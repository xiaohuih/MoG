<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use App\Facades\Game;

class Player extends Model
{
    protected static $cmd = 10003;

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static|static[]
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    protected function findOrFail($id, $columns = ['*'])
    {
        $params = [
            'funId' => 'GET_PLAYER_DETAIL',
            'id' => (int)$id
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.gm.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'ZoneId' => Game::getZone(),
                'params' => json_encode($params)
            ]
        ]);
        $data = json_decode($res->getBody(), true);
        
        return static::newFromBuilder($data);
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param  string|array|\Closure  $column
     * @param  mixed   $operator
     * @param  mixed   $value
     * @param  string  $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        return $this;
    }

    /**
     * 执行操作
     *
     * @param int $id
     * @param string $action
     * @param string $value
     */
    public static function perform($id, $action, $value)
    {
        $params = [
            'funId' => 'PERFORM_PLAYER_ACTION',
            'id' => (int)$id,
            'action' => $action,
            'value' => $value
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.gm.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'ZoneId' => Game::getZone(),
                'params' => json_encode($params)
            ]
        ]);
        $data = json_decode($res->getBody(), true);
        // if ($data['status'] == true) {
        //     $data['message'] => trans('admin.update_succeeded')；
        // }
        return $data;
        
        // if ($this->form()->destroy($id)) {
        //     $data = [
        //         'status'  => true,
        //         'message' => trans('admin.delete_succeeded'),
        //     ];
        // } else {
        //     $data = [
        //         'status'  => false,
        //         'message' => trans('admin.delete_failed'),
        //     ];
        // }
    }
}
