<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('cuisine_type');
            $table->text('description');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->decimal('price_range', 2, 1); // 1.0 to 5.0
            $table->decimal('rating', 2, 1)->default(0.0); // 0.0 to 5.0
            $table->boolean('accepts_reservations')->default(false);
            $table->json('opening_hours');
            $table->json('facilities')->nullable(); // WiFi, Parking, etc.
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
