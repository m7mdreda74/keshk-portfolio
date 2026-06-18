<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SkillController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Skill::orderBy('id')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'percentage' => 'required|integer|min:0|max:100',
        ]);

        return response()->json(Skill::create($data), 201);
    }

    public function update(Request $request, Skill $skill): JsonResponse
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'percentage' => 'required|integer|min:0|max:100',
        ]);

        $skill->update($data);

        return response()->json($skill);
    }

    public function destroy(Skill $skill): JsonResponse
    {
        $skill->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
