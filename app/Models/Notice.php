<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Notice extends Model
{
    protected static $cmd = 10002;
    /**
     * @var array
     */
    protected $fillable = [
        'status',
    ];

    public function setZonesAttribute($value)
    {
        $this->attributes['zones'] = implode(';', $value);
    }

    /**
     * 发送公告
     */
    public function send()
    {
        $cmd = 'SEND_NOTICE';
        $params = [
            'id' => (int)$this->id,
            'content' => $this->content,
            'zones' => $this->zones,
            'starttime' => isset($this->starttime) ? strtotime($this->starttime):0,
            'endtime' => isset($this->endtime) ? strtotime($this->endtime):0,
            'interval' => $this->interval ?: 0,
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => json_encode([$cmd, $params])
            ]
        ]);
        $data = json_decode($res->getBody(), true);
        // 更新状态
        if (true == $data['status']) {
            $this->update(['status' => 1]);
        }

        return $data;
    }
    
    /**
     * 撤回公告
     * @param int $id
     */
    public static function revoke($id)
    {
        $cmd = 'REVOKE_NOTICE';
        $params = [
            'id' => (int)$id,
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => json_encode([$cmd, $params])
            ]
        ]);
        $data = json_decode($res->getBody(), true);
        // 更新状态
        if (true == $data['status']) {
            self::where('id', '=', $id)->update(['status' => 0]);
        }

        return $data;
    }
}
