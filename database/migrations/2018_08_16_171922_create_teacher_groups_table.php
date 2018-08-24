<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherGroupsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('teacher_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_autorizacion')->unsigned();
            $table->integer('id_grupo')->unsigned();
            $table->timestamps();

            $table->foreign('id_autorizacion')->references('id')->on('authentications')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id_grupo')->references('id')->on('groups')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('teacher_groups');
    }

}
