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
        // Schema::create('sectors', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('nameAr');
        //     $table->string('slug');
        //     $table->timestamps();
        // });


        // Schema::create('category_sector', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('categoryId')->constrained('categories')->onDelete('cascade');
        //     $table->foreignId('sectorId')->constrained('sectors')->onDelete('cascade');
        //     $table->timestamps();

        //     $table->unique(['categoryId', 'sectorId']);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
