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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->foreignId('freelancer_id')->constrained('users')->onDelete('cascade');
            $table->float('bid');
            $table->integer('duration'); // Duration in days, assuming integer value is suitable
            $table->text('cover_letter');
            $table->json('attachments')->nullable();
            $table->string('status', 15)->nullable();
            $table->timestamps();

            // Composite unique key
            $table->unique(['job_id', 'freelancer_id'], 'job_freelancer_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
