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
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reqBy')->constrained('users')->onDelete('cascade');
            $table->foreignId('subBy')->nullable()->constrained('users')->onDelete('cascade');

            $table->foreignId('reqId')->constrained('requests')->onDelete('cascade');

            $table->text('descriptionEn')->nullable();
            $table->text('descriptionAr')->nullable();
            $table->longText('meta')->nullable();
            $table->json('summary')->nullable();

            $table->timestamp('requestedAt');
            $table->timestamp('submittedAt')->nullable();
            $table->timestamp('verifiedAt')->nullable();

            $table->string('status')->default('Action Required');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_checks');
    }
};
