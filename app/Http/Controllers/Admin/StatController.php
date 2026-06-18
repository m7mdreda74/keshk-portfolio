<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StatController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Stat::orderBy('id')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'count' => 'required|integer|min:0',
        ]);

        return response()->json(Stat::create($data), 201);
    }

    public function update(Request $request, Stat $stat): JsonResponse
    {
        $data = $request->validate([
            'label' => 'required|string|max:255',
            'count' => 'required|integer|min:0',
        ]);

        $stat->update($data);

        return response()->json($stat);
    }

    public function destroy(Stat $stat): JsonResponse
    {
        $stat->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
