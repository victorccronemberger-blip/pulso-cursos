<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use App\Models\FileUploader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MyProfileController extends Controller
{
    public function index()
    {
        $user = User::findOrFail(auth()->id());
        $fullName = trim((string) $user->name);
        $lastName = trim((string) $user->last_name);
        $firstName = $fullName;

        if ($lastName !== '' && Str::endsWith($fullName, ' ' . $lastName)) {
            $firstName = trim(Str::beforeLast($fullName, ' ' . $lastName));
        } elseif ($lastName === '' && $fullName !== '') {
            $nameParts = preg_split('/\s+/', $fullName, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';
        }

        $page_data['user_details'] = $user;
        $page_data['first_name'] = $firstName;
        $page_data['last_name'] = $lastName;
        $view_path                 = 'frontend.' . get_frontend_settings('theme') . '.student.my_profile.index';
        return view($view_path, $page_data);
    }

    public function update(Request $request, $user_id)
    {
        abort_unless((int) $user_id === (int) auth()->id(), 403);

        $linkedin = trim((string) $request->input('linkedin'));
        if ($linkedin !== '' && !Str::startsWith($linkedin, ['http://', 'https://'])) {
            $request->merge(['linkedin' => 'https://' . $linkedin]);
        }

        $rules = [
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:120',
            'phone' => 'nullable|string|max:30',
            'linkedin' => 'nullable|url|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $firstName = trim((string) $request->name);
        $lastName = trim((string) $request->last_name);
        $data = [
            'name' => trim($firstName . ' ' . $lastName),
            'last_name' => $lastName,
            'phone' => trim((string) $request->phone) ?: null,
            'linkedin' => trim((string) $request->linkedin) ?: null,
        ];

        User::where('id', auth()->id())->update($data);
        Session::flash('success', 'Perfil atualizado com sucesso.');
        return redirect()->back();
    }

    public function update_profile_picture(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp,tiff|max:3072',
        ]);

        // process file
        $file      = $request->photo;
        $file_name = Str::random(20) . '.' . $file->extension();
        $path      = 'uploads/users/' . auth()->user()->role . '/' . $file_name;
        FileUploader::upload($file, $path, null, null, 300);

        User::where('id', auth()->user()->id)->update(['photo' => $path]);
        Session::flash('success', get_phrase('Profile picture updated.'));
        return redirect()->back();
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:4',
            'confirm_password' => 'required|same:new_password',
        ]);

        // Check if the current password is correct
        if (!Auth::attempt(['email' => auth()->user()->email, 'password' => $request->current_password])) {
            Session::flash('error', 'A senha atual está incorreta.');
            return redirect()->back();
        }

        // Update password
        auth()->user()->update(['password' => Hash::make($request->new_password)]);

        Session::flash('success', 'Senha alterada com sucesso.');
        return redirect()->back();
    }
}
