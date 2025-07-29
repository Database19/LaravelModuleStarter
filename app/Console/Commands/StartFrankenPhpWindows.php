<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StartFrankenPhpWindows extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'frankenphp:start
                            {--port=8000 : Port untuk server}
                            {--watch : Enable file watching}
                            {--workers=auto : Jumlah workers}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start FrankenPHP server dengan Windows compatibility';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // EMERGENCY FIX: Prevent hanging/infinite loop
        $this->error('🚨 EMERGENCY NOTICE: This command has been disabled due to hanging issues on Windows');
        $this->error('❌ This command can cause system hang and require restart');
        $this->newLine();

        $this->info('✅ Safe alternatives:');
        $this->info('1. Use Docker: frankenphp.bat docker');
        $this->info('2. Use binary: frankenphp.bat binary');
        $this->info('3. Use regular Laravel server: php artisan serve');
        $this->newLine();

        $this->warn('🔧 We are working on a fix for the Windows hanging issue');
        $this->info('💡 For now, please use the Docker method for best results');

        return Command::FAILURE;
    }

    /**
     * Define signal constants untuk Windows compatibility
     */
    private function defineSignalConstants(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            if (!defined('SIGINT')) define('SIGINT', 2);
            if (!defined('SIGTERM')) define('SIGTERM', 15);
            if (!defined('SIGHUP')) define('SIGHUP', 1);
            if (!defined('SIGUSR1')) define('SIGUSR1', 10);
            if (!defined('SIGUSR2')) define('SIGUSR2', 12);
            if (!defined('SIGKILL')) define('SIGKILL', 9);
            if (!defined('SIGQUIT')) define('SIGQUIT', 3);

            $this->info('✅ Windows signal constants defined successfully');
        }
    }
}
