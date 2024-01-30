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
            $table->enum('state', ['ACTIVE', 'DRAFT', 'DELETED'])->default('DRAFT');
            $table->dateTime('validFrom')->nullable();
            $table->dateTime('validTo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
           $table->dropColumn('state');
           $table->dropColumn('validFrom');
           $table->dropColumn('validTo');
        });
    }
};
