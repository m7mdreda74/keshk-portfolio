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
    // Strip oversized base64 images (legacy uncompressed uploads > ~200KB base64)
    private function safeImage(?string $img): ?string
    {
        if (!$img) return null;
        return strlen($img) > 200000 ? null : $img;
    }

    public function index(): JsonResponse
    {
        $projects = Project::all()->map(function ($p) {
            $p->image = $this->safeImage($p->image);
            return $p;
        });

        $testimonials = Testimonial::all()->map(function ($t) {
            $t->image = $this->safeImage($t->image);
            return $t;
        });

        return response()->json([
            'personal_info' => PersonalInfo::first(),
            'skills'        => Skill::all(),
            'services'      => Service::all(),
            'stats'         => Stat::all(),
            'projects'      => $projects,
            'resume_items'  => ResumeItem::all(),
            'testimonials'  => $testimonials,
        ]);
    }
}
