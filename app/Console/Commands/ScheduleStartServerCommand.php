<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\OM;

class ScheduleStartServerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:schedule-start {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a specific game zone by schedule';

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
        $id = $this->argument('id');

        $schedule = OM\ServerStart::findOrFail($id);
        $schedule->start();

        $this->info(sprintf("Start server by schedule %d.", $id));
    }
}
