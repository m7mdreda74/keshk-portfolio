<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personal_infos', function (Blueprint $table) {
            $table->string('linkedin')->nullable()->after('resume_link');
            $table->string('github')->nullable()->after('linkedin');
            $table->string('whatsapp')->nullable()->after('github');
            $table->string('telegram')->nullable()->after('whatsapp');
            $table->string('facebook')->nullable()->after('telegram');
            $table->string('instagram')->nullable()->after('facebook');
            $table->string('copyright')->nullable()->after('instagram');
        });
    }

    public function down(): void
    {
        Schema::table('personal_infos', function (Blueprint $table) {
            $table->dropColumn(['linkedin', 'github', 'whatsapp', 'telegram', 'facebook', 'instagram', 'copyright']);
        });
    }
};
