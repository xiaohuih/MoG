<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Mail extends Model
{
    protected static $cmd = 10002;

    // 普通邮件
    const TYPE_NORMAL   = 1;
    // 全服邮件
    const TYPE_GLBOAL   = 2;

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
            'type' => (int)$this->type,
            'title' => $this->title,
            'content' => $this->content,
            'attachments' => $this->attachments ?: "",
            'zones' => $this->zones,
            'receivers' => $this->receivers ?: "",
            'sendtime' => isset($this->sendtime) ? strtotime($this->sendtime):0,
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
            $this->status = 1;
            $this->save();
        }

        return $data;
    }
    
    /**
     * 撤回邮件
     * @param int $id
     */
    public static function revoke($id)
    {
        $cmd = 'REVOKE_MAIL';
        $params = [
            'id' => (int)$id,
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
            self::where('id', '=', $id)->update(['status' => 0]);
        }

        return $data;
    }
}