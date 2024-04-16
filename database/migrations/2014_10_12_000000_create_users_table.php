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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('username', 50)->unique()->nullable();
            $table->string('slug', 50)->unique()->nullable();

            $table->text('bio')->nullable();
            $table->string('email', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->string('picture')->nullable();
            $table->string('cover')->nullable();
            $table->string('video')->nullable();
            $table->string('phone', 20)->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('gender', 10)->nullable();
            $table->date('dob')->nullable()->comment('Date of Birth');
            $table->string('postalcode', 10)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('national_id')->nullable();
            $table->string('national_id_face')->nullable();
            $table->string('national_id_back')->nullable();
            $table->string('national_id_selfie')->nullable();

            $table->string('job_title', 100)->nullable();
            $table->enum('role', ['client', 'freelancer', 'admin'])->default('freelancer')->index();
            $table->timestamp('last_login')->nullable()->index();
            $table->float('balance')->default(0);
            $table->integer('status')->default(0)->comment('0: pending, 1: approved, 2: rejected, 3: removed');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('removed_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->text('notes')->nullable();

            $table->foreignId('specialization_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
