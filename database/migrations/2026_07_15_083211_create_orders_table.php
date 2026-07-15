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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('price');
            $table->date('order_date');
            $table->boolean('payed_off');
            $table->foreignId('card_id')->nullable()->constrained('cards');
            $table->foreignId('bike_id')->constrained('bikes');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('status_id')->constrained('statuses');

            // BOOKING
            $table->date('rent_start')->nullable();
            $table->date('rent_end')->nullable();
            $table->foreignId('location_id')->nullable()->constrained('locations');

            // PURCHASE
            $table->string('dropoff_address')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
