<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Script extends Model
{
    protected static $cmd = 10002;

    public function setZonesAttribute($value)
    {
        $this->attributes['zones'] = implode(';', $value);
    }

    /**
     * 发送公告
     */
    public function perform()
    {
        $cmd = 'PERFORM_SCRIPT';
        $params = [
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
