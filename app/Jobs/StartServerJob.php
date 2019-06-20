<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class StartServerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $zone;
    protected $version;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($zone, $version)
    {
        $this->zone = $zone;
        $this->version = $version;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug(sprintf("Start server {%s} {%s}", $this->zone, $this->version));
        $shell = sprintf("sudo salt '*' state.apply game.start pillar='{\"zone\": \"%s\", \"version\": \"%s\"}'", $this->zone, $this->version);
        exec($shell, $result, $status);
        Log::debug($result);
    }
    
    /**
     * The job failed to process.
     *
     * @param  Exception  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        // Send user notification of failure, etc...
    }
}
