<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marriages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('family_id');
            $table->unsignedBigInteger('husband_id')->nullable();
            $table->unsignedBigInteger('wife_id')->nullable();
            $table->date('married_date')->nullable();
            $table->date('divorced_date')->nullable();
            $table->enum('status', ['married', 'divorced', 'widowed'])->default('married');
            $table->timestamps();

            $table->foreign('family_id')
                ->references('id')
                ->on('families')
                ->onDelete('cascade');

            $table->foreign('husband_id')
                ->references('id')
                ->on('people')
                ->onDelete('set null');

            $table->foreign('wife_id')
                ->references('id')
                ->on('people')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marriages');
    }
};
