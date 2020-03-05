<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use App\Facades\Game;

class RemoveItem extends Model
{
    protected static $server_cmd = 10003; // 发送到session
    /**
     * @var array
     */
    protected $fillable = [
        'status',
    ];

    /**
     * 发送删除物品请求
     */
    public function send()
    {
        $cmd = 'REMOVE_PLAYER_ITEM';
        $params = [
            'id' => (int)$this->player,
            'itemid' => (int)$this->itemid,
            'configid' => (int)$this->configid,
            'count' => (int)$this->count,
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$server_cmd,
                'ZoneId' => (int)$this->zone,
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
}
