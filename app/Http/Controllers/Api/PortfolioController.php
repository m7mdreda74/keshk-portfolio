<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PersonalInfo;
use App\Models\Skill;
use App\Models\Service;
use App\Models\Stat;
use App\Models\Project;
use App\Models\ResumeItem;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class PortfolioController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'personal_info' => PersonalInfo::first(),
            'skills' => Skill::all(),
            'services' => Service::all(),
            'stats' => Stat::all(),
            'projects' => Project::all(),
            'resume_items' => ResumeItem::all(),
            'testimonials' => Testimonial::all(),
        ]);
    }
}
