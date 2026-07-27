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
            $table->foreignId('bike_id')->constrained('bikes'); // PROBLEM: When it gets deleted?
            $table->foreignId('user_id')->constrained('users'); // PROBLEM: What if it gets deleted?
            $table->foreignId('status_id')->constrained('statuses');

            // BOOKING
            $table->datetime('rent_start')->nullable();
            $table->datetime('rent_end')->nullable();
            $table->foreignId('location_id')->nullable()->constrained('locations'); // PROBLEM: What if it gets deleted?

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
