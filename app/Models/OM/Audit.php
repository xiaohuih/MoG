<?php

namespace App\Models\OM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Audit extends Model
{
    public static $file = 'package.json';

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
        // \Log::debug($data);

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
        $audits = json_decode(Storage::disk('game')->get(self::$file), true);
       
        $audit = null;
        for ($i = 0, $c = count($audits); $i < $c; ++$i) {
            if ($audits[$i]['id'] == (int)$id) {
                $audit = &$audits[$i];
                break;
            }
        }
        
        if (!isset($audit)){
            return false;
        }
        $audit['packages'] = implode(',', $audit['packages']);
        return static::newFromBuilder($audit);
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
        $audits = json_decode(Storage::disk('game')->get(self::$file), true);

       
        $audit = null;
        for ($i = 0, $c = count($audits); $i < $c; ++$i) {
            if ($audits[$i]['id'] == (int)$this->id) {
                $audit = &$audits[$i];
                // \Log::debug($audit);
                break;
            }
        }

        if (!isset($audit)){
            return false;
        }
        
        foreach ($this->getAttributes() as $key => $value) {
            $type = gettype($audit[$key]);
            if ($type == 'array') {
                $audit[$key] = explode(",", $value);
            } else {
                $audit[$key] = $value;
            }
            settype($audit[$key], $type); 
        }

        Storage::disk('game')->put(self::$file, json_encode($audits, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        // \Log::debug($audits);
        return true;
    }

    public static function setProperty($type, $name, $value)
    {
        $audits = json_decode(Storage::disk('game')->get(self::$file), true);
       
        $audit = null;
        for ($i = 0, $c = count($audits); $i < $c; ++$i) {
            if ($audits[$i]['id'] == (int)$type) {
                $audit = &$audits[$i];
                break;
            }
        }
        if (!isset($audit)){
            return false;
        }
        $vartype = gettype($audit[$name]);
        $audit[$name] = $value;
        settype($audit[$name], $vartype);

        Storage::disk('game')->put(self::$file, json_encode($audits, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        return true;
    }
}
