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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->index();
            $table->text('description');
            $table->json('required_skills')->nullable();
            $table->float('expected_budget')->index();
            $table->integer('expected_duration');
            $table->json('attachments')->nullable();
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade')->index();
            $table->foreignId('specialization_id')->constrained('specializations')->onDelete('cascade');
            $table->string('status', 15)->nullable();

            // type
            $table->string('type', 15)->default('0')->comment('open, closed');

            // location type
            $table->string('location_type', 15)->default('0')->comment('remote, on-site');

            // location
            $table->string('longitude', 150)->nullable();
            $table->string('latitude', 150)->nullable();
            $table->string('address', 150)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
