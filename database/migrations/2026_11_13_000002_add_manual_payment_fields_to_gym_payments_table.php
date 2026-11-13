<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gym_payments', function (Blueprint $table) {
            $table->string('proof_image_path')->nullable()->after('payment_method');
            $table->text('notes')->nullable()->after('proof_image_path');
            $table->text('admin_notes')->nullable()->after('notes');
            $table->timestamp('approved_at')->nullable()->after('admin_notes');
            $table->foreignId('approved_by')->nullable()->after('approved_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('gym_payments', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn([
                'proof_image_path',
                'notes',
                'admin_notes',
                'approved_at',
                'approved_by',
            ]);
        });
    }
};
