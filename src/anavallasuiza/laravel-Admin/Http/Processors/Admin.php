<?php namespace Admin\Http\Processors;

use ErrorException;
use Auth, Input, Hash, Redirect, Request, Session;
use Admin\Models;

class Admin extends Processor {
    public function login($form)
    {
        if (!($data = $this->check(__FUNCTION__, $form))) {
            return $data;
        }

        $success = Auth::attempt([
            'user' => $data['user'],
            'password' => $data['password']
        ], true);

        if ($success !== true) {
            Models\Session::create([
                'user' => $data['user'],
                'ip' => Request::getClientIp(),
                'created_at' => date('Y-m-d H:i:s'),
                'success' => 0
            ]);

            throw new ErrorException(__('User or password is not correct'));
        }

        $user = Auth::user();

        if (empty($user->enabled)) {
            Models\Session::create([
                'user' => $data['user'],
                'ip' => Request::getClientIp(),
                'created_at' => date('Y-m-d H:i:s'),
                'success' => 0,
                'users_id' => $user->id
            ]);

            Auth::logout();

            throw new ErrorException(__('Sorry but your user is disabled. Please contact with us to solve this problem.'));
        }

        Models\Session::create([
            'user' => $data['user'],
            'ip' => Request::getClientIp(),
            'created_at' => date('Y-m-d H:i:s'),
            'success' => 1,
            'users_id' => $user->id
        ]);

        $referer = Input::get('referer');

        if (empty($referer) || ($referer === getenv('REQUEST_URI'))) {
            return Redirect::route('admin.index');
        } else {
            return Redirect::away($referer);
        }
    }
}
