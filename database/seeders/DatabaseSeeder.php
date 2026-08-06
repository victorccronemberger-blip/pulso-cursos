<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
            CourseSeeder::class,
        ]);

        // dummy2.jpg does not exist - only use existing images
        $dummyImages = [
            'assets/global/images/dummy0.jpg',
            'assets/global/images/dummy1.jpg',
            'assets/global/images/dummy3.jpg',
            'assets/global/images/dummy4.jpg',
            'assets/global/images/dummy5.jpg',
        ];

        $banglaNames = [
            'মোহাম্মদ রাহিম উদ্দিন',
            'ফাতেমা বেগম',
            'আবদুল করিম',
            'সুমাইয়া আক্তার',
            'মোঃ আরিফ হোসেন',
            'নাজমা খানম',
            'রফিকুল ইসলাম',
            'শামীমা নাসরিন',
            'তানভীর আহমেদ',
            'মরিয়ম বেগম',
            'জাহিদুল হাসান',
            'রোকেয়া সুলতানা',
            'ইমরান হোসেন',
            'সাবিনা ইয়াসমিন',
            'নুরুল আমিন',
            'হাসিনা বেগম',
            'শাহরিয়ার কবির',
            'মাহমুদা খাতুন',
        ];

        // =========================
        // Admins (3)
        // =========================
        $adminAbouts = [
            'আমি একজন অভিজ্ঞ শিক্ষা প্রশাসক। বাংলাদেশের অনলাইন শিক্ষা বিস্তারে কাজ করছি।',
            'ডিজিটাল শিক্ষা প্ল্যাটফর্ম পরিচালনায় আমার ৫ বছরের অভিজ্ঞতা রয়েছে।',
            'শিক্ষার্থীদের জন্য সেরা মানের কোর্স নিশ্চিত করা আমার লক্ষ্য।',
        ];

        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'role'              => 'admin',
                'email'             => 'admin' . $i . '@growup.com',
                'status'            => 1,
                'name'              => $banglaNames[$i - 1],
                'phone'             => '+880 170000000' . $i,
                'website'           => 'https://academy.com.bd',
                'skills'            => 'প্রশাসন, ব্যবস্থাপনা, পরিকল্পনা',
                'facebook'          => 'https://facebook.com',
                'twitter'           => 'https://twitter.com',
                'linkedin'          => 'https://linkedin.com',
                'address'           => 'ঢাকা, বাংলাদেশ',
                'about'             => $adminAbouts[$i - 1],
                'photo'             => $dummyImages[array_rand($dummyImages)],
                'email_verified_at' => now(),
                'password'          => Hash::make('password123'),
            ]);
        }

        // =========================
        // Instructors (5)
        // =========================
        $instructorSkills = [
            'ওয়েব ডেভেলপমেন্ট, পিএইচপি, লারাভেল, মাইএসকিউএল',
            'পাইথন, ডেটা সায়েন্স, মেশিন লার্নিং',
            'গ্রাফিক ডিজাইন, অ্যাডোবি ফটোশপ, ইলাস্ট্রেটর',
            'ডিজিটাল মার্কেটিং, এসইও, সোশ্যাল মিডিয়া',
            'মোবাইল অ্যাপ, ফ্লাটার, অ্যান্ড্রয়েড',
        ];
        $instructorAbouts = [
            'আমি একজন পেশাদার ওয়েব ডেভেলপার। ৭ বছর ধরে ওয়েব প্রযুক্তি নিয়ে কাজ করছি এবং ৫০০+ শিক্ষার্থীকে প্রশিক্ষণ দিয়েছি।',
            'ডেটা সায়েন্স ও আর্টিফিশিয়াল ইন্টেলিজেন্সে আমার গভীর জ্ঞান রয়েছে। বিভিন্ন প্রতিষ্ঠানে প্রশিক্ষণ দিয়েছি।',
            'গ্রাফিক ডিজাইনে আমার ৬ বছরের অভিজ্ঞতা আছে। দেশি-বিদেশি ক্লায়েন্টদের সাথে কাজ করেছি।',
            'ডিজিটাল মার্কেটিং বিশেষজ্ঞ হিসেবে বিভিন্ন ব্যবসা প্রতিষ্ঠানকে অনলাইনে সফল করতে সহায়তা করেছি।',
            'মোবাইল অ্যাপ ডেভেলপমেন্টে আমার ৪ বছরের অভিজ্ঞতা রয়েছে। গুগল প্লে স্টোরে ১০+ অ্যাপ প্রকাশিত হয়েছে।',
        ];

        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'role'              => 'instructor',
                'email'             => 'instructor' . $i . '@growup.com',
                'status'            => 1,
                'name'              => $banglaNames[$i + 2],
                'phone'             => '+880 180000000' . $i,
                'website'           => 'https://academy.com.bd',
                'skills'            => $instructorSkills[$i - 1],
                'facebook'          => 'https://facebook.com',
                'twitter'           => 'https://twitter.com',
                'linkedin'          => 'https://linkedin.com',
                'address'           => 'ঢাকা, বাংলাদেশ',
                'about'             => $instructorAbouts[$i - 1],
                'photo'             => $dummyImages[array_rand($dummyImages)],
                'email_verified_at' => now(),
                'password'          => Hash::make('password123'),
            ]);
        }

        // =========================
        // Students (10)
        // =========================
        $studentAbouts = [
            'আমি একজন বিশ্ববিদ্যালয় শিক্ষার্থী। নতুন দক্ষতা অর্জনে আগ্রহী।',
            'ফ্রিল্যান্সিং ক্যারিয়ার শুরু করতে চাই। অনলাইনে শিখছি।',
            'সফটওয়্যার ইঞ্জিনিয়ার হওয়ার স্বপ্ন নিয়ে পড়াশোনা করছি।',
            'ডিজাইনের প্রতি আগ্রহ থেকে কোর্স করছি।',
            'ডিজিটাল মার্কেটিং শিখে নিজের ব্যবসা বাড়াতে চাই।',
            'প্রোগ্রামিং শেখা আমার শখ। প্রতিদিন নতুন কিছু শিখি।',
            'এইচএসসি পাশ করে অনলাইনে দক্ষতা বাড়াচ্ছি।',
            'গ্রাফিক ডিজাইন শিখে ফ্রিল্যান্সিং করতে চাই।',
            'ডেটা সায়েন্সে ক্যারিয়ার গড়ার লক্ষ্যে কোর্স করছি।',
            'ওয়েব ডেভেলপমেন্ট শিখে চাকরি খুঁজছি।',
        ];

        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'role'              => 'student',
                'email'             => 'student' . $i . '@growup.com',
                'status'            => 1,
                'name'              => $banglaNames[$i + 7],
                'phone'             => '+880 190000000' . $i,
                'skills'            => 'শেখার আগ্রহ, পরিশ্রমী, মনোযোগী',
                'facebook'          => null,
                'twitter'           => null,
                'linkedin'          => null,
                'address'           => 'বাংলাদেশ',
                'about'             => $studentAbouts[$i - 1],
                'photo'             => $dummyImages[array_rand($dummyImages)],
                'email_verified_at' => now(),
                'password'          => Hash::make('password123'),
            ]);
        }
    }
}
