<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('status')->default('draft')->after('slug');
            $table->string('thumbnail')->nullable()->after('status');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->after('thumbnail');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('updated_by');
            $table->dropColumn(['status', 'thumbnail', 'deleted_at']);
        });
    }
};
