<?php namespace Admin\Http\Controllers\Forms;

use FormManager\Inputs\Input;
use FormManager\Fields\Field;

class Admin {
    public function login()
    {
        return (new Form())->add([
            'user' => Field::text()->required()->attr([
                'placeholder' => __('Your user'),
                'autofocus' => true
            ]),
            'password' => Field::password()->required()->attr([
                'placeholder' => __('Your password')
            ]),
            'remember' => Field::checkbox()->attr([
                'placeholder' => __('Remember me'),
                'value' => '1'
            ])
        ])->wrapperInputs();
    }
}
