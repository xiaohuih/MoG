<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use App\Facades\Game;

class Guild extends Model
{
    protected static $cmd = 10003;

    /**
     * 解散
     *
     * @param int $id
     * @param string $action
     * @param string $value
     */
    public static function disband($id)
    {
        $cmd = 'DISBAND_GUILD';
        $params = [
            'id' => (int)$id
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.gm.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'ZoneId' => Game::getZone(),
                'params' => json_encode([$cmd, $params])
            ]
        ]);
        $data = json_decode($res->getBody(), true);

        return $data;
    }

    /**
     * 删除成员
     *
     * @param int $id
     * @param int $member_id
     */
    public static function removeMember($id, $member_id)
    {
        $cmd = 'REMOVE_GUILD_MEMBER';
        $params = [
            'id' => (int)$id,
            'member_id' => (int)$member_id
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.gm.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'ZoneId' => Game::getZone(),
                'params' => json_encode([$cmd, $params])
            ]
        ]);
        $data = json_decode($res->getBody(), true);

        return $data;
    }
}
