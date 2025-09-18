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

            $table->string('reqReferenceNumber')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phoneNumber')->unique();
            $table->string('mobileNumber')->unique();
            $table->string('passportNumber')->unique();

            $table->date(column: 'submittedAt');
            $table->timestamps();
        });

        Schema::create('request_meta_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reqId')->constrained('requests')->onDelete('cascade');

            $table->bigInteger('catId');
            $table->bigInteger('sectorId');
            $table->bigInteger('activityId');
            $table->bigInteger('subActivities');
            $table->bigInteger('entityId');

            $table->timestamps();
        });

        Schema::create('request_type_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reqId')->constrained('requests')->onDelete('cascade');

            $table->bigInteger('key');
            $table->bigInteger('secureCode');
            $table->tinyInteger('expiry')->default(1);

            $table->timestamps();
        });

        Schema::create('request_codes_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reqTypeCodeId')->constrained('request_type_codes')->onDelete('cascade');

            $table->bigInteger('key');
            $table->bigInteger('documentName');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_codes_documents');
        Schema::dropIfExists('request_meta_data');
        Schema::dropIfExists('request_type_codes');
        Schema::dropIfExists('requests');
    }
};
