<?php

namespace App\Models\Player;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\Paginator;
use GuzzleHttp\Client;
use App\Facades\Game;

class Feedback extends Model
{
    protected static $server_cmd = 10003;
    
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

        $cmd = 'QUERY_FEEDBACK';
        $params = [
            'begin' => ($currentPage - 1) * $perPage, // 从0开始计数
            'limit' => $perPage
        ];
       
        $client = new Client();
        $res = $client->request('GET', config('game.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$server_cmd,
                'ZoneId' => Game::getZone(),
                'params' => json_encode([$cmd, $params])
            ]
        ]);
        $data = json_decode($res->getBody(), true);

        $data['feedbacks'] = collect($data['feedbacks'])->map(function($item) {
            return $item;
        })->all();
      
        $items = static::hydrate($data['feedbacks']);

        return new LengthAwarePaginator($items, $data['total'], $perPage, $currentPage, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName
        ]);
    }
}
