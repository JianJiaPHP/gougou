<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->index('id');
            $table->tinyInteger('type')->default(0)->comment('类型 0=菜单 1=按钮 2=接口 3=页面 ');
            $table->string('btn_key')->nullable()->comment('按钮权限（如 create）');
            $table->string('component',255)->nullable()->comment('组件');
            $table->string('icon',50)->nullable()->comment('图标');
            $table->bigInteger('pid')->default(0)->comment('父级id 0=顶级');
            $table->string('name',100)->nullable()->comment('权限名称');
            $table->string('router',255)->nullable()->comment('权限路径');
            $table->string('method',20)->nullable()->comment('请求方式');
            $table->integer('sort')->default(100)->comment('排序');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `yw_permissions` comment '数据权限'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
