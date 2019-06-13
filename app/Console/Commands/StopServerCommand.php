<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs;

class StopServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:stop {zone=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop a specific game zone';

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

        // 关闭入口
        if ("*" == $zone) {
            Jobs\StopServerEntranceJob::dispatch($zone);
        } else {
            Jobs\StopMainServerEntranceJob::dispatch();
        }
        // 关服
        Jobs\StopServerJob::dispatch($zone);
    }
}
