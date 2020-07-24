<?php

use Illuminate\Database\Seeder;

class ConfigTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Config::create([
            'key'=>'admin_name',
            'value'=>'益邻',
            'desc'=>'后台名'
        ]);
    }
}
