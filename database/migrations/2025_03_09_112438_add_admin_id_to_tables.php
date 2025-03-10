<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add admin_id to hotels table
        Schema::table('hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable()->after('id');
            $table->foreign('admin_id')->references('id')->on('admins');
        });
        
        // Add admin_id to bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable()->after('id');
            $table->foreign('admin_id')->references('id')->on('admins');
        });
                
        // Add admin_id to booking_sources table
        Schema::table('booking_sources', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable()->after('id');
            $table->foreign('admin_id')->references('id')->on('admins');
        });
        
        // Add admin_id to payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable()->after('id');
            $table->foreign('admin_id')->references('id')->on('admins');
        });
        
        // Add admin_id to room_types table
        Schema::table('room_types', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_id')->nullable()->after('id');
            $table->foreign('admin_id')->references('id')->on('admins');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->nullable()->after('user_type');
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove admin_id from hotels table
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
        
        // Remove admin_id from bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
        
        // Remove admin_id from marketers table
        Schema::table('marketers', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
        
        // Remove admin_id from booking_sources table
        Schema::table('booking_sources', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
        
        // Remove admin_id from payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
        
        // Remove admin_id from room_types table
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });

        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn('created_by');
        });
    }
};
