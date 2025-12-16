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
        Schema::create('gifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wishlist_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('link')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('is_booked')->default(false);
            $table->text('booked_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gifts');
    }
};
