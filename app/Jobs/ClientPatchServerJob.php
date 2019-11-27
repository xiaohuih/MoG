<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\OM;

class ClientPatchServerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // protected $zone;
    protected $platform;
    protected $version;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($platform, $version)
    {
        // $this->zone = $zone;
        $this->platform = $platform;
        $this->version = $version;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug(sprintf("Clinet patch update {%s} version {%s} start", $this->platform, $this->version));
        $script_file =  storage_path() . DIRECTORY_SEPARATOR .'app/game/assets/client-patch.sh';
        $shell = sprintf("bash %s %s %s", $script_file, $this->version, $this->platform);
        exec($shell, $result, $status);
        if ($status != 0) {
            Log::error(sprintf("Client Patch update {%s} version {%s}  failed",  $this->platform, $this->version));
            return;
        }
        Log::debug(sprintf("Client Patch update {%s} version {%s}  success",  $this->platform, $this->version));
    }
}
