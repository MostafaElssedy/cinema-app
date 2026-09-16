<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieApiController extends Controller
{
    // GET /api/movies
    public function index(Request $request)
    {
        $query = Movie::query();

        // 1. Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('release_year', 'like', "%{$search}%");
            });
        }

        // 2. Year Filter
        if ($request->filled('year')) {
            $query->where('release_year', $request->input('year'));
        }

        // 3. Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'rating') {
            $query->orderBy('rating', 'desc');
        } elseif ($sort === 'oldest') {
            $query->orderBy('release_year', 'asc');
        } elseif ($sort === 'newest') {
            $query->orderBy('release_year', 'desc');
        } else {
            $query->latest();
        }

        $perPage = $request->input('per_page', 15);
        $movies = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Movies fetched successfully.',
            'data' => MovieResource::collection($movies->items()),
            'pagination' => [
                'total' => $movies->total(),
                'count' => $movies->count(),
                'per_page' => $movies->perPage(),
                'current_page' => $movies->currentPage(),
                'total_pages' => $movies->lastPage(),
            ]
        ], 200);
    }

    // GET /api/movies/{id}
    public function show($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json([
                'success' => false,
                'message' => 'Movie not found.',
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Movie details fetched successfully.',
            'data' => [
                'movie' => new MovieResource($movie),
            ]
        ], 200);
    }
}
