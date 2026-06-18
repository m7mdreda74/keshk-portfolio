<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Project::orderBy('id')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'description'  => 'required|string',
            'image'        => 'nullable',
            'details_link' => 'nullable|string',
        ]);

        return response()->json(Project::create($data), 201);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'description'  => 'required|string',
            'image'        => 'nullable',
            'details_link' => 'nullable|string',
        ]);

        $project->update($data);

        return response()->json($project);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
