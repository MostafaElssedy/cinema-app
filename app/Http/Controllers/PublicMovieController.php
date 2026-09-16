<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class PublicMovieController extends Controller
{
    public function index(Request $request)
    {
        $query = Movie::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        if ($request->filled('year')) {
            $query->where('release_year', $request->input('year'));
        }

        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->input('min_rating'));
        }

        $sort = $request->input('sort', 'latest');
        if ($sort === 'rating') {
            $query->orderBy('rating', 'desc');
        } elseif ($sort === 'year') {
            $query->orderBy('release_year', 'desc');
        } else {
            $query->latest();
        }

        $movies = $query->paginate(12)->withQueryString();
        $years = Movie::select('release_year')->distinct()->orderBy('release_year', 'desc')->pluck('release_year');
        $featuredMovie = Movie::orderBy('rating', 'desc')->first();

        return view('home', compact('movies', 'years', 'featuredMovie'));
    }

    public function show($id)
    {
        $movie = Movie::with(['showtimes' => function ($query) {
            $query->with('theater')->orderBy('show_date')->orderBy('start_time');
        }])->findOrFail($id);

        $showtimesByDate = $movie->showtimes->groupBy(function ($st) {
            return $st->show_date ? $st->show_date->format('Y-m-d') : 'Upcoming';
        });

        $relatedMovies = Movie::where('id', '!=', $movie->id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('show', compact('movie', 'relatedMovies', 'showtimesByDate'));
    }

    public function watchlist()
    {
        return view('watchlist');
    }
}
