<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PersonalInfoController extends Controller
{
    public function show(): JsonResponse
    {
        return response()->json(PersonalInfo::first());
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'profile'       => 'required|string|max:255',
            'email'         => 'required|email|max:255',
            'phone'         => 'required|string|max:50',
            'bio'           => 'required|string',
            'profile_image' => 'nullable|string',
            'hero_image'    => 'nullable|string',
            'resume_link'   => 'nullable|string',
            'linkedin'      => 'nullable|string',
            'github'        => 'nullable|string',
            'whatsapp'      => 'nullable|string',
            'telegram'      => 'nullable|string',
            'facebook'      => 'nullable|string',
            'instagram'     => 'nullable|string',
            'copyright'     => 'nullable|string',
        ]);

        $info = PersonalInfo::first();

        if ($info) {
            $info->update($data);
        } else {
            $info = PersonalInfo::create($data);
        }

        return response()->json($info);
    }
}
