<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gyms', function (Blueprint $table) {
            $table->date('subscription_ends_at')->nullable()->after('trial_ends_at');
            $table->string('subscription_plan')->nullable()->after('subscription_ends_at');
            $table->string('subscription_status')->default('trialing')->after('subscription_plan');
            $table->index(['subscription_status', 'subscription_ends_at']);
        });

        Schema::create('gym_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('plan');
            $table->string('provider')->default('paymongo');
            $table->string('provider_checkout_id')->nullable()->unique();
            $table->string('provider_payment_id')->nullable()->index();
            $table->string('reference_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('PHP');
            $table->string('status')->default('pending');
            $table->string('payment_method')->nullable();
            $table->text('checkout_url')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->date('period_starts_at')->nullable();
            $table->date('period_ends_at')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['gym_id', 'status']);
            $table->index(['gym_id', 'paid_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gym_payments');

        Schema::table('gyms', function (Blueprint $table) {
            $table->dropIndex(['subscription_status', 'subscription_ends_at']);
            $table->dropColumn([
                'subscription_ends_at',
                'subscription_plan',
                'subscription_status',
            ]);
        });
    }
};
