<?php

namespace App\Models\Player;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\Paginator;
use GuzzleHttp\Client;
use App\Facades\Game;

class Rank extends Model
{
    protected static $cmd = 10003;
    /**
     * Rank type
     */
    protected $type = 0;
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

        if ($this->type != 0) {
            $cmd = 'GET_PLAYER_RANK';
            $params = [
                'type' => $this->type,
                'perPage' => $perPage,
                'currentPage' => $currentPage
            ];
            $client = new Client();
            $res = $client->request('GET', config('game.url'), [
                'timeout' => 10,
                'query' => [
                    'CmdId' => static::$cmd,
                    'ZoneId' => Game::getZone(),
                    'params' => json_encode([$cmd, $params])
                ]
            ]);
            $data = json_decode($res->getBody(), true);
        } else {
            $data = [
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
        $this->type = (int)$operator;
        return $this;
    }
}
