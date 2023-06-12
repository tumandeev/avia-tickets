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
        Schema::create('ticket_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_query_id')->constrained('users');
            $table->string('query_hash');
            $table->integer('price_st');
            $table->integer('price_ad');
            $table->integer('price_mx');
            $table->string('airplaneName');
            $table->string('destinationportName');
            $table->string('origincityName');
            $table->string('destinationcityName');
            $table->timestamp('departuretime');
            $table->timestamp('departuredate');
            $table->timestamp('arrivaltime');
            $table->timestamp('arrivaldate');
            $table->json('data');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_data');
    }
};
