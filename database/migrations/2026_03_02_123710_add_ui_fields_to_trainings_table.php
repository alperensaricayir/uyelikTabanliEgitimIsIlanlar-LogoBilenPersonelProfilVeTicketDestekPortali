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
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('hero_video_url')->nullable();
            $table->string('hero_poster_path')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->json('promo_features')->nullable();
            $table->string('purchase_button_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn([
                'hero_video_url',
                'hero_poster_path',
                'price',
                'promo_features',
                'purchase_button_text',
            ]);
        });
    }
};
