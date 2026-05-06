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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['g_number']);
            $table->unique(['g_number', 'account_id']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropUnique(['sale_id']);
            $table->unique(['sale_id', 'account_id']);
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->dropUnique(['income_id']);
            $table->unique(['income_id', 'account_id']);
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->unique(['barcode', 'warehouse_name', 'date', 'account_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['g_number', 'account_id']);
            $table->unique('g_number');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropUnique(['sale_id', 'account_id']);
            $table->unique('sale_id');
        });

        Schema::table('incomes', function (Blueprint $table) {
            $table->dropUnique(['income_id', 'account_id']);
            $table->unique('income_id');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->dropUnique(['barcode', 'warehouse_name', 'date', 'account_id']);
        });
    }
};
