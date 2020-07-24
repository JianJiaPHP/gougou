<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminLoginLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_login_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->index('id');
            $table->integer('uid')->nullable()->comment('管理员id');
            $table->string('ip',50)->nullable()->comment('ip地址');
            $table->string('country',50)->nullable()->comment('国家');
            $table->string('region',50)->nullable()->comment('区域');
            $table->string('city',50)->nullable()->comment('城市');
            $table->string('county',50)->nullable()->comment('县');
            $table->string('isp',50)->nullable()->comment('运营商');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `yw_admin_login_logs` comment '管理登陆日志'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_login_logs');
    }
}
