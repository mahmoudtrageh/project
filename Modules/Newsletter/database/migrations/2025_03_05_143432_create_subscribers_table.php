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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('source')->nullable()->comment('Where the subscriber came from');
            $table->datetime('last_sent_at')->nullable();
            $table->datetime('subscribed_at')->nullable();
            $table->datetime('unsubscribed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('newsletter_subscriber', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newsletter_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscriber_id')->constrained()->onDelete('cascade');
            $table->string('status')->nullable(); // pending, sent, failed
            $table->datetime('sent_at')->nullable();
            $table->datetime('opened_at')->nullable();
            $table->text('error')->nullable();
            $table->timestamps();
            
            $table->unique(['newsletter_id', 'subscriber_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscriber');
        Schema::dropIfExists('subscribers');
    }
};
