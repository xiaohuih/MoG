<?php

namespace App\Models\OM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Notice extends Model
{
    public static $file = 'notice.json';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'type';
    /**
     * 类型
     */
    public static $types = [
        1 => ['name' => 'maintain', 'switch' => true],      // 维护公告
        2 => ['name' => 'update', 'switch' => false],       // 更新公告
        3 => ['name' => 'operation', 'switch' => true],     // 运营公告
        4 => ['name' => 'mandatory', 'switch' => false],    // 强更公告
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
            $contents = Storage::disk('game')->get(self::$file);
            $data['list'] = json_decode($contents, true);
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
        $notices = json_decode(Storage::disk('game')->get(self::$file), true);
       
        $notice = null;
        for ($i = 0, $c = count($notices); $i < $c; ++$i) {
            if ($notices[$i]['type'] == (int)$id) {
                $notice = &$notices[$i];
                break;
            }
        }
        if (!isset($notice)){
            return false;
        }
        return static::newFromBuilder($notice);
    }

    protected function findToShow($id)
    {
        return $this->findOrFail($id);
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
        $notices = json_decode(Storage::disk('game')->get(self::$file), true);
       
        $notice = null;
        for ($i = 0, $c = count($notices); $i < $c; ++$i) {
            if ($notices[$i]['type'] == (int)$this->type) {
                $notice = &$notices[$i];
                break;
            }
        }
        if (!isset($notice)){
            return false;
        }
        foreach ($this->getAttributes() as $key => $value) {
            $type = gettype($notice[$key]);
            $notice[$key] = $value;
            settype($notice[$key], $type);
        }

        Storage::disk('game')->put(self::$file, json_encode($notices, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        return true;
    }

    public static function setProperty($type, $name, $value)
    {
        $notices = json_decode(Storage::disk('game')->get(self::$file), true);
       
        $notice = null;
        for ($i = 0, $c = count($notices); $i < $c; ++$i) {
            if ($notices[$i]['type'] == (int)$type) {
                $notice = &$notices[$i];
                break;
            }
        }
        if (!isset($notice)){
            return false;
        }
        $vartype = gettype($notice[$name]);
        $notice[$name] = $value;
        settype($notice[$name], $vartype);

        Storage::disk('game')->put(self::$file, json_encode($notices, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        return true;
    }
}
