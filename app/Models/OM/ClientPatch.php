<?php

namespace App\Models\OM;

use App\Models\Script;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class ClientPatch extends Model
{
    protected static $cmd = 10002;
    /**
     * 服务器类型
     */
    public static $servers = [
        2 => 'scene',   // 场景服
        3 => 'session', // 会话服
    ];

    public function setZonesAttribute($value)
    {
        $this->attributes['zones'] = implode(';', $value);
    }
    
    /**
     * 执行
     */
    public function perform()
    {
        try {
            // 拷贝补丁到SALT
            $disk = Storage::disk('local');
            $file = 'game/assets/' . DIRECTORY_SEPARATOR . 'patch_'.$this->platform. "_" .$this->version.'.zip';
            if ($disk->exists($file)) {
                $disk->delete($file);
            }
            $disk->copy('aetherupload/'.$this->file, $file);
            // 上传补丁
            Artisan::call("game:client-patch", ['version' => $this->version, 'platform' => $this->platform]);
            // 重载补丁
            // Script::performScript($this->script, 2, $this->zones);
            // Script::performScript($this->script, 3, $this->zones);
        } catch (\Exception $e) {
            return [
                'status'    => false,
                'message'   => 'failed',
            ];
        }
        return [
            'status'    => true,
            'message'   => 'success',
        ];
    }
}
