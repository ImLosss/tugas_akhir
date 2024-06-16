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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('no_meja')->nullable();
            $table->bigInteger('total')->nullable();
            $table->bigInteger('profit')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->boolean('pembayaran')->default(false);
            $table->string('kasir');
            $table->bigInteger('partner')->nullable();
            $table->bigInteger('partner_price')->nullable();
            $table->bigInteger('partner_profit')->nullable();
            $table->bigInteger('partner_total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
