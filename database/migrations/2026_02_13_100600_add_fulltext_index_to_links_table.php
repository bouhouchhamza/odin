<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        Schema::table('links', function (Blueprint $table) {
            $table->fullText(['title', 'url'], 'links_fulltext_idx');
        });
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            return;
        }

        Schema::table('links', function (Blueprint $table) {
            $table->dropFullText('links_fulltext_idx');
        });
    }
};
