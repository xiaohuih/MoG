<?php

namespace App\Models\OM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Scheduling;
use Illuminate\Support\Facades\Artisan;
use PDOException;

class ServerStart extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'status',
    ];
    /**
     * Add commands event to the schedule.
     *
     */
    public static function schedule(Scheduling\Schedule $schedule)
    {
        try {
            $schedules = self::all();
        } catch(\PDOException $exception) {
            $schedules = [];
        }
        
        foreach ($schedules as $key => $value) {
            if ($value['starttime'] && empty($value['status'])) {
                $cron = Schedule::formatTimestampToCron(strtotime($value['starttime']));
                $schedule->command('game:schedule-start', [$value['id']])->cron($cron);
            }
        }
    }

    /**
     * 开服
     */
    public function start()
    {
        try {
            Artisan::call("game:start", ['zone' => $this->zone]);
            $this->update(['status' => 1]);
        } catch (\Exception $e) {
            return [
                'status'    => false,
                'message'   => 'failed',
            ];
        }
        return [
            'status'    => true,
            'message'   => 'success',
        ];
    }
}
