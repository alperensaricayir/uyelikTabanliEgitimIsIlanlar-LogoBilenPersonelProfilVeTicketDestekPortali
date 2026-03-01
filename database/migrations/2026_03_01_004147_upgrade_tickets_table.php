<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('priority')->nullable()->after('status');
            $table->timestamp('last_reply_at')->nullable()->after('priority');
            $table->foreignId('last_reply_by')->nullable()->constrained('users')->nullOnDelete()->after('last_reply_at');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['priority', 'last_reply_at', 'last_reply_by']);
        });
    }
};
