<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User subscribing
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); // Course being subscribed to
            $table->string('phone'); // Phone number (required)
            $table->string('location'); // Iraqi governorate
            
            // ENUM for request status (default to 'pending')
            $table->enum('request_status', ['pending', 'successful', 'failed'])->default('pending');
        
            // ENUM for payment method (no default specified)
            $table->enum('payment_method', ['office', 'representative', 'zain_cash', 'master_card']); 
            $table->text('note')->nullable();
            $table->text('details')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users'); // Admin approving the subscription
            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
