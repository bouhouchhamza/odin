<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('links', function (Blueprint $table) {
            if (!Schema::hasColumn('links', 'normalized_url')) {
                $table->string('normalized_url', 2048)->nullable()->after('url');
            }
            if (!Schema::hasColumn('links', 'description')) {
                $table->text('description')->nullable()->after('normalized_url');
            }
            if (!Schema::hasColumn('links', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        Schema::table('links', function (Blueprint $table) {
            $table->index(['user_id', 'deleted_at'], 'links_user_deleted_idx');
        });
    }

    public function down(): void
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropIndex('links_user_deleted_idx');
            $table->dropSoftDeletes();
            $table->dropColumn('description');
            $table->dropColumn('normalized_url');
        });
    }
};
