<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs;

class ClientPatchServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:client-patch {platform} update to {version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Client Patch description';

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
        $platform = $this->argument('platform');
        $version = $this->argument('version');

        // 补丁
        Jobs\ClientPatchServerJob::dispatch($platform, $version);
            
        $this->info(sprintf("clinet patch update {%s} version to {%s}.", $platform, $version));
    }
}
