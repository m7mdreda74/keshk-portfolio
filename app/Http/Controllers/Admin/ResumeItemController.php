<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResumeItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ResumeItemController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ResumeItem::orderBy('id')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'         => 'required|in:education,experience',
            'title'        => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'duration'     => 'required|string|max:100',
            'description'  => 'required|string',
        ]);

        return response()->json(ResumeItem::create($data), 201);
    }

    public function update(Request $request, ResumeItem $resumeItem): JsonResponse
    {
        $data = $request->validate([
            'type'         => 'required|in:education,experience',
            'title'        => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'duration'     => 'required|string|max:100',
            'description'  => 'required|string',
        ]);

        $resumeItem->update($data);

        return response()->json($resumeItem);
    }

    public function destroy(ResumeItem $resumeItem): JsonResponse
    {
        $resumeItem->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
