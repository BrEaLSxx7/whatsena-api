<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('numero_documento', 20)->unique();
            $table->string('primer_nombre', 30)->nullable();
            $table->string('segundo_nombre', 30)->nullable();
            $table->string('primer_apellido', 30)->nullable();
            $table->string('segundo_apellido', 30)->nullable();
            $table->string('foto', 100)->default('user.png');
            $table->integer('id_tipo_documento')->unsigned();
            $table->timestamps();

            $table->foreign('id_tipo_documento')->references('id')->on('document_types')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('users');
    }

}
