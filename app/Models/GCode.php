<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use App\Exports\BaseExport;
use Maatwebsite\Excel\Facades\Excel;

class GCode extends Model
{
    // 礼包码 = 版本号 + 随机码 + 签名
    // 加密字符表
    const R = "vPh7zZwA2LyU4bGq5tcVfMxJi6XaSK9CNpWjYTHQ8REnmu3BrdgeDkFs";
    // 随机码字符长度
    const kRandCodeLength = 4;
    // 签名字符长度
    const kSignatureLenth = 6;
    // 版本长度
    const kVersionLenth = 2;

    protected static $cmd = 10002;
    /**
     * @var array
     */
    protected $fillable = [
        'status',
    ];
    /**
     * 类型
     */
    public static $types = [
        0 => 'nolimit',     // 无限制 
        1 => 'once',        // 仅可领取一次
    ];

    /**
     * 关联到模型的数据表
     ** @var string
     */
    protected $table = 'gcodes';
    /**
     * 列转换
     ** @var string
     */
    protected $casts = [
        'mail' => 'json',
    ];

    /**
     * 真实ID
     */
    public static function realId($id)
    {
        return $id + 1000;
    }

    /**
     * 加密
     */
    static function baseEncode($num, $length = 0)
    {
        $baseLength = strlen(self::R);
        $str = "";
        while ($num > 0 || strlen($str) < $length) {
            $i = $num % $baseLength;
            $str = $str . substr(self::R, $i, 1);
            $num = (int)(($num - 1) / $baseLength);
        }
        return $str;
    }
    /**
     * 生成随机码
     */
    static function randCode($length, $codes)
    {
        $baseLength = strlen(self::R);
        $code = "";
        $i = 0;
        while (++$i < 1000) {
            for ($i = 0; $i < $length; $i++) {
                $code = $code . substr(self::R, mt_rand(0, $baseLength-1), 1);
            }
            if (!in_array($code, $codes, true)) {
                break;
            }
        }
        array_push($codes, $code);
        return $code;
    }
    
    /**
     * 发布
     */
    public function publish()
    {
        $cmd = 'PUBLISH_GCODE';
        $params = [
            'id' => self::realId((int)$this->id),
            'type' => (int)$this->type,
            'platform' => (int)$this->platform,
            'group' => (int)$this->group,
            'key' => $this->key,
            'mail' => $this->mail,
            'begintime' => isset($this->begintime) ? strtotime($this->begintime):0,
            'endtime' => isset($this->endtime) ? strtotime($this->endtime):0,
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.gm.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => json_encode([$cmd, $params])
            ]
        ]);
        $data = json_decode($res->getBody(), true);
        // 更新状态
        if (true == $data['status']) {
            $this->update(['status' => 1]);
        }

        return $data;
    }

    /**
     * 取消发布
     */
    public function unpublish()
    {
        $cmd = 'UNPUBLISH_GCODE';
        $params = [
            'id' => self::realId((int)$this->id),
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.gm.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => json_encode([$cmd, $params])
            ]
        ]);
        $data = json_decode($res->getBody(), true);
        // 更新状态
        if (true == $data['status']) {
            $this->update(['status' => 0]);
        }

        return $data;
    }

    /**
     * 生成码
     */
    public function generate()
    {
        $version = $this->baseEncode(self::realId((int)$this->id), self::kVersionLenth);
        $codes = [];
        $codesDict = [];
        for ($i = 0; $i < $this->count; $i++) {
            $rand = $this->randCode(self::kRandCodeLength, $codesDict);
            $sign = $this->baseEncode(crc32($version.$rand.$this->key) & 0xFFFFFFFF, self::kSignatureLenth);
            $code = $version.$rand.$sign;
            
            array_push($codes, [$code]);
        }
        return $codes;
    }

    /**
     * 生成码
     */
    public function export()
    {
        return Excel::download(new BaseExport(collect($this->generate())), $this->name.'.xlsx');
    }
}
