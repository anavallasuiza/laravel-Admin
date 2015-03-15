<?php namespace Admin\Http\Processors\Management;

use Exception;
use Admin\Http\Processors\Processor;
use Admin\Models;
use Hash;
use Session;
use Redirect;

class Users extends Processor
{
    public function edit($form)
    {
        if (!($data = $this->check(__FUNCTION__, $form))) {
            return $data;
        }

        if ($data['password'] !== $data['password_repeat']) {
            throw new Exception(__('Passwords must be equals.'));
        }

        $exists = Models\User::where('user', '=', $data['user'])
            ->where('id', '!=', $data['id'])
            ->first();

        if ($exists) {
            throw new Exception(__('Already exists another user with user "%s"', $data['user']));
        }

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } elseif (empty($data['id'])) {
            throw new Exception(__('Password is required for new users'));
        } else {
            unset($data['password']);
        }

        unset($data['password_repeat']);

        try {
            $row = Models\User::replace($data);
        } catch (Exception $e) {
            throw new Exception(__('Error storing data: %s', $e->getMessage()));
        }

        Session::flash('flash-message', [
            'message' => __('Data was saved successfully'),
            'status' => 'success',
        ]);

        return Redirect::route('admin.management.users.edit', $row->id);
    }
}
