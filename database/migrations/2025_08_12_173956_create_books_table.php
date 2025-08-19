<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            // titulos
            $table->string('title');
            $table->string('slug');
            $table->string('original_title')->nullable();

            // datos del libro
            $table->text('synopsis')->nullable();
            $table->date('release_date')->nullable();
            $table->integer('number_collection')->nullable();
            $table->integer('pages')->nullable();

            // opiniones del libro
            $table->longText('summary')->nullable();
            $table->longText('notes')->nullable();
            $table->string('is_favorite')->nullable(); // 0 false - 1 true

            // seleccionables desde el modelo
            $table->integer('category')->nullable(); // categorias de tipos de libros
            $table->integer('rating')->nullable(); // sin valoracion, y de 1 a 5 estrellas
            $table->string('format')->nullable(); // 1 Libro - 2 Digital - 3 Audiolibro 
            $table->integer('media_type')->nullable(); // 1 libro - 2 manga
            $table->integer('status')->nullable(); // 1 sin leer - 2 leido - 3 leyendo
            $table->integer('language')->nullable(); // 0 español - 1 ingles - 2 italiano

            // imagenes del libro
            $table->string('cover_image')->nullable();
            $table->text('cover_image_url')->nullable();
            
            // datos internos            
            $table->string('uuid')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
