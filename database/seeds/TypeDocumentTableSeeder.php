<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeDocumentTableSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        DB::table('document_types')->insert([
            'nombre' => 'Cédula ciudadanía'
        ]);
        DB::table('document_types')->insert([
            'nombre' => 'Cédula extranjería'
        ]);
        DB::table('document_types')->insert([
            'nombre' => 'Tarjeta identidad'
        ]);
        DB::table('document_types')->insert([
            'nombre' => 'Pasaporte'
        ]);
    }

}
