<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->bigInteger('barcode')->nullable();
            $table->integer('quantity')->nullable()->default(0);
            $table->boolean('is_supply')->nullable()->default(false);
            $table->boolean('is_realization')->nullable()->default(false);
            $table->integer('quantity_full')->nullable()->default(0);
            $table->string('warehouse_name')->nullable();
            $table->integer('in_way_to_client')->nullable()->default(0);
            $table->integer('in_way_from_client')->nullable()->default(0);
            $table->bigInteger('nm_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->bigInteger('sc_code')->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->integer('discount')->nullable()->default(0);

            $table->timestamps();

            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
