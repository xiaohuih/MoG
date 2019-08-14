<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs;

class PatchServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:patch {zone} {sversion} {tversion=0.0.0.0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Patch to a specific game zone';

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
        $sversion = $this->argument('sversion');
        $tversion = $this->argument('tversion');

        // 补丁
        Jobs\PatchServerJob::dispatch($zone, $sversion, $tversion);
            
        $this->info(sprintf("patch server {%s} from version {%s} to {%s}.", $zone, $tversion, $sversion));
    }
}
