<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('blocked_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bike_id')->nullable()->constrained()->cascadeOnDelete(); // null = όλα τα ποδήλατα
            $table->date('start_date');
            $table->date('end_date'); // exclusive, όπως το FullCalendar convention που ήδη χρησιμοποιείς
            $table->string('reason')->nullable(); // π.χ. "Εθνική Εορτή", "Συντήρηση"
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocked_dates');
    }
};
