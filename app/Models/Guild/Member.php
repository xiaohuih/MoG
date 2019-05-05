<?php

namespace App\Models\Guild;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\Paginator;
use GuzzleHttp\Client;
use App\Facades\Game;

class Member extends Model
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
            'funId' => 'GET_GUILD_MEMBERS',
            'perPage' => $perPage,
            'currentPage' => $currentPage
        ];
        if (Request::get('id')) {
            $params['id'] = (int)Request::get('id');
        }
        if (Request::get('name')) {
            $params['name'] = Request::get('name');
        }
        if (isset($params['id']) || isset($params['name'])) {
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
            $id = $data['id'];
            $data['list'] = collect($data['list'])->map(function($item) use ($id) {
                $item['guild'] = $id;
                return $item;
            })->all();
        } else {
            $data = [
                'id' => 0,
                'list' => [],
                'pagination' => ['total' => 0, 'perPage' => $perPage , 'currentPage' => $currentPage],
            ];
        }

        $items = static::hydrate($data['list']);
        $pagination = $data['pagination'];
        return new LengthAwarePaginator($items, $pagination['total'], $pagination['perPage'], $pagination['currentPage'],[
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName
        ]);
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
}
