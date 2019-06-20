<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs;

class StartServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:start {zone} {version=0.0.0.0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a specific game zone';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $zone = $this->argument('zone');
        $version = $this->argument('version');

        // 开服
        Jobs\StartServerJob::dispatch($zone, $version);
        // 打开入口
        Jobs\StartServerEntranceJob::dispatch($zone)
            ->delay(now()->addMinutes(1));
            
        $this->info(sprintf("Start server {%d} by version {%s}.", $zone, $version));
    }
}
