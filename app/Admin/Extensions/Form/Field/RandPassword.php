<?php

namespace App\Admin\Extensions\Form\Field;

use Encore\Admin\Form\Field\Text;

class RandPassword extends Text
{
    protected $length = 6;
    /**
     * Set the length of password.
     *
     * @return string
     */
    public function length($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * Script for this tool.
     *
     * @return string
     */
    protected function script()
    {
        $message = trans('admin.refresh_succeeded');

        return <<<EOT

function randPassword(size)
{
    var seed = new Array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','P','Q','R','S','T','U','V','W','X','Y','Z',
    'a','b','c','d','e','f','g','h','i','j','k','m','n','p','Q','r','s','t','u','v','w','x','y','z',
    '2','3','4','5','6','7','8','9'
    );
    length = seed.length;
    var password = '';
    for (i = 0; i < size; i++) {
        j = Math.floor(Math.random()*length);
        password += seed[j];
    }
    return password;
}

$('.{$this->id}-refresh').on('click', function() {
    var password = randPassword({$this->length});
    $(".{$this->id}").val(password);
});

EOT;
    }

    /**
     * Render this filed.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function render()
    {
        $refresh = trans('admin.refresh');

        $this->script = $this->script();
        $this->append('<a class="btn-kv '.$this->id.'-refresh" title="'.$refresh.'"><i class="fa fa-refresh"></i></a>');

        return parent::render();
    }
}
