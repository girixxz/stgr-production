<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Helpers\CacheHelper;

class ClearStaticCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-static
                          {--warmup : Warmup cache after clearing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cached static data (product categories, materials, etc)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing static data cache...');
        
        CacheHelper::clearAll();
        
        $this->info('✅ Static cache cleared successfully!');

        if ($this->option('warmup')) {
            $this->info('Warming up cache...');
            CacheHelper::warmUp();
            $this->info('✅ Cache warmed up successfully!');
        }

        return Command::SUCCESS;
    }
}
