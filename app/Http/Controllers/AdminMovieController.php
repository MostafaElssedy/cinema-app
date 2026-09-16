<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Models\Movie;
use App\Models\Watchlist;
use Illuminate\Http\Request;

class AdminMovieController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = Movie::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('release_year', 'like', "%{$search}%");
        }

        $movies = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total_movies' => Movie::count(),
            'avg_rating' => round(Movie::avg('rating') ?? 0, 1),
            'latest_year' => Movie::max('release_year') ?? date('Y'),
            'total_watchlist' => Watchlist::count(),
            'total_bookings' => \App\Models\Booking::count(),
            'total_revenue' => \App\Models\Booking::where('status', 'confirmed')->sum('total_price'),
        ];

        $recentBookings = \App\Models\Booking::with(['showtime.movie', 'showtime.theater', 'bookedSeats'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact('movies', 'stats', 'recentBookings'));
    }

    public function create()
    {
        return view('admin.movies.create');
    }

    public function store(MovieRequest $request)
    {
        $validated = $request->validated();

        $imagePath = 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=800&q=80';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
            $imagePath = '/images/' . $imageName;
        }

        Movie::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'release_year' => $validated['release_year'],
            'rating' => $validated['rating'],
            'image' => $imagePath,
            'trailer_url' => $validated['trailer_url'] ?? null,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Movie created successfully!');
    }

    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        return view('admin.movies.edit', compact('movie'));
    }

    public function update(MovieRequest $request, $id)
    {
        $movie = Movie::findOrFail($id);
        $validated = $request->validated();

        $imagePath = $movie->image;

        if ($request->hasFile('image')) {
            if ($movie->image && str_starts_with($movie->image, '/images/') && file_exists(public_path($movie->image))) {
                @unlink(public_path($movie->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->extension();
            $image->move(public_path('images'), $imageName);
            $imagePath = '/images/' . $imageName;
        }

        $movie->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'release_year' => $validated['release_year'],
            'rating' => $validated['rating'],
            'image' => $imagePath,
            'trailer_url' => $validated['trailer_url'] ?? null,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Movie updated successfully!');
    }

    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);

        if ($movie->image && str_starts_with($movie->image, '/images/') && file_exists(public_path($movie->image))) {
            @unlink(public_path($movie->image));
        }

        $movie->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Movie deleted successfully!');
    }
}
