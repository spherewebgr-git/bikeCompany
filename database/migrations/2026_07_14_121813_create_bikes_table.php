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
        Schema::create('bikes', function (Blueprint $table) {
            $table->id();
            $table->string('colour');
            $table->string('image_path');
            $table->foreignId('brand_id')->constrained('brands');
            $table->foreignId('type_id')->constrained('types');
            $table->foreignId('speed_id')->constrained('speeds');
            $table->foreignId('provision_id')->constrained('provisions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bikes');
    }
};
