<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Script extends Model
{
    protected static $cmd = 10002;
    /**
     * 服务器类型
     */
    public static $servers = [
        1 => "客户端",  // 客户端 
        2 => '场景服',  // 场景服
        3 => '会话服',  // 会话服
    ];


    public function setZonesAttribute($value)
    {
        $this->attributes['zones'] = implode(';', $value);
    }

    /**
     * 发送公告
     */
    public function perform()
    {
        $cmd = 'PERFORM_SCRIPTCMD';
        $params = [
            'type' => $this->server,
            'content' => $this->content,
            'zones' => $this->zones,
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.gm.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => json_encode([$cmd, $params])
            ]
        ]);
        $data = json_decode($res->getBody(), true);

        return $data;
    }
}
