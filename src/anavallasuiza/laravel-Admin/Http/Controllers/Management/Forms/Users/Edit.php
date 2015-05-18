<?php
namespace Admin\Http\Controllers\Management\Forms\Users;

use FormManager\Builder as B;
use Laravel\FormManager\Form as F;

class Edit extends F
{
    public function __construct()
    {
        return $this->method('post')->add([
            'id' => B::hidden(),
            '_processor' => B::hidden()->value('edit'),

            'name' => B::text()->required()->attr([
                'placeholder' => __('Name'),
            ]),
            'user' => B::text()->required()->attr([
                'placeholder' => __('User'),
            ]),
            'password' => B::password()->attr([
                'placeholder' => __('Password'),
            ]),
            'password_repeat' => B::password()->attr([
                'placeholder' => __('Repeat Password'),
            ]),
            'admin' => B::checkbox()->attr([
                'placeholder' => __('Admin'),
                'value' => '1',
            ]),
            'enabled' => B::checkbox()->attr([
                'placeholder' => __('Enabled'),
                'value' => '1',
            ]),
        ])->setRender('Bootstrap');
    }
}
