<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('total_client_price', 10, 2)->nullable();
            $table->enum('payment_status', ['paid', 'pending'])->default('pending');
            $table->string('booking_number')->nullable()->unique();
        });

        $bookings = DB::table('bookings')->get();
        $counter = 1000;
        
        foreach ($bookings as $booking) {
            DB::table('bookings')
                ->where('id', $booking->id)
                ->update(['booking_number' => 'B-' . $counter]);
            $counter++;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
