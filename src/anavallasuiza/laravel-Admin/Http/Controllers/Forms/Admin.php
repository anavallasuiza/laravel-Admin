<?php namespace Admin\Http\Controllers\Forms;

use FormManager\Fields\Field;

class Admin extends Form
{
    public function login()
    {
        return $this->add([
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
