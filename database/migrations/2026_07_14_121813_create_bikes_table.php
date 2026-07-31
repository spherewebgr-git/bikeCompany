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
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete(); // CHECK: ->cascadeOnDelete();
            $table->foreignId('type_id')->constrained('types'); // CHECK: ->cascadeOnDelete();
            $table->foreignId('speed_id')->constrained('speeds'); // CHECK: ->cascadeOnDelete();
            $table->foreignId('provision_id')->constrained('provisions'); // CHECK: ->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained('brands'); // CHECK: ->cascadeOnDelete(); ?
            $table->foreignId('type_id')->constrained('types'); // CHECK: ->cascadeOnDelete(); ?
            $table->foreignId('speed_id')->constrained('speeds'); // CHECK: ->cascadeOnDelete(); ?
            $table->foreignId('provision_id')->constrained('provisions'); // CHECK: ->cascadeOnDelete(); ?
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
