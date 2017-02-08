<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('name');
        });

        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('genre_id')->unsigned()->nullable();
            $table->foreign('genre_id')->references('id')->on('genres');

            $table->string('name');
            $table->string('rating')->nullable();
            $table->text('description');
            $table->string('thumbnail_uri')->nullable();
        });

        Schema::create('actors', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('name');
            $table->date('birth_date');
            $table->text('bio')->nullable();
            $table->string('thumbnail_uri')->nullable();
        });

        Schema::create('cast_members', function (Blueprint $table) {
            $table->timestamps();

            $table->integer('actor_id')->unsigned();
            $table->integer('movie_id')->unsigned();

            $table->foreign('actor_id')->references('id')->on('actors');
            $table->foreign('movie_id')->references('id')->on('movies');

            $table->string('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists('cast_members');
        Schema::dropIfExists('movies');
        Schema::dropIfExists('actors');
        Schema::dropIfExists('genres');
        
    }
}
