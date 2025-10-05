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

        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users')->onDelete('cascade');

            $table->string('reqReferenceNumber',50)->unique();
            $table->string('nameEn',100);
            $table->string('nameAr')->nullable();
            $table->string('email',100);
            $table->string('mobileNumber',18);
            $table->string('passportNumber',15);
            $table->string('qid',15)->nullable();
            $table->tinyInteger('status')->default(0);

            $table->date('submittedAt');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('request_meta_data', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reqId')->constrained('requests')->onDelete('cascade');

            $table->string('catSlug',50);
            $table->string('subCatSlug',50)->nullable();
            $table->string('sectorSlug',50);
            $table->string('activitySlug',50);
            $table->string('subActivitySlug',50)->nullable();
            $table->string('entitySlug',50)->nullable();
            $table->string('incubatorSlug',50)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('request_type_codes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reqId')->constrained('requests')->onDelete('cascade');

            $table->bigInteger('key');
            $table->bigInteger('secureCode');
            $table->tinyInteger('expiry')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('request_codes_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reqTypeCodeId')->constrained('request_type_codes')->onDelete('cascade');

            $table->bigInteger('key');
            $table->bigInteger('documentName');

            $table->timestamps();
            $table->softDeletes();
        });

        //family_member, jobs, educations, current_residence, other_nationalities, countries_visited, request

        Schema::create('request_attributes', function (Blueprint $table){
            $table->id();

            $table->foreignId('reqId')->constrained('requests')->onDelete('cascade');

            $table->longText('meta')->nullable();
            $table->string('type');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
        Schema::dropIfExists('request_meta_data');
        Schema::dropIfExists('request_type_codes');
        Schema::dropIfExists('request_codes_documents');
        Schema::dropIfExists('request_attributes');
    }
};
