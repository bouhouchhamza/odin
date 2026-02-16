<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Clean existing duplicates before adding the unique index.
        $duplicates = DB::table('tags')
            ->select('name', DB::raw('MIN(id) as keep_id'), DB::raw('COUNT(*) as total'))
            ->groupBy('name')
            ->having('total', '>', 1)
            ->get();

        foreach ($duplicates as $duplicate) {
            $keepId = (int) $duplicate->keep_id;

            $extraIds = DB::table('tags')
                ->where('name', $duplicate->name)
                ->where('id', '!=', $keepId)
                ->pluck('id');

            foreach ($extraIds as $oldId) {
                $linkIds = DB::table('link_tag')
                    ->where('tag_id', $oldId)
                    ->pluck('link_id');

                foreach ($linkIds as $linkId) {
                    DB::table('link_tag')->updateOrInsert(
                        ['link_id' => $linkId, 'tag_id' => $keepId],
                        ['updated_at' => now(), 'created_at' => now()]
                    );
                }

                DB::table('link_tag')->where('tag_id', $oldId)->delete();
                DB::table('tags')->where('id', $oldId)->delete();
            }
        }

        Schema::table('tags', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    public function down(): void
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });
    }
};
