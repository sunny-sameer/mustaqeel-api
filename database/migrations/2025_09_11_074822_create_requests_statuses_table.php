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


        Schema::create('stages', function (Blueprint $table) {
            $table->id();

            $table->string('nameEn', 50);
            $table->string('nameAr', 50);
            $table->string('slug', 50);
            $table->string('status')->default('1');
            $table->text('description')->nullable();

            $table->timestamps();
        });

        Schema::create('stages_statuses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stageId')->constrained('stages')->onDelete('cascade');
            $table->string('nameEn', 50);
            $table->string('nameAr', 50);
            $table->string('slug', 50);
            $table->string('status')->default('1');
            $table->timestamps();
        });

        Schema::create('request_stages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reqId');
            $table->bigInteger('stageId');
            $table->string('startDate');
            $table->string('endDate');
            $table->string('status')->default('1');
            $table->timestamps();
        });

        Schema::create('requests_status', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reqStageId');
            $table->bigInteger('statusId');
            $table->string('startDate');
            $table->string('endDate');
            $table->text('comments')->nullable();
            $table->string('status')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages_statuses');
        Schema::dropIfExists('stages');
        Schema::dropIfExists('request_stages');
        Schema::dropIfExists('requests_status');
    }
};
