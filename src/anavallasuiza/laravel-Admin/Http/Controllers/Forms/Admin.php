<?php namespace Admin\Http\Controllers\Forms;

use FormManager\Builder as F;

class Admin extends Form
{
    public function login()
    {
        return $this->add([
            '_action' => F::hidden()->val('login'),

            'user' => F::text()->required()->attr([
                'placeholder' => __('Your user'),
                'autofocus' => true
            ]),
            'password' => F::password()->required()->attr([
                'placeholder' => __('Your password')
            ]),
            'remember' => F::checkbox()->attr([
                'placeholder' => __('Remember me'),
                'value' => '1'
            ])
        ])->wrapperInputs();
    }
}
