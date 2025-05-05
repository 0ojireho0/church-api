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
        Schema::create('churches', function (Blueprint $table) {
            $table->id();
            $table->string("church_name")->nullable();
            $table->string("address")->nullable();
            $table->string("city")->nullable();
            $table->string("phone")->nullable();
            $table->string("landline")->nullable();
            $table->string("email")->nullable();
            $table->string("img")->nullable();
            $table->string("img_path")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('churches');
    }
};
