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

            $table->string('name', 50);
            $table->string('nameAr', 50);
            $table->string('slug', 50);
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stages_statuses', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('stageId')->constrained('stages')->onDelete('cascade');

            $table->string('name', 50);
            $table->string('nameAr', 50);
            $table->string('slug', 50);
            $table->tinyInteger('status')->default(1);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('request_stages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reqId')->constrained('requests')->onDelete('cascade');

            $table->string('stageSlug',50);

            $table->date('startDate');
            $table->date('endDate');
            $table->tinyInteger('status')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('request_statuses', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('reqStageId');

            $table->string('stageStatuSlug',50);

            $table->date('startDate');
            $table->date('endDate');
            $table->text('comments')->nullable();
            $table->tinyInteger('status')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stages');
        Schema::dropIfExists('stages_statuses');
        Schema::dropIfExists('request_stages');
        Schema::dropIfExists('request_statuses');
    }
};
