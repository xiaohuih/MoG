<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use App\Facades\Game;
use GuzzleHttp\Client;

class ZoneController extends Controller
{
    protected static $cmd = 10001;

    public function index()
    {
        $client = new Client();
        $res = $client->request('GET', config('game.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd
            ]
        ]);
        $zones = json_decode($res->getBody(), true);
        sort($zones);
        
        $result = ['items' => [], 'selected' => 0];
        if ($zones) {
            $result['items'] = $zones;
        }
        $zoneSelected = Game::getZone();
        if ($zoneSelected != 0 && !in_array($zoneSelected, $zones)) {
            Game::setZone(0);
        }
        $result["selected"] = Game::getZone();
        return $result;
    }

    public function zones()
    {
        $client = new Client();
        $res = $client->request('GET', config('game.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd
            ]
        ]);
        $zones = json_decode($res->getBody(), true);
        sort($zones);

        $result = [];
        array_push($result, [
            "id"=> '*',
            "text"=> '所有区'
        ]);
        foreach ($zones as $zone) {
            array_push($result, [
                "id"=> $zone,
                "text"=> sprintf("%d%s", $zone, trans('game.zone'))
            ]);
        }
        return $result;
    }

    public function select()
    {
        Game::setZone((int)Input::get('_zone', 0));
    }
}
