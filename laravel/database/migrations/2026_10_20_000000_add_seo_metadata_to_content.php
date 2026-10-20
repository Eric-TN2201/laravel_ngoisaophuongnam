<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['categories', 'sub_categories', 'news', 'services'] as $name) {
            Schema::table($name, function (Blueprint $table) {
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['categories', 'sub_categories', 'news', 'services'] as $name) {
            Schema::table($name, fn (Blueprint $table) => $table->dropColumn(['meta_title', 'meta_description']));
        }
    }
};