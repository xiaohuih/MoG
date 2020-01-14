<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Mail extends Model
{
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
        1 => 'normal',     // 普通邮件 
        2 => 'global',     // 全服邮件
    ];

    public function setZonesAttribute($value)
    {
        $this->attributes['zones'] = implode(';', $value);
    }

    /**
     * 发送邮件
     */
    public function send()
    {
        $cmd = 'SEND_MAIL';
        $params = [
            'id' => (int)$this->id,
            'receivers' => $this->receivers,
            'title' => $this->title,
            'content' => $this->content,
            'attachments' => $this->attachments ?: "",
            'zones' => $this->zones,
            'sendtime' => isset($this->sendtime) ? strtotime($this->sendtime):0,
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.url'), [
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
     * 撤回邮件
     * @param int $id
     */
    public function revoke()
    {
        $cmd = 'REVOKE_MAIL';
        $params = [
            'id' => (int)$this->id,
            'receivers' => $this->receivers,
            'zones' => $this->zones,
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'params' => json_encode([$cmd, $params])
            ]
        ]);
        $data = json_decode($res->getBody(), true);
        // 更新状态 为已撤回
        if (true == $data['status']) {
            $this->update(['status' => 2]);
        }

        return $data;
    }
}
