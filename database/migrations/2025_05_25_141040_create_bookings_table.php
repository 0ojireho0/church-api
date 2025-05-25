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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->date('date')->nullable();
            $table->time('time_slot')->nullable();
            $table->string('service_type')->nullable();
            $table->string('filename')->nullable();
            $table->string('filepath')->nullable();
            $table->string('mop')->nullable();
            $table->enum('status', ['Pending', 'Confirmed','Denied']);
            $table->json('form_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
