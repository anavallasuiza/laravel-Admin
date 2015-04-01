<?php

use Illuminate\Database\Migrations\Migration;

class CreateAdminTables extends Migration
{
    public function up()
    {
        $this->upTables();
        $this->upIndex();
    }

    protected function upTables()
    {
        Schema::create('admin_logs', function($table) {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->string('related_table');
            $table->string('related_id');
            $table->string('action');
            $table->text('description');

            $table->timestamp('created_at');

            $table->integer('admin_users_id')->unsigned();
        });

        Schema::create('admin_menus', function($table) {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->string('title');
            $table->string('route');

            $table->boolean('enabled');
        });

        Schema::create('admin_menus_users', function($table) {
            $table->engine = 'InnoDB';

            $table->boolean('list');
            $table->boolean('update');
            $table->boolean('create');
            $table->boolean('delete');

            $table->integer('admin_menus_id')->unsigned();
            $table->integer('admin_users_id')->unsigned();
        });

        Schema::create('admin_sessions', function($table) {
            $table->engine = 'InnoDB';

            $table->increments('id');

            $table->string('user');
            $table->string('ip');
            $table->string('success');

            $table->timestamp('created_at');

            $table->integer('admin_users_id')->unsigned();
        });

        Schema::create('admin_users', function($table) {
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
    }

    protected function upIndex()
    {
        Schema::table('admin_logs', function($table) {
            $table->foreign('admin_users_id')
                ->references('id')
                ->on('admin_users');
        });

        Schema::table('admin_menus_users', function($table) {
            $table->foreign('admin_menus_id')
                ->references('id')
                ->on('admin_menus');

            $table->foreign('admin_users_id')
                ->references('id')
                ->on('admin_users');
        });

        Schema::table('admin_sessions', function($table) {
            $table->foreign('admin_users_id')
                ->references('id')
                ->on('admin_users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_logs');
        Schema::dropIfExists('admin_menus_users');
        Schema::dropIfExists('admin_menus');
        Schema::dropIfExists('admin_sessions');
        Schema::dropIfExists('admin_users');
    }
}
