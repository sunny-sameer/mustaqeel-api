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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('nameAr');
            $table->string('slug');

            $table->tinyInteger('status')->default(0);

            $table->timestamps();
        });

        Schema::create('sectors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nameAr');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('categoryId')->constrained('categories')->onDelete('cascade');

            $table->string('name');
            $table->string('nameAr');
            $table->string('slug');

            $table->tinyInteger('status')->default(0);

            $table->timestamps();
        });


        Schema::create('category_sector', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoryId')->constrained('categories')->onDelete('cascade');
            $table->foreignId('sectorId')->constrained('sectors')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['categoryId', 'sectorId']);
        });


        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sectorId')->constrained('sectors')->onDelete('cascade');

            $table->string('name');
            $table->string('nameAr');
            $table->string('slug');

            $table->timestamps();
        });


        Schema::create('sub_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activityId')->constrained('activities')->cascadeOnDelete();
            $table->string('name');
            $table->string('nameAr')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Entities
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('nameAr')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Pivot table Activity ↔ Entity
        Schema::create('activity_entity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activityId')->constrained('activities')->cascadeOnDelete();
            $table->foreignId('entityId')->constrained('entities')->cascadeOnDelete();
            $table->unique(['activityId', 'entityId']);
        });

        // Incubators
        Schema::create('incubators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoryId')->constrained('categories')->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('nameAr')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
        Schema::dropIfExists('incubators');
        Schema::dropIfExists('activity_entity');
        Schema::dropIfExists('entities');
        Schema::dropIfExists('sub_activities');
        Schema::dropIfExists('activities');
        Schema::dropIfExists('category_sector');
        Schema::dropIfExists('sectors');
        Schema::dropIfExists('categories');
    }
};
