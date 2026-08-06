<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebConfig
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $s3_keys = get_settings('amazon_s3', 'object') ?? (object) [
                'AWS_ACCESS_KEY_ID' => '',
                'AWS_SECRET_ACCESS_KEY' => '',
                'AWS_DEFAULT_REGION' => '',
                'AWS_BUCKET' => '',
            ];

            config([
                'app.name' => get_settings('system_title') ?? config('app.name'),
                'app.timezone' => get_settings('timezone') ?? config('app.timezone'),

                // SMTP configuration
                'mail.mailers.smtp.transport' => get_settings('protocol'),
                'mail.mailers.smtp.host' => get_settings('smtp_host'),
                'mail.mailers.smtp.port' => get_settings('smtp_port'),
                'mail.mailers.smtp.encryption' => get_settings('smtp_crypto'),
                'mail.mailers.smtp.username' => get_settings('smtp_user'),
                'mail.mailers.smtp.password' => get_settings('smtp_pass'),
                'mail.mailers.smtp.timeout' => null,
                'mail.mailers.smtp.local_domain' => $_SERVER['SERVER_NAME'] ?? 'localhost',
                'mail.from.name' => get_settings('system_title') ?? config('app.name'),
                'mail.from.address' => get_settings('smtp_from_email'),

                'filesystems.disks.s3.key' => $s3_keys->AWS_ACCESS_KEY_ID ?? '',
                'filesystems.disks.s3.secret' => $s3_keys->AWS_SECRET_ACCESS_KEY ?? '',
                'filesystems.disks.s3.region' => $s3_keys->AWS_DEFAULT_REGION ?? '',
                'filesystems.disks.s3.bucket' => $s3_keys->AWS_BUCKET ?? '',
            ]);
        } catch (\Exception $e) {
            // Database tables not yet created — use Laravel config defaults
        }

        return $next($request);
    }
}
