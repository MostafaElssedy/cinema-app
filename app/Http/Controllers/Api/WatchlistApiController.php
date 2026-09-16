<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\WatchlistRequest;
use App\Http\Resources\WatchlistResource;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class WatchlistApiController extends Controller
{
    // GET /api/watchlist?device_id=XXXX
    public function index(Request $request)
    {
        $deviceId = $request->input('device_id') ?? $request->header('X-Device-Id');

        if (!$deviceId) {
            return response()->json([
                'success' => false,
                'message' => 'device_id is required as query parameter or X-Device-Id header.',
            ], 422);
        }

        $watchlistItems = Watchlist::with('movie')
            ->where('device_id', $deviceId)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Watchlist items fetched successfully.',
            'device_id' => $deviceId,
            'count' => $watchlistItems->count(),
            'data' => WatchlistResource::collection($watchlistItems),
        ], 200);
    }

    // POST /api/watchlist
    public function store(WatchlistRequest $request)
    {
        $validated = $request->validated();

        $item = Watchlist::firstOrCreate([
            'device_id' => $validated['device_id'],
            'movie_id' => $validated['movie_id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Movie added to watchlist successfully.',
            'data' => new WatchlistResource($item->load('movie')),
        ], 201);
    }

    // DELETE /api/watchlist
    public function destroy(WatchlistRequest $request)
    {
        $validated = $request->validated();

        $deleted = Watchlist::where('device_id', $validated['device_id'])
            ->where('movie_id', $validated['movie_id'])
            ->delete();

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Movie removed from watchlist successfully.',
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Movie not found in watchlist.',
        ], 404);
    }
}
