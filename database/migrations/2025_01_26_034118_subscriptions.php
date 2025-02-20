<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Cost per billing cycle
            $table->enum('billing_cycle', ['monthly', 'yearly', 'custom'])->default('monthly'); // Frequency of billing
            $table->boolean('is_active')->default(true); // For activating/deactivating plans

            // Plan Features
            $table->boolean('feature_1')->default(false);
            $table->boolean('feature_2')->default(false);
            $table->boolean('feature_3')->default(false);
            // ...

            $table->timestamps();
        });

        Schema::create('company_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); // References the company purchasing the subscription
            $table->unsignedBigInteger('plan_id');
            $table->date('start_date');              // Start of the subscription
            $table->date('end_date')->nullable();    // Optional: Used for non-auto-renewing plans
            $table->enum('status', ['active', 'canceled', 'expired', 'pending'])->default('pending'); // Tracks subscription state
            $table->boolean('auto_renew')->default(true); // Auto-renewal setting
            $table->timestamps();

            $table->foreign('plan_id')
                ->references('id')
                ->on('plans')
                ->onDelete('cascade');
        });

        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_subscription_id')->constrained()->onDelete('cascade');
            $table->timestamp('payment_date')->useCurrent();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['credit_card', 'paypal', 'bank_transfer', 'gcash', 'other']);
            $table->enum('status', ['pending', 'successful', 'failed', 'refunded'])->default('pending');
            $table->string('transaction_reference')->unique();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_payments');
        Schema::dropIfExists('company_subscriptions');
        Schema::dropIfExists('plans');
    }
};