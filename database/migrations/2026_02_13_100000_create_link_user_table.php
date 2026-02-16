<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('link_user')) {
            return;
        }

        Schema::create('link_user', function (Blueprint $table) {
            $table->foreignId('link_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('permission', ['read', 'edit'])->default('read');
            $table->foreignId('shared_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['link_id', 'user_id']);
            $table->index(['user_id', 'permission']);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('link_user')) {
            return;
        }

        Schema::dropIfExists('link_user');
    }
};
