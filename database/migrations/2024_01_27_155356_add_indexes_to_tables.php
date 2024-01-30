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
        Schema::table('products', function (Blueprint $table) {
            $table->fullText('name');
        });
        Schema::table('variants', function (Blueprint $table) {
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->dropFullText('products_name_fulltext');
        });
        Schema::table('variants', function (Blueprint $table) {
            $table->dropFullText('variants_price_index');
        });
    }
};
