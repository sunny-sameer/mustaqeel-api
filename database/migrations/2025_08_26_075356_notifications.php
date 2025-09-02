<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->text('subject')->nullable();
            $table->longText('default_content')->nullable();
            $table->longText('custom_content')->nullable();
            $table->enum('type', ['sms', 'mail', 'database']);
            $table->json('placeholders')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_events', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('notification_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('notification_event_template', function (Blueprint $table) {
            $table->foreignId('notification_event_id')->constrained()->onDelete('cascade');
            $table->foreignId('notification_template_id')->constrained()->onDelete('cascade');
            $table->primary(['notification_event_id', 'notification_template_id']);
        });

        // Laravel's default notifications table
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_event_template');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('notification_channels');
        Schema::dropIfExists('notification_events');
        Schema::dropIfExists('notification_templates');
    }
};
