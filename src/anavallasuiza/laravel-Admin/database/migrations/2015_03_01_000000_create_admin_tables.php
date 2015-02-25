<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTables extends Migration
{
    protected function drop()
    {
        Schema::dropIfExists('admin_logs');
        Schema::dropIfExists('admin_sessions');
        Schema::dropIfExists('admin_users');
    }

    public function up()
    {
        Schema::create('admin_logs', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->string('related_table');
            $table->string('related_id');
            $table->string('action');
            $table->text('description');

            $table->timestamp('created_at');

            $table->integer('users_id')->unsigned();
        });

        Schema::create('admin_sessions', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->string('user');
            $table->string('ip');
            $table->string('success');

            $table->timestamp('created_at');

            $table->integer('users_id')->unsigned();
        });

        Schema::create('admin_users', function($table)
        {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->string('name');
            $table->string('user')->unique();
            $table->string('password');
            $table->string('password_token');
            $table->boolean('admin');
            $table->boolean('enabled');

            $table->rememberToken();

            $table->timestamps();
        });

        Schema::table('admin_logs', function($table)
        {
            $table->index('users_id')
                ->foreign('users_id')
                ->references('id')
                ->on('admin_users');
        });

        Schema::table('admin_sessions', function($table)
        {
            $table->index('users_id');
        });
    }

    public function down()
    {
        $this->drop();
    }
}
