<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    protected $table = 'personal_infos';
    protected $fillable = [
        'name', 'profile', 'email', 'phone', 'bio',
        'profile_image', 'hero_image', 'resume_link',
        'linkedin', 'github', 'whatsapp', 'telegram', 'facebook', 'instagram',
        'copyright'
    ];
}
