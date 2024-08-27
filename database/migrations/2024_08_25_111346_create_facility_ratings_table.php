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
        Schema::create('facility_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor('user_id');
            $table->foreignIdFor('facility_id');
            $table->integer('rating');
            $table->string('review');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facility_ratings');
    }
};