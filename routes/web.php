<?php

use App\Http\Controllers\CommonController;
use App\Http\Controllers\frontend\HomeController;
use App\Http\Controllers\InstallController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

//Cache clear route
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Cache::flush();

    return 'Application cache cleared';
});

Route::get('home/switch/{id}', [HomeController::class, 'homepage_switcher'])->name('home.switch');

//Redirect route
Route::get('/dashboard', function () {
    if (auth()->user()->role == 'admin') {
        return redirect(route('admin.dashboard'));
    } elseif (auth()->user()->role == 'student') {
        return redirect(route('my.courses'));
    } else {
        return redirect(route('home'));
    }
})->middleware(['auth', 'verified'])->name('dashboard');

//Common modal route
Route::get('modal/{view_path}', [ModalController::class, 'common_view_function'])->name('modal');
Route::any('get-video-details/{url?}', [CommonController::class, 'get_video_details'])->name('get.video.details');
Route::get('view/{path}', [CommonController::class, 'rendered_view'])->name('view');

Route::get('closed_back_to_mobile_ber', function () {
    session()->forget('app_url');
    return redirect()->back();
})->name('closed_back_to_mobile_ber');

//Mobile payment redirect
Route::get('payment/web_redirect_to_pay_fee', [PaymentController::class, 'webRedirectToPayFee'])->name('payment.web_redirect_to_pay_fee');

//Installation routes
Route::controller(InstallController::class)->group(function () {
    Route::get('install/step0', 'step0')->name('step0');
    Route::get('install/step1', 'step1')->name('step1');
    Route::get('install/step2', 'step2')->name('step2');
    Route::any('install/step3', 'step3')->name('step3');
    Route::get('install/step4', 'step4')->name('step4');
    Route::get('install/step4/{confirm_import}', 'confirmImport')->name('step4.confirm_import');
    Route::get('install/install', 'confirmInstall')->name('confirm_install');
    Route::post('install/validate', 'validatePurchaseCode')->name('install.validate');
    Route::any('install/finalizing_setup', 'finalizingSetup')->name('finalizing_setup');
    Route::get('install/success', 'success')->name('success');
});
//Installation routes


//Frontend vide_banner routes

Route::get('/stream/banner-video', function () {
    $path = public_path(get_frontend_settings('banner_video'));

    if (!file_exists($path)) {
        abort(404);
    }

    $size     = filesize($path);
    $mimeType = 'video/mp4';
    $start    = 0;
    $end      = $size - 1;

    $headers = [
        'Content-Type'              => $mimeType,
        'Accept-Ranges'             => 'bytes',
        'Content-Length'            => $size,
        'Cache-Control'             => 'no-cache, must-revalidate',
    ];

    // Handle range request for seeking
    if (request()->hasHeader('Range')) {
        $range = request()->header('Range');
        preg_match('/bytes=(\d+)-(\d*)/', $range, $matches);

        $start = intval($matches[1]);
        $end   = isset($matches[2]) && $matches[2] !== '' ? intval($matches[2]) : $size - 1;
        $length = $end - $start + 1;

        $headers['Content-Range']  = "bytes $start-$end/$size";
        $headers['Content-Length'] = $length;

        return response()->stream(function () use ($path, $start, $length) {
            $stream = fopen($path, 'rb');
            fseek($stream, $start);
            $remaining = $length;
            while (!feof($stream) && $remaining > 0) {
                $chunkSize = min(8192, $remaining);
                echo fread($stream, $chunkSize);
                $remaining -= $chunkSize;
                flush();
            }
            fclose($stream);
        }, 206, $headers); // 206 Partial Content
    }

    // Normal full request
    return response()->stream(function () use ($path) {
        $stream = fopen($path, 'rb');
        while (!feof($stream)) {
            echo fread($stream, 8192);
            flush();
        }
        fclose($stream);
    }, 200, $headers);
});
