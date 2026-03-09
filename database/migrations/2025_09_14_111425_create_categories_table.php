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

            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sectors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nameAr');
            $table->string('slug');

            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('categoryId')->constrained('categories')->onDelete('cascade');

            $table->string('name');
            $table->string('nameAr');
            $table->string('slug');

            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('category_sector', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoryId')->constrained('categories')->onDelete('cascade');
            $table->foreignId('sectorId')->constrained('sectors')->onDelete('cascade');

            $table->unique(['categoryId', 'sectorId']);
        });


        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sectorId')->constrained('sectors')->onDelete('cascade');

            $table->string('name');
            $table->string('nameAr');
            $table->string('slug');

            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('sub_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activityId')->constrained('activities')->cascadeOnDelete();
            $table->string('name');
            $table->string('nameAr')->nullable();
            $table->string('slug')->unique();

            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        // Entities
        Schema::create('entities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('nameAr')->nullable();
            $table->string('slug')->unique();

            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
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

            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        // Nationalities
        Schema::create('nationalities', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('slug');
            $table->text('phonecode')->nullable();
            $table->timestamps();
        });

        // Form Fields table
        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('nameEn');
            $table->string('nameAr');
            $table->string('slug')->unique();
            $table->string('type'); // text, textarea, select, radio, checkbox, file, date, email, number

            // Organization & Layout
            $table->string('section')->default('general'); // personal-info, employment-education, etc
            $table->string('group')->default('general');   // identification-data, applicant-info, etc
            $table->integer('fieldOrder')->default(0);
            $table->integer('gridColumns')->default(4);   // 1-12 for grid layout

            // Repeatable Groups
            $table->boolean('repeatable')->default(false);
            $table->string('repeatableLabel')->nullable(); // "Add Previous Job"
            $table->integer('repeatableMax')->nullable();  // Maximum number of repeats

            // Meta & Conditions
            $table->longText('meta')->nullable();           // Field-specific config (options, placeholders, validations)
            $table->json('conditions')->nullable();         // Conditional logic (show/hide based on other fields)

            // Status
            $table->tinyInteger('status')->default(1);

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index(['section', 'group', 'fieldOrder']);
            $table->index('status');
            $table->index('type');
        });

        // Form Field 
        Schema::create('form_field_metas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ffId')->constrained('form_fields')->onDelete('cascade')->onUpdate('cascade');

            // Category hierarchy
            $table->string('key', 50);                    // category_slug
            $table->json('value');                          // Stores sub_category, sector, activity, etc.

            // Visibility rules
            $table->string('onshoreOffShore');              // onshore, offshore, both
            $table->boolean('isRequired');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('key');
            $table->index('onshoreOffShore');
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
        Schema::dropIfExists('nationalities');
        Schema::dropIfExists('form_fields');
        Schema::dropIfExists('form_field_metas');
    }
};
