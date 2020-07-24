<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->increments('id');
            $table->index('id');
            $table->string('key',255)->nullable()->comment('配置key');
            $table->string('value',255)->nullable()->comment('配置值');
            $table->string('desc',255)->nullable()->comment('配置描述');
            $table->timestamps();
        });
        \DB::statement("ALTER TABLE `yw_config` comment '配置'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config');
    }
}
