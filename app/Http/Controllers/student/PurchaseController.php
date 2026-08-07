<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment_history;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PurchaseController extends Controller
{
    public function purchase_history()
    {
        $page_data['payments'] = Payment_history::join('courses', 'payment_histories.course_id', 'courses.id')
            ->join('users', 'payment_histories.user_id', 'users.id')
            ->where('payment_histories.user_id', auth()->user()->id)
            ->select('payment_histories.*', 'courses.title as course_title', 'users.name as user_name')
            ->latest('id')->paginate(10);
        $view_path = 'frontend.' . get_frontend_settings('theme') . '.student.purchase_history.index';
        return view($view_path, $page_data);
    }

    public function invoice($id)
    {
        // validate course id
        if (!is_numeric($id) || (int) $id < 1) {
            Session::flash('error', get_phrase('Data not found.'));
            return redirect()->back();
        }

        // check existence
        $payment = Payment_history::join('courses', 'payment_histories.course_id', 'courses.id')
            ->join('users', 'payment_histories.user_id', 'users.id')
            ->where('payment_histories.id', $id)
            ->where('payment_histories.user_id', auth()->id())
            ->select('payment_histories.*', 'courses.title as course_title', 'users.name as user_name')->first();
        if (!$payment) {
            Session::flash('error', get_phrase('Data not found.'));
            return redirect()->back();
        }

        $page_data['invoice'] = $payment;
        $view_path            = 'frontend.' . get_frontend_settings('theme') . '.student.purchase_history.invoice';
        return view($view_path, $page_data);
    }

    public function purchase_course($course_id)
    {
        // validate course id
        if (!is_numeric($course_id) || (int) $course_id < 1) {
            Session::flash('error', get_phrase('Data not found.'));
            return redirect()->back();
        }

        // check personal course
        if (Course::where('id', $course_id)->where('user_id', auth()->user()->id)->exists()) {
            Session::flash('error', get_phrase('Ops! You own this course.'));
            return redirect()->back();
        }

        // Check if the course is purchased and not expired
        $existingEnrollment = Enrollment::where('user_id', auth()->user()->id)
            ->where('course_id', $course_id)
            ->where(function ($query) {
                $query->where('expiry_date', '>', now()->timestamp)
                    ->orWhereNull('expiry_date');
            })
            ->exists();

        if ($existingEnrollment) {
            Session::flash('error', get_phrase('You already enrolled in this course'));
            return redirect()->back();
        }

        // get course details by id
        $course_details = Course::where('id', $course_id)->first();

        // if course doesn't exist redirect back
        if (!$course_details) {
            Session::flash('error', get_phrase('Data not found.'));
            return redirect()->back();
        }

        // if course is free then enroll user and redirect to my courses
        if ($course_details->is_paid == 0) {
            $enrollment['user_id']         = auth()->user()->id;
            $enrollment['course_id']       = $course_id;
            $enrollment['enrollment_type'] = 'free';
            $enrollment['entry_date']      = time();

            $course_details = get_course_info($course_id);

            if ($course_details->expiry_period > 0) {
                $days = $course_details->expiry_period * 30;
                $enrollment['expiry_date'] = strtotime("+" . $days . " days");
            } else {
                $enrollment['expiry_date'] = null;
            }

            $enrollment['updated_at'] = now();
            $enrollment['created_at'] = now();
            Enrollment::insert($enrollment);
            return redirect()->route('my.courses');
        } else {
            $query = CartItem::where('course_id', $course_id)->where('user_id', auth()->user()->id);
            if ($query->count() == 0) {
                CartItem::insert(['user_id' => auth()->user()->id, 'course_id' => $course_id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => now()]);
                return redirect(route('cart'));
            } elseif ($query->count() == 1) {
                return redirect(route('cart'));
            }
        }

        // redirect to cart store
        return redirect()->back();
    }

    public function payout(Request $request)
    {
        // Prices, taxes and item ids must come from the current server-side cart.
        // Hidden inputs in the cart page are display helpers and are not purchase authority.
        $cart_courses = CartItem::join('courses', 'cart_items.course_id', '=', 'courses.id')
            ->where('cart_items.user_id', auth()->id())
            ->select('courses.*')
            ->get();

        if ($cart_courses->isEmpty()) {
            Session::flash('error', get_phrase('Your cart is empty.'));
            return redirect()->route('cart');
        }

        $items_id = $cart_courses->pluck('id')->map(fn ($id) => (int) $id)->all();
        $courses = $items_id;

        foreach ($cart_courses as $course) {
            if ($course->user_id == auth()->id()) {
                Session::flash('error', get_phrase('Ops! You own this course.'));
                return redirect()->route('cart');
            }

            $already_enrolled = Enrollment::where('course_id', $course->id)
                ->where('user_id', auth()->id())
                ->where(function ($query) {
                    $query->where('expiry_date', '>', now()->timestamp)->orWhereNull('expiry_date');
                })
                ->exists();

            if ($already_enrolled) {
                Session::flash('error', get_phrase('You already enrolled in this course'));
                return redirect()->route('cart');
            }
        }

        // if order is gift then select gifted user id
        if ($request->gifted_user_email) {
            $gifted_user_id = User::where('role', '!=', 'admin')->where('email', $request->gifted_user_email)->value('id');
            if (!$gifted_user_id) {
                Session::flash('error', get_phrase("User email doesn't exists."));
                return redirect()->back();
            }

            $courses = [];
            foreach ($items_id as $item) {
                if (Enrollment::where('course_id', $item)->where('user_id', $gifted_user_id)->doesntExist()) {
                    $courses[] = $item;
                }
            }

            if (count($courses) == 0) {
                Session::flash('error', get_phrase('User already enrolled.'));
                return redirect()->back();
            }
        }

        $selected_courses = $cart_courses->whereIn('id', $courses)->values();

        $subtotal = 0;
        foreach ($selected_courses as $course) {
            $subtotal += $course->discount_flag ? (float) $course->discounted_price : (float) $course->price;
        }

        $coupon_code = trim((string) $request->input('coupon_code'));
        $coupon = null;
        if ($coupon_code !== '') {
            $coupon = Coupon::where('code', $coupon_code)
                ->where('status', 1)
                ->where('expiry', '>', time())
                ->first();

            if (! $coupon) {
                Session::flash('error', get_phrase('This coupon is not valid.'));
                return redirect()->route('cart');
            }
        }

        $coupon_discount = $coupon ? round($subtotal * ((float) $coupon->discount / 100), 2) : 0;
        $tax_rate = max(0, (float) get_settings('course_selling_tax'));
        $tax = round(($subtotal - $coupon_discount) * ($tax_rate / 100), 2);
        $payable = round(max(0, $subtotal - $coupon_discount + $tax), 2);

        // prepare each item by its id
        foreach ($selected_courses as $key => $course) {
            $items[] = [
                'id'             => $course->id,
                'title'          => $course->title,
                'subtitle'       => '',
                'price'          => $course->price,
                'discount_price' => $course->discount_flag ? $course->discounted_price : 0,
            ];
        }

        $payment_details = [
            'items'          => $items,

            'custom_field'   => [
                'item_type'       => 'course',
                'pay_for'         => 'course payment',
                'user_id'         => auth()->user()->id,
                'user_photo'      => auth()->user()->photo,
                'cart_id'         => $courses,
                'coupon_discount' => $coupon_discount,
                'gifted_user_id'  => $gifted_user_id ?? '',
            ],

            'success_method' => [
                'model_name'    => 'PurchaseCourse',
                'function_name' => 'purchase_course',
            ],

            'tax'            => $tax,
            'coupon'         => $coupon?->code,
            'payable_amount' => $payable,
            'cancel_url'     => route('cart'),
            'success_url'    => route('payment.success', ''),
        ];

        Session::put(['payment_details' => $payment_details]);

        if ($payable <= 0) {
            return \App\Models\PurchaseCourse::purchase_course('coupon');
        }

        return redirect()->route('payment');
    }
}
