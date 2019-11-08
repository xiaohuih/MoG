<?php

namespace App\Models\OM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Products extends Model
{
    public static $file = 'package_config.json';
    /**
     * The primary key for the model.
     *
     * @var string
     */
    // protected $primaryKey = 'ids';
    /**
     * 类型
     */
    // public static $types = [
    //     1 => ['name' => 'product_ios', 'switch' => true],              // 提审服
    //     100 => ['name' => 'product_ios_100', 'switch' => true],         // 提审服100
    //     200 => ['name' => 'product_ios_200', 'switch' => true],         // 提审服200
    // ];
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

        // for ($i = 0; $i < count($data['list']); ++$i) {
        //     $data['list'][$i]['id'] = $i+1;
        // }
        $items = static::hydrate($data['list']);
        // \Log::debug($items);
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
        $products = json_decode(Storage::disk('game')->get(self::$file), true);       
        $product = null;
        for ($i = 0, $c = count($products); $i < $c; ++$i) {
            if ($products[$i]['id'] == (int)$id) {
                $product = &$products[$i];
                break;
            }
        }

        if (!isset($product)){
            return false;
        }
        $product['ids'] = implode(',', $product['ids']);
        return static::newFromBuilder($product);
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
        $products = json_decode(Storage::disk('game')->get(self::$file), true);
        $product = null;
        for ($i = 0, $c = count($products); $i < $c; ++$i) {
            if ($products[$i]['id'] == (int)$this->id) {
                $product = &$products[$i];
                break;
            }
        }

        if (!isset($product)){
            return false;
        }
        $product['file'] = "";
        foreach ($this->getAttributes() as $key => $value) {
            $type = gettype($product[$key]);
            if ($type == "array") {
                $product[$key] = explode(",", $value);
            }else{
                $product[$key] = $value;
            }
            settype($product[$key], $type); 
        }
        try {
            // 拷贝商品ID文件到目录服
            $disk = Storage::disk('local');
            $file = 'game/pay' . DIRECTORY_SEPARATOR . 'product_'.$this->id .'.json';
            if ($disk->exists($file)) {
                $disk->delete($file);
            }
            $disk->copy('public/'.$this->file, $file);
        } catch (\Exception $e) {
            // return [
            //     'status'    => false,
            //     'message'   => 'failed',
            // ];
        }
        
        unset($product['file']);


        Storage::disk('game')->put(self::$file, json_encode($products, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        return true;
    }

    public static function setProperty($type, $name, $value)
    {
        $products = json_decode(Storage::disk('game')->get(self::$file), true);
       
        $product = null;
        for ($i = 0, $c = count($products); $i < $c; ++$i) {
            if ($products[$i]['id'] == (int)$id) {
                $product = &$products[$i];
                break;
            }
        }
        if (!isset($product)){
            return false;
        }
        $vartype = gettype($product[$name]);
        $product[$name] = $value;
        settype($product[$name], $vartype);

        Storage::disk('game')->put(self::$file, json_encode($products, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT));
        return true;
    }
}
