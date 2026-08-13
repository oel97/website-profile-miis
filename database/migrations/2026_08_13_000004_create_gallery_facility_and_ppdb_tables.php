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
        Schema::create('gallery_albums', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->date('event_date')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_published', 'event_date']);
            $table->index(['is_published', 'sort_order']);
        });

        Schema::create('gallery_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_album_id')->constrained()->cascadeOnDelete();
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->string('alt_text')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['gallery_album_id', 'sort_order']);
        });

        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('image_path')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['is_published', 'sort_order']);
        });

        Schema::create('ppdb_periods', function (Blueprint $table) {
            $table->id();
            $table->string('academic_year');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('banner_path')->nullable();
            $table->dateTime('registration_start_at')->nullable();
            $table->dateTime('registration_end_at')->nullable();
            $table->dateTime('announcement_at')->nullable();
            $table->string('registration_url')->nullable();
            $table->string('contact_whatsapp', 30)->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->index(['is_active', 'is_published']);
        });

        Schema::create('ppdb_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppdb_period_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['ppdb_period_id', 'is_active', 'sort_order']);
        });

        Schema::create('ppdb_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppdb_period_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['ppdb_period_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_steps');
        Schema::dropIfExists('ppdb_requirements');
        Schema::dropIfExists('ppdb_periods');
        Schema::dropIfExists('facilities');
        Schema::dropIfExists('gallery_photos');
        Schema::dropIfExists('gallery_albums');
    }
};
