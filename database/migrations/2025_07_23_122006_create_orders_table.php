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
            $table->string('g_number')->nullable();
            $table->bigInteger('income_id')->nullable();
            $table->string('number')->nullable();
            $table->dateTime('date')->nullable();
            $table->dateTime('last_change_date')->nullable();
            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->string('barcode')->nullable();
            $table->bigInteger('nm_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->integer('total_price')->nullable();
            $table->string('oblast')->nullable();
            $table->integer('discount_percent')->nullable();
            $table->boolean('is_cancel')->nullable();
            $table->string('cancel_dt')->nullable();
            $table->string('odid')->nullable();
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
