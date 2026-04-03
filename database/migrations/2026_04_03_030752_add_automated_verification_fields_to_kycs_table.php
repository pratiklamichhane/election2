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
        Schema::table('kycs', function (Blueprint $table) {
            $table->string('nagrita_number_normalized')->nullable()->after('nagrita_number');
            $table->string('nagrita_front_hash', 64)->nullable()->after('nagrita_front');
            $table->string('nagrita_back_hash', 64)->nullable()->after('nagrita_back');
            $table->date('date_of_birth')->nullable()->after('nagrita_back_hash');
            $table->string('district')->nullable()->after('date_of_birth');
            $table->unsignedSmallInteger('ward_no')->nullable()->after('district');
            $table->enum('verification_status', ['pending', 'verified', 'manual_review', 'rejected'])
                ->default('pending')
                ->after('ward_no');
            $table->unsignedTinyInteger('verification_score')->nullable()->after('verification_status');
            $table->json('verification_flags')->nullable()->after('verification_score');
            $table->timestamp('verified_at')->nullable()->after('verification_flags');
            $table->timestamp('last_verified_at')->nullable()->after('verified_at');
            $table->text('rejection_reason')->nullable()->after('last_verified_at');

            $table->index('nagrita_number_normalized');
            $table->index('verification_status');
            $table->index('nagrita_front_hash');
            $table->index('nagrita_back_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kycs', function (Blueprint $table) {
            $table->dropIndex(['nagrita_number_normalized']);
            $table->dropIndex(['verification_status']);
            $table->dropIndex(['nagrita_front_hash']);
            $table->dropIndex(['nagrita_back_hash']);

            $table->dropColumn([
                'nagrita_number_normalized',
                'nagrita_front_hash',
                'nagrita_back_hash',
                'date_of_birth',
                'district',
                'ward_no',
                'verification_status',
                'verification_score',
                'verification_flags',
                'verified_at',
                'last_verified_at',
                'rejection_reason',
            ]);
        });
    }
};
