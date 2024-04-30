<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEscrowsTable extends Migration
{
    public function up()
    {
        Schema::create('escrows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('status'); // e.g., 'held', 'released', 'refunded'
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('escrows');
    }
}