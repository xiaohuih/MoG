<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Pagination\Paginator;
use GuzzleHttp\Client;

class GCode extends Model
{
	// 增加
	const OPT_ADD = 1;
	// 删除
    const OPT_DELETE = 2; 
	// 获取
    const OPT_CHECK = 3;

    // 无限制
    const TYPE_NOLIMIT  = 0;
    // 仅可领取一次
    const TYPE_ONCE     = 1;
    
    protected static $cmd = 10004;

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
        $params = json_encode([
            "funId"     => "GIFT_CODE_OPT",
            "code_id"   => trim(Request::get('id') ?: ''),
            "opt"       => self::OPT_CHECK
        ]);
        $client = new Client();
        $res = $client->request('GET', config('game.account.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => $params
            ]
        ]);
        $data = json_decode($res->getBody(), true);

        $total = 0;
        $results = array();
        if (isset($data) && $data['res'] == 0) {
            $results = $data['codes'];
        }
        $total = count($results);

        return new LengthAwarePaginator(static::hydrate($results), $total, $perPage, $page,[
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
        $params = json_encode([
            "funId"     => "GIFT_CODE_OPT",
            "code_id"   => $id,
            "opt"       => self::OPT_CHECK
        ]);
        $client = new Client();
        $res = $client->request('GET', config('game.account.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => $params
            ]
        ]);
        $data = json_decode($res->getBody(), true);
        
        return static::newFromBuilder($data['codes'][0]);
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
    
    /**
     * Insert the given attributes and set the ID on the model.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  array  $attributes
     * @return void
     */
    protected function insertAndSetId(Builder $query, $attributes)
    {
        $params = [
            "funId"     => "GIFT_CODE_OPT",
            "opt"       => self::OPT_ADD
        ];
        $params = array_merge($params, $attributes);
        $params['mail'] = 0;
        $params['begintime'] = isset($params['begintime']) ? strtotime($params['begintime']) : 0;
        $params['endtime'] = isset($params['endtime']) ? strtotime($params['endtime']) : 0;
        unset($params['created_at'], $params['updated_at']);
        $params = json_encode($params);
        
        $client = new Client();
        $res = $client->request('GET', config('game.account.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => $params
            ]
        ]);
        $data = json_decode($res->getBody(), true);
        if ($data['res'] != 0) {
            admin_toastr($data['reason'], 'error');
        } else {
            $id = $data['id'];
            $this->setAttribute($this->getKeyName(), $id);
        }
    }

    /**
     * Perform the actual delete query on this model instance.
     *
     * @return void
     */
    protected function performDeleteOnModel()
    {
        $params = json_encode([
            "funId"     => "GIFT_CODE_OPT",
            "code_id"   => $this->getKeyForSaveQuery(),
            "opt"       => self::OPT_DELETE
        ]);
        $client = new Client();
        $res = $client->request('GET', config('game.account.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => $params
            ]
        ]);

        $this->exists = false;
    }
}
