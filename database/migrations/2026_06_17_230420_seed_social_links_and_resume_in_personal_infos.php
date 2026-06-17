<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('personal_infos')
            ->whereNull('resume_link')
            ->orWhereNull('linkedin')
            ->update([
                'resume_link' => DB::raw("COALESCE(resume_link, 'https://drive.google.com/file/d/1tUSqTA0OpZpJMyQ2YsC132RfSmYnycxh/view?usp=sharing')"),
                'linkedin'    => DB::raw("COALESCE(linkedin,    'https://www.linkedin.com/in/m0hamed-keshk')"),
                'github'      => DB::raw("COALESCE(github,      'https://github.com/m7mdreda74')"),
                'whatsapp'    => DB::raw("COALESCE(whatsapp,    'http://wa.me/201099670724')"),
                'telegram'    => DB::raw("COALESCE(telegram,    'http://t.me/m7md_reda74')"),
                'facebook'    => DB::raw("COALESCE(facebook,    'https://www.facebook.com/share/1JNP9vrxzq/')"),
                'instagram'   => DB::raw("COALESCE(instagram,   'https://www.instagram.com/m7md_reda74')"),
                'copyright'   => DB::raw("COALESCE(copyright,   '© 2026 Mohamed Reda Keshk. All Rights Reserved.')"),
            ]);
    }

    public function down(): void
    {
        // no rollback needed for seed data
    }
};
