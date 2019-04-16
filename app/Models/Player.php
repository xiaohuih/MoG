<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\Paginator;
use GuzzleHttp\Client;

class Player extends Model
{
    protected static $cmd = 10003;

    /**
     * Paginate the given query.
     *
     * @param  int  $perPage
     * @param  array  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     *
     * @throws \InvalidArgumentException
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $currentPage = $page ?: Paginator::resolveCurrentPage($pageName);
        $perPage = $perPage ?: $this->getPerPage();

        $params = [
            'funId' => 'GET_PLAYERS',
            'perPage' => $perPage,
            'currentPage' => $currentPage
        ];
        if (Request::get('id')) {
            $params['id'] = Request::get('id');
        }
        if (Request::get('name')) {
            $params['name'] = Request::get('name');
        }
        $client = new Client();
        $res = $client->request('GET', config('game.gm.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => json_encode($params)
            ]
        ]);
        $data = json_decode($res->getBody(), true);

        $items = static::hydrate($data['list']);
        $pagination = $data['pagination'];
        return new LengthAwarePaginator($items,$pagination['total'], $pagination['perPage'], $pagination['currentPage'],[
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName
        ]);
    }

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
            'id' => $id
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.gm.url'), [
            'connect_timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
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

    public static function with($relations)
    {
        return new static;
    }
}
