<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Models\OM;

class PatchServerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $zone;
    protected $sversion;
    protected $tversion;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($zone, $sversion, $tversion)
    {
        $this->zone = $zone;
        $this->sversion = $sversion;
        $this->tversion = $tversion;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug(sprintf("Patch server {%s} from version {%s} to {%s} start", $this->zone, $this->tversion, $this->sversion));
        $shell = sprintf("sudo salt '*' state.apply game.install-patch pillar='{\"zone\": \"%s\", \"sversion\": \"%s\", \"tversion\": \"%s\"}'", 
            $this->zone, $this->sversion, $this->tversion);
        exec($shell, $result, $status);
        Log::debug($result);
        if ($status != 0) {
            Log::error(sprintf("Patch server {%s} from version {%s} to {%s} failed", $this->zone, $this->tversion, $this->sversion));
            return;
        }
        Log::debug(sprintf("Patch server {%s} from version {%s} to {%s} success", $this->zone, $this->tversion, $this->sversion));
    }
}
