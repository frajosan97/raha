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
        // Subscription plans table (must come first)
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic, Premium, Pro
            $table->integer('duration_days'); // 7, 30, 180
            $table->decimal('price', 8, 2); // 200, 600, 2400
            $table->integer('listing_priority')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Users table
        Schema::create('users', function (Blueprint $table) {
            // Basic authentication
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            // Contact information
            $table->string('phone')->unique()->nullable();
            $table->string('whatsapp')->nullable();

            // Personal details
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('nationality')->nullable();
            $table->string('hair_color')->nullable();
            $table->string('eye_color')->nullable();
            $table->json('measurements')->nullable();

            // Location
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Profile content
            $table->text('about')->nullable();
            $table->json('languages')->nullable();
            $table->json('tags')->nullable();

            // Professional details
            $table->boolean('is_escort')->default(false);
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->enum('availability', ['available', 'unavailable', 'on_vacation'])->default('available');

            // Subscription status
            $table->boolean('has_active_subscription')->default(false);
            $table->timestamp('subscription_expires_at')->nullable();
            $table->timestamp('last_subscription_reminder_at')->nullable();

            // Account status
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_banned')->default(false);
            $table->enum('relationship_status', ['single', 'in_a_relationship', 'married', 'divorced', 'widowed'])->default('single');
            $table->timestamp('last_online_at')->nullable();

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });

        // Subscriptions table (fixed)
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('subscription_plans');
            $table->string('payment_reference')->nullable()->unique();
            $table->decimal('amount_paid', 8, 2)->default(0.00);
            $table->string('payment_method')->default('mpesa');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('start_date')->useCurrent();
            $table->timestamp('end_date')->nullable(); // Changed to nullable
            $table->boolean('is_auto_renew')->default(false);
            $table->timestamp('last_reminder_sent_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'end_date']);
            $table->index(['payment_status', 'end_date']);
        });

        // Password reset tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Sessions table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // User preferences
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('gender_preference')->nullable();
            $table->integer('min_age')->nullable();
            $table->integer('max_age')->nullable();
            $table->json('location_preference')->nullable();
            $table->json('other_preferences')->nullable();
            $table->timestamps();
        });

        // User services
        Schema::create('user_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_extras')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Gallery/images table
        Schema::create('user_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->boolean('is_public')->default(true);
            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_galleries');
        Schema::dropIfExists('user_services');
        Schema::dropIfExists('user_preferences');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('subscription_plans');
    }
};
