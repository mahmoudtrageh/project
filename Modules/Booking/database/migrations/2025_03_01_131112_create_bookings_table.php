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
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('client_name');
            $table->string('client_phone')->nullable();
            $table->date('enter_date');
            $table->date('leave_date');
            $table->decimal('client_sell_price', 10, 2)->comment('Price per night for client');
            $table->decimal('marketer_sell_price', 10, 2)->comment('Price per night for marketer');
            $table->decimal('buying_price', 10, 2)->comment('Price per night paid to hotel');
            $table->decimal('deposit', 10, 2)->default(0);
            $table->text('note')->nullable();
            $table->foreignId('room_type_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('rooms_number')->default(1);
            $table->foreignId('booking_source_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('marketer_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
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
