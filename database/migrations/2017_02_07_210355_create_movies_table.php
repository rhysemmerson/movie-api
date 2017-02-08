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

            $table->integer('genre_id')->unsigned();
            $table->foreign('genre_id')->references('id')->on('genres');

            $table->string('name');
            $table->string('rating');
            $table->text('description');
            $table->string('thumbnail_uri');
        });

        Schema::create('actors', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('name');
            $table->date('birth_date');
            $table->integer('age');
            $table->text('bio');
            $table->string('thumbnail_uri');
        });

        Schema::create('actor_movie', function (Blueprint $table) {
            $table->integer('actor_id')->unsigned();
            $table->integer('movie_id')->unsigned();

            $table->foreign('actor_id')->references('id')->on('actors');
            $table->foreign('movie_id')->references('id')->on('movies');

            $table->string('role');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('actor_movie');
        Schema::dropIfExists('actors');
        Schema::dropIfExists('movies');
        Schema::dropIfExists('genres');
    }
}
