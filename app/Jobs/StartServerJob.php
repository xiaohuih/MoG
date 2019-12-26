<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class StartServerJob implements ShouldQueue
{
    public static $file = 'states/game/files/scripts/modify-pillar.sh';
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
        $script = storage_path('app/salt') . DIRECTORY_SEPARATOR . self::$file;
        Log::debug(sprintf("Start server {%s} {%s}", $this->zone, $this->version));
        $script_shell = sprintf("sudo bash %s", $script);
        exec($script_shell, $result_shell, $shell_status);
        $shell = sprintf("sudo salt '*' state.apply game.start pillar='{\"zone\": \"%s\", \"version\": \"%s\"}'", $this->zone, $this->version);
        exec($shell, $result, $status);
        Log::debug($result);
        if ($status != 0) {
            Log::error(sprintf("Start server {%s} {%s} failed", $this->zone, $this->version));
            return;
        }
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
