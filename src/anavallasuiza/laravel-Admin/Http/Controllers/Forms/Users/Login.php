<?php
namespace Admin\Http\Controllers\Forms\Users;

use FormManager\Builder as B;
use Laravel\FormManager\Form as F;

class Login extends F
{
    public function __construct()
    {
        return $this->method('post')->add([
            '_processor' => B::hidden()->val('login'),

            'user' => B::text()->attr([
                'placeholder' => __('Tu usuario'),
                'autofocus' => true,
                'required' => true,
            ]),
            'password' => B::password()->attr([
                'placeholder' => __('Tu contraseña'),
                'required' => true,
            ]),
            'remember' => B::checkbox()->attr([
                'placeholder' => __('Recuérdame'),
                'value' => '1',
            ]),
        ])->setRender('Bootstrap');
    }
}
