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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('userId')->constrained('users')->onDelete('cascade');
            $table->string('occupation')->nullable();
            $table->string('gender')->nullable();
            $table->string('nationality')->nullable();
            $table->string('countryOfResidence')->nullable();
            $table->string('religion')->nullable();
            $table->string('dob')->nullable();
            $table->string('pob')->nullable();
            $table->string('maritalStatus')->nullable();
            $table->string('shortBiography')->nullable();
            $table->tinyInteger('status')->default(0);

            $table->timestamps();
        });

        Schema::create('passport_details', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('entityId')->constrained('users')->onDelete('cascade');
            $table->string('passportNumber')->nullable();
            $table->string('passportType')->nullable();
            $table->string('passportIssuerDate')->nullable();
            $table->string('passportExpiryDate')->nullable();
            $table->string('passportIssueBy')->nullable();
            $table->string('passportIssuingCountry')->nullable();
            $table->string('passportPlaceOfIssue')->nullable();
            $table->tinyInteger('status')->default(0);

            $table->timestamps();
        });

        Schema::create('communications', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('entityId')->constrained('users')->onDelete('cascade'); // userId
            $table->string('key')->nullable(); // mobileName,email,langProficiencyEng,langProficiencyAr
            $table->json('value')->nullable(); //93933939,tet@yahoo.com,2,3
            $table->string('status')->default(0);
            $table->timestamps();
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('entityId')->constrained('users')->onDelete('cascade');
            $table->string('zip')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->tinyInteger('status')->default(0);

            $table->timestamps();
        });

        Schema::create('qatar_info', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('entityId')->constrained('users')->onDelete('cascade'); // userId
            $table->string('key')->nullable(); // sponsorName, addressSponsor,qidNo,qidType,address,workPermit,mainWorkPermit.
            $table->json('value')->nullable(); //93933939,tet@yahoo.com
            $table->string('status')->default(0);
            $table->timestamps();
        });

        //family_member, jobs, educations, current_residence, other_nationalities, countries_visited
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('passport_details');
        Schema::dropIfExists('communications');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('qatar_info');
    }
};
