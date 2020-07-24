<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateAdministratorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administrators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->index('id');
            $table->string('account',20)->nullable()->comment('登陆账号');
            $table->string('nickname',20)->nullable()->comment('昵称');
            $table->string('avatar',255)->nullable()->comment('头像');
            $table->string('password',255)->nullable()->comment('密码');
            $table->integer('role_id')->comment('角色id 0=超级管理员');
            $table->text('token')->nullable()->comment('用户token');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `yw_administrators` comment '管理员'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administrators');
    }
}
