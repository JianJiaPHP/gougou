<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function (Blueprint $table) {
            $table->increments('id');
            $table->index('id');
            $table->string('name',50)->nullable()->comment('角色名');
            $table->string('desc',100)->nullable()->comment('角色描述');
            $table->text('permission')->nullable()->comment('数据权限集合序列化后的id');
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `yw_role` comment '角色'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role');
    }
}
