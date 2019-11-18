<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

use App\Models\OM;

class StartServerEntranceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The zone to modify
     */
    protected $zone;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($zone)
    {
        $this->zone = $zone;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::debug(sprintf("Start server {%d} entrance", $this->zone));
        $prve_zone = $this->zone - 1;
        OM\Server::modify($prve_zone, 'state', 2);
        OM\Server::modify($this->zone, 'state', 1);
    }
}
