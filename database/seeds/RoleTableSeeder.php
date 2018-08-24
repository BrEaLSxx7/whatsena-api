<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('roles')->insert([
            'nombre' => 'Admin'
        ]);
        DB::table('roles')->insert([
            'nombre' => 'Instructor'
        ]);
        DB::table('roles')->insert([
            'nombre' => 'Aprendiz'
        ]);
    }

}
