<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('family_id');
            $table->string('full_name');
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('birth_date')->nullable();
            $table->date('death_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('avatar')->nullable();
            $table->text('biography')->nullable();

            // Quan hệ gia phả
            $table->unsignedBigInteger('father_id')->nullable();
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->timestamps();

            $table->foreign('family_id')
                ->references('id')
                ->on('families')
                ->onDelete('cascade');

            $table->foreign('father_id')
                ->references('id')
                ->on('people')
                ->onDelete('set null');

            $table->foreign('mother_id')
                ->references('id')
                ->on('people')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
