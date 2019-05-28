<?php

namespace App\Models\OM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Server extends Model
{
    public static $file = 'files/server_list.json';
    /**
     * 状态
     */
    public static $states = [
        1 => ['name' => 'new', 'style' => 'green'],        // 新服
        2 => ['name' => 'burst', 'style' => 'red'],      // 爆满
        3 => ['name' => 'hot', 'style' => 'orange'],        // 火爆
        4 => ['name' => 'maintain', 'style' => 'grey'],   // 维护
        5 => ['name' => 'hide', 'style' => 'black'],       // 隐藏
    ];
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

        $data = [
            'list' => [],
            'pagination' => ['total' => 0, 'perPage' => $perPage , 'currentPage' => $currentPage],
        ];
        try {
            $contents = json_decode(Storage::disk('admin')->get(self::$file), true);
            $servers = $contents['servers'];
            $data['list'] = $servers;
            $data['pagination']['total'] = count($servers);
        } catch (\Exception $exception) {
            throw $exception;
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

    public static function modify($id, $name, $value)
    {
        $contents = json_decode(Storage::disk('admin')->get(self::$file), true);
        $servers = &$contents['servers'];

        $server = null;
        for ($i = 0, $c = count($servers); $i < $c; ++$i) {
            if ($servers[$i]['id'] == (int)$id) {
                $server = &$servers[$i];
                break;
            }
        }
        if (!isset($server)){
            return false;
        }
        $type = gettype($server[$name]);
        $server[$name] = $value;
        settype($server[$name], $type);

        Storage::disk('admin')->put(self::$file, json_encode($contents, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));

        return 200;
    }
}
