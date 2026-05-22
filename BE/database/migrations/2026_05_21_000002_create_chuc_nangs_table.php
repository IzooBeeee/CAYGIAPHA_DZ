<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chuc_nangs', function (Blueprint $table): void {
            $table->id();
            $table->string('ten_chuc_nang');
            $table->string('slug_chuc_nang');
            $table->string('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chuc_nangs');
    }
};
