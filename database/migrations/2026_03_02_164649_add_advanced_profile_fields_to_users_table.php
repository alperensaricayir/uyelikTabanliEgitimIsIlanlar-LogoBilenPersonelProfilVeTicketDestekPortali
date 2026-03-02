<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('headline', 120)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('country', 80)->nullable();
            $table->text('bio')->nullable(); // Max 2000 chars validated in controller
            $table->json('skills')->nullable();
            $table->string('website_url', 2048)->nullable();
            $table->string('linkedin_url', 2048)->nullable();
            $table->string('github_url', 2048)->nullable();
            $table->string('instagram_url', 2048)->nullable();
            $table->string('youtube_url', 2048)->nullable();
            $table->string('twitter_url', 2048)->nullable();
            $table->string('behance_url', 2048)->nullable();
            $table->string('dribbble_url', 2048)->nullable();
            $table->boolean('is_profile_public')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'profile_photo_path',
                'headline',
                'city',
                'country',
                'bio',
                'skills',
                'website_url',
                'linkedin_url',
                'github_url',
                'instagram_url',
                'youtube_url',
                'twitter_url',
                'behance_url',
                'dribbble_url',
                'is_profile_public'
            ]);
        });
    }
};
