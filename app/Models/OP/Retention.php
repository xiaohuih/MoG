<?php

namespace App\Models\OP;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class Retention extends Model
{
    /**
     * Get Type Name
     *
     * @return string
     */
    // public function getTypeName()
    // {
    //     return '_doc';
    // }
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
        $client = \Elasticsearch\ClientBuilder::fromConfig(config('database.elasticsearch'), true);
        $searchParams = [
            'index' => 'filebeat-*',
            'size' => 0,
            'body' => '{
                "aggs" : {
                    "1": {
                        "date_histogram": {
                            "field": "created_at",
                            "format": "yyyy-MM-dd",
                            "calendar_interval": "1d"
                        },
                        "aggs": {
                    "all" : {
                      "filter" : { 
                        "term": { "days": "0" } 
                      },
                      "aggs" : {
                        "unique_count" : {
                          "cardinality" : {
                            "field" : "player.id.keyword"
                          }
                        }
                      }
                    },
                    "2nd" : {
                      "filter" : { 
                        "term": { "days": "1" } 
                      },
                      "aggs" : {
                        "unique_count" : {
                          "cardinality" : {
                            "field" : "player.id.keyword"
                          }
                        }
                      }
                    },
                    "2nd_retation": {
                      "bucket_script": {
                        "buckets_path": {
                          "value1": "all>unique_count",
                          "value2": "2nd>unique_count"
                        },
                        "script": "params.value2 / params.value1 * 100"
                      }
                    },
                    "3rd" : {
                      "filter" : { 
                        "term": { "days": "2" } 
                      },
                      "aggs" : {
                        "unique_count" : {
                          "cardinality" : {
                            "field" : "player.id.keyword"
                          }
                        }
                      }
                    },
                    "3rd_retation": {
                      "bucket_script": {
                        "buckets_path": {
                          "value1": "all>unique_count",
                          "value2": "3rd>unique_count"
                        },
                        "script": "params.value2 / params.value1 * 100"
                      }
                    }
                        }
                    }
              }
            }'
        ];
        try {
            $response = \Elastic::search($searchParams);
        } catch (\Elasticsearch\Common\Exceptions\Curl\CouldNotConnectToHost $e) {
            $previous = $e->getPrevious();
            if ($previous instanceof \Elasticsearch\Common\Exceptions\MaxRetriesException) {
                echo "Max retries!";
            }
        }
        \Log::debug($response);
        $aggregations = $response['aggregations'];

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
}