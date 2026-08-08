<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

require dirname(__DIR__, 2).'/vendor/autoload.php';
$app = require dirname(__DIR__, 2).'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

if (($argv[1] ?? null) === '--id' && isset($argv[2])) {
    echo json_encode(DB::table('courses')->where('id', (int) $argv[2])->first(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), PHP_EOL;
    exit;
}

if (($argv[1] ?? null) === '--categories') {
    echo json_encode(DB::table('categories')->orderBy('id')->get(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), PHP_EOL;
    exit;
}

$courses = DB::table('courses')
    ->select(['id', 'title', 'slug', 'status', 'is_paid', 'price', 'discount_flag', 'discounted_price', 'user_id'])
    ->orderBy('id')
    ->get();

foreach ($courses as $course) {
    printf(
        "%3d | %-8s | %-70s | status=%s paid=%s price=%s discount=%s owner=%s\n",
        $course->id,
        $course->title,
        $course->slug,
        $course->status,
        $course->is_paid,
        $course->price,
        $course->discount_flag ? $course->discounted_price : '-',
        $course->user_id,
    );
}
