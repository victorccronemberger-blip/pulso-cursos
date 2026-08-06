<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class AppSetup extends Command
{
    protected $signature = 'app:setup
                            {--admin-name=Admin : Name of the admin user}
                            {--admin-email= : Email of the admin user (required)}
                            {--admin-password= : Password of the admin user (required)}
                            {--admin-phone= : Phone number of the admin user}
                            {--admin-address= : Address of the admin user}
                            {--system-name=BIM Bangladesh : System name}
                            {--timezone=Asia/Dhaka : Timezone}
                            {--import-sql : Import the install.sql seed data}
                            {--force : Skip confirmation prompts}';

    protected $description = 'Manual setup: configure database, create admin user, and seed system settings';

    public function handle(): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║   BIM Bangladesh - Manual Setup          ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->info('');

        // 1. Validate database connection
        if (!$this->validateDatabase()) {
            return self::FAILURE;
        }

        // 2. Validate admin inputs
        $adminEmail = $this->option('admin-email');
        $adminPassword = $this->option('admin-password');

        if (empty($adminEmail)) {
            $adminEmail = $this->ask('Admin email');
        }
        if (empty($adminPassword)) {
            $adminPassword = $this->secret('Admin password');
        }
        if (empty($this->option('admin-name'))) {
            $this->option('admin-name', $this->ask('Admin name', 'Admin'));
        }

        // Confirm before proceeding
        if (!$this->option('force')) {
            $this->info('');
            $this->info('This will set up the application with:');
            $this->info("  Database: " . env('DB_DATABASE'));
            $this->info("  Admin:    {$adminEmail}");
            $this->info("  System:   {$this->option('system-name')}");
            $this->info('');

            if (!$this->confirm('Proceed with setup?', true)) {
                $this->info('Setup cancelled.');
                return self::SUCCESS;
            }
        }

        // 3. Run migrations
        $this->info('');
        $this->info('Running migrations...');
        $exitCode = $this->call('migrate', ['--force' => true]);
        if ($exitCode !== 0) {
            $this->error('Migration failed. Check your database configuration.');
            return self::FAILURE;
        }

        // 4. Import SQL (if requested)
        if ($this->option('import-sql')) {
            $this->info('');
            $this->info('Importing seed SQL...');
            $this->importSql();
        }

        // 5. Create admin user
        $this->info('');
        $this->info('Creating admin user...');
        $this->createAdminUser($adminEmail, $adminPassword);

        // 6. Setup system settings
        $this->info('');
        $this->info('Setting up system configuration...');
        $this->setupSystemSettings();

        // 7. Generate app key if missing
        $this->info('');
        $this->info('Generating application key...');
        $this->call('key:generate', ['--force' => true]);

        // Success
        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║   Setup completed successfully!          ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->info('');
        $this->info("  Login with: {$adminEmail}");
        $this->info("  URL:        " . env('APP_URL', 'http://localhost'));
        $this->info('');

        return self::SUCCESS;
    }

    private function validateDatabase(): bool
    {
        $this->info('Testing database connection...');

        try {
            $db = DB::connection();
            $dbName = $db->getDatabaseName();

            if ($dbName === 'db_name' || empty($dbName)) {
                $this->error('Database is not configured!');
                $this->info('');
                $this->info('Please configure your .env file first:');
                $this->info('  DB_HOST=127.0.0.1');
                $this->info('  DB_PORT=3306');
                $this->info('  DB_DATABASE=academy_lite');
                $this->info('  DB_USERNAME=root');
                $this->info('  DB_PASSWORD=');
                $this->info('');
                $this->info('Then run: php artisan app:setup --admin-email=admin@example.com --admin-password=secret');
                return false;
            }

            $db->getPdo();
            $this->info("  Connected to: {$dbName}");
            return true;
        } catch (\Exception $e) {
            $this->error("Database connection failed: {$e->getMessage()}");
            $this->info('');
            $this->info('Check your .env file and ensure MySQL is running.');
            return false;
        }
    }

    private function createAdminUser(string $email, string $password): void
    {
        $adminName = $this->option('admin-name');
        $adminPhone = $this->option('admin-phone');
        $adminAddress = $this->option('admin-address');

        // Check if admin already exists
        $existing = DB::table('users')->where('email', $email)->first();
        if ($existing) {
            $this->warn("  User with email '{$email}' already exists. Skipping admin creation.");
            return;
        }

        DB::table('users')->insert([
            'name'              => $adminName,
            'email'             => $email,
            'password'          => Hash::make($password),
            'role'              => 'admin',
            'status'            => 1,
            'phone'             => $adminPhone ?? '',
            'address'           => $adminAddress ?? '',
            'skills'            => json_encode([]),
            'email_verified_at' => date('Y-m-d H:i:s'),
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ]);

        $this->info("  Admin user created: {$email}");
    }

    private function setupSystemSettings(): void
    {
        $systemName = $this->option('system-name');
        $timezone = $this->option('timezone');

        $settings = [
            'system_name' => $systemName,
            'timezone'    => $timezone,
        ];

        foreach ($settings as $key => $value) {
            $exists = DB::table('settings')->where('type', $key)->count() > 0;
            if ($exists) {
                DB::table('settings')->where('type', $key)->update([
                    'description' => $value,
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
            } else {
                DB::table('settings')->insert([
                    'type'        => $key,
                    'description' => $value,
                    'created_at'  => date('Y-m-d H:i:s'),
                    'updated_at'  => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Set default currency if not exists
        $currencyExists = DB::table('settings')->where('type', 'system_currency')->count() > 0;
        if (!$currencyExists) {
            DB::table('settings')->insert([
                'type'        => 'system_currency',
                'description' => 'BDT',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        $currencyPosExists = DB::table('settings')->where('type', 'currency_position')->count() > 0;
        if (!$currencyPosExists) {
            DB::table('settings')->insert([
                'type'        => 'currency_position',
                'description' => 'left-space',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        $this->info("  System settings configured");
    }

    private function importSql(): void
    {
        $sqlPath = base_path('public/assets/install.sql');

        if (!file_exists($sqlPath)) {
            $this->warn("  install.sql not found at: {$sqlPath}");
            return;
        }

        $templine = '';
        $lines = file($sqlPath);
        $count = 0;

        foreach ($lines as $line) {
            if (substr($line, 0, 2) == '--' || $line == '') {
                continue;
            }

            $templine .= $line;

            if (substr(trim($line), -1, 1) == ';') {
                try {
                    DB::statement($templine);
                    $count++;
                } catch (\Exception $e) {
                    // Skip duplicate table errors
                    if (str_contains($e->getMessage(), 'already exists')) {
                        continue;
                    }
                    $this->warn("  SQL Warning: " . substr($e->getMessage(), 0, 100));
                }
                $templine = '';
            }
        }

        $this->info("  Imported {$count} SQL statements");
    }
}
