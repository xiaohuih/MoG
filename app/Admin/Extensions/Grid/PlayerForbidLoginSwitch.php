<?php

namespace App\Admin\Extensions\Grid;

use Encore\Admin\Admin;

class PlayerForbidLoginSwitch
{
    /**
     * @var mixed
     */
    protected $key;
    /**
     * @var mixed
     */
    protected $value;
    /**
     * @var string
     */
    protected $resource;

    protected $states = [
        'on'  => ['value' => 1, 'text' => 'ON', 'color' => 'primary'],
        'off' => ['value' => 0, 'text' => 'OFF', 'color' => 'default'],
    ];

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
    
    protected function updateStates($states)
    {
        foreach (array_dot($states) as $key => $state) {
            array_set($this->states, $key, $state);
        }
    }

    protected function getElementClassName()
    {
        return 'grid-switch-'.str_replace('.', '-', $this->column->getName());
    }
    
    protected function script()
    {
        $this->updateStates($states);

        $name = $this->column->getName();

        $keys = collect(explode('.', $name));
        if ($keys->isEmpty()) {
            $key = $name;
        } else {
            $key = $keys->shift().$keys->reduce(function ($carry, $val) {
                return $carry."[$val]";
            });
        }

        return <<<EOT

$('.{$this->getElementClassName()}').bootstrapSwitch({
    size:'mini',
    onText: '{$this->states['on']['text']}',
    offText: '{$this->states['off']['text']}',
    onColor: '{$this->states['on']['color']}',
    offColor: '{$this->states['off']['color']}',
    onSwitchChange: function(event, state){

        $(this).val(state ? 'on' : 'off');

        var pk = $(this).data('key');
        var value = $(this).val();

        $.ajax({
            url: "{$this->getResource()}/" + pk,
            type: "POST",
            data: {
                "$key": value,
                _token: LA.token,
                _method: 'PUT'
            },
            success: function (data) {
                toastr.success(data.message);
            }
        });
    }
});

EOT;
    }

    protected function render()
    {
        Admin::script($this->script());

        $key = $this->row->{$this->grid->getKeyName()};

        $checked = $this->states['on']['value'] == $this->value ? 'checked' : '';

        return <<<EOT
        <input type="checkbox" class="{$this->getElementClassName()}" $checked data-key="$key" />
EOT;
    }

    public function __toString()
    {
        return $this->render();
    }
}
