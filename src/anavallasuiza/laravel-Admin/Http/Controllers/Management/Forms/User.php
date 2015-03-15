<?php namespace Admin\Http\Controllers\Management\Forms;

use Admin\Http\Controllers\Forms\Form;
use FormManager\Builder as F;

class User extends Form
{
    public function edit()
    {
        return $this->add([
            'id' => F::hidden(),
            '_action' => F::hidden()->value('edit'),

            'name' => F::text()->required()->attr([
                'placeholder' => __('Name'),
            ]),
            'user' => F::text()->required()->attr([
                'placeholder' => __('User'),
            ]),
            'password' => F::password()->attr([
                'placeholder' => __('Password'),
            ]),
            'password_repeat' => F::password()->attr([
                'placeholder' => __('Repeat Password'),
            ]),
            'admin' => F::checkbox()->attr([
                'placeholder' => __('Admin'),
                'value' => '1',
            ]),
            'enabled' => F::checkbox()->attr([
                'placeholder' => __('Enabled'),
                'value' => '1',
            ]),
        ])->wrapperInputs();
    }
}
