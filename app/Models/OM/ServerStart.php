<?php

namespace App\Models\OM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Scheduling;
use Illuminate\Support\Facades\Artisan;

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
        $schedules = self::all();
        
        foreach ($schedules as $key => $value) {
            if ($value['starttime'] && ($value['status'] && $value['status'] == 1)) {
                $cron = Schedule::formatTimestampToCron(strtotime($value['starttime']));
                $schedule->command('game:start', [$value['zone']])->cron($cron);
                
                self::find($value['id'])->update(['status' => 1]);
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
