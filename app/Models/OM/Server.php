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
    public static $file = 'server_list.json';
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
            $contents = json_decode(Storage::disk('game')->get(self::$file), true);
            $games = $contents['games'];
            $data['list'] = array_slice($games, ($currentPage-1)*$perPage, $perPage);
            $data['pagination']['total'] = count($games);
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
     * Find a model by its primary key or throw an exception.
     *
     * @param  mixed  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|static|static[]
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id)
    {
        $games = json_decode(Storage::disk('game')->get(self::$file), true)['games'];
       
        $game = null;
        for ($i = 0, $c = count($games); $i < $c; ++$i) {
            if ($games[$i]['id'] == (int)$id) {
                $game = &$games[$i];
                break;
            }
        }
        if (!isset($game)){
            return false;
        }
        return static::newFromBuilder($game);
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
     * Set the relationships that should be eager loaded.
     *
     * @param mixed $relations
     *
     * @return $this|Model
     */
    public static function with($relations)
    {
        return new static;
    }

    /**
     * Save the model.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        $contents = json_decode(Storage::disk('game')->get(self::$file), true);
        $games = &$contents['games'];
       
        $game = null;
        for ($i = 0, $c = count($games); $i < $c; ++$i) {
            if ($games[$i]['id'] == (int)$this->id) {
                $game = &$games[$i];
                break;
            }
        }
        if (!isset($game)){
            return false;
        }
        foreach ($this->getAttributes() as $key => $value) {
            $type = gettype($game[$key]);
            $game[$key] = $value;
            settype($game[$key], $type);
        }

        Storage::disk('game')->put(self::$file, json_encode($contents, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        return true;
    }

    public static function modify($id, $name, $value)
    {
        $contents = json_decode(Storage::disk('game')->get(self::$file), true);
        $games = &$contents['games'];

        $game = null;
        for ($i = 0, $c = count($games); $i < $c; ++$i) {
            if ($games[$i]['id'] == (int)$id) {
                $game = &$games[$i];
                break;
            }
        }
        if (!isset($game)){
            return false;
        }
        $type = gettype($game[$name]);
        $game[$name] = $value;
        settype($game[$name], $type);

        Storage::disk('game')->put(self::$file, json_encode($contents, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        return 200;
    }

    public static function modifyMaxVersion($version)
    {
        $contents = json_decode(Storage::disk('game')->get(self::$file), true);
        $contents['version_max'] = $version;

        Storage::disk('game')->put(self::$file, json_encode($contents, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        return 200;
    }

    public static function list()
    {
        $contents = json_decode(Storage::disk('game')->get(self::$file), true);
        return $contents['games'];
    }
}
