<?php

namespace App\Widgets;

use App\Facades\Game;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Widgets\Widget;
use Illuminate\Contracts\Support\Renderable;

class ZoneSelect extends Widget implements Renderable
{
    /**
     * The url to get zones.
     */
    const URL_ZONES = '/admin/zone';
    /**
     * The url to change current one.
     */
    const URL_SELECTZONE = '/admin/zone/select';
    /**
     * @var string
     */
    protected $className;

    /**
     * Alert constructor.
     *
     */
    public function __construct()
    {
    }

    /**
     * Get select class name.
     *
     * @return string
     */
    protected function getElementClassName()
    {
        if (!$this->className) {
            $this->className = uniqid().'-select';
        }

        return $this->className;
    }

    /**
     * Set up script for export button.
     */
    protected function setUpScripts()
    {
        $urlZones = static::URL_ZONES;
        $urlSelectZone = static::URL_SELECTZONE;
        $language = config('app.locale');
        $placeholder = trans('game.select_zone');
        $zonePost = trans('game.zone');

        $script = <<<SCRIPT
function initSelector() {  
    $.ajax({
        dataType: 'json', 
        method: 'GET', 
        url: '$urlZones',
    }).done( function(data) {    
        $(".{$this->getElementClassName()}").select2({
            data: $.map(data.items, function(id) {
                return {id: id, text: id + '$zonePost'};
            }),
            allowClear: true,
            placeholder: '{$placeholder}',
            language: '{$language}',
            minimumResultsForSearch: Infinity,
        });
        if (data.selected != 0) {
            $('.{$this->getElementClassName()}').val(data.selected).trigger('change');
        } else {
            $('.{$this->getElementClassName()}').val(null).trigger('change');
        }
    });
}

function selectZone(zone) {
    $.post('{$urlSelectZone}', {
        _token: LA.token,
        _zone: zone
    },
    function(){
        $.pjax.reload('#pjax-container');
    });
}

$('.{$this->getElementClassName()}').on('select2:select', function (e) {
    selectZone(e.params.data.id);
});
$('.{$this->getElementClassName()}').on('select2:unselect', function (e) {
    selectZone(0);
});

initSelector();

SCRIPT;

        Admin::script($script);
    }
    
    /**
    * {@inheritdoc}
    */
   public function render()
   {
       $this->setUpScripts();

       return <<<EOT
<div class="sidebar-form">
    <select class='form-control {$this->getElementClassName()}'>
    </select>
</div>
EOT;
   }
}
