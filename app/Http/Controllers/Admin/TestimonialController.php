<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Testimonial::orderBy('id')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'role'  => 'required|string|max:255',
            'quote' => 'required|string',
            'stars' => 'required|integer|min:1|max:5',
            'image' => 'nullable|string',
        ]);

        return response()->json(Testimonial::create($data), 201);
    }

    public function update(Request $request, Testimonial $testimonial): JsonResponse
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'role'  => 'required|string|max:255',
            'quote' => 'required|string',
            'stars' => 'required|integer|min:1|max:5',
            'image' => 'nullable|string',
        ]);

        $testimonial->update($data);

        return response()->json($testimonial);
    }

    public function destroy(Testimonial $testimonial): JsonResponse
    {
        $testimonial->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
