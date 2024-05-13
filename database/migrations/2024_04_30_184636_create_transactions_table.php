<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('escrow_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['deposit', 'payment', 'withdrawal']);
            $table->decimal('amount', 10, 2);
            $table->string('status'); // e.g., 'pending', 'completed', 'failed'
            $table->string('paymob_order_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}