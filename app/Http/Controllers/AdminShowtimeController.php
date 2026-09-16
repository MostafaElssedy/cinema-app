<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShowtimeRequest;
use App\Models\Movie;
use App\Models\Showtime;
use App\Models\Theater;
use Illuminate\Http\Request;

class AdminShowtimeController extends Controller
{
    /**
     * Display a listing of showtimes with filtering.
     */
    public function index(Request $request)
    {
        $query = Showtime::with(['movie', 'theater', 'bookedSeats', 'bookings']);

        if ($request->filled('movie_id')) {
            $query->where('movie_id', $request->input('movie_id'));
        }

        if ($request->filled('theater_id')) {
            $query->where('theater_id', $request->input('theater_id'));
        }

        if ($request->filled('date')) {
            $query->whereDate('show_date', $request->input('date'));
        }

        $showtimes = $query->orderBy('show_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(15)
            ->withQueryString();

        $movies = Movie::orderBy('title')->get(['id', 'title']);
        $theaters = Theater::orderBy('name')->get(['id', 'name', 'type']);

        $stats = [
            'total_showtimes' => Showtime::count(),
            'upcoming_showtimes' => Showtime::whereDate('show_date', '>=', now()->toDateString())->count(),
            'total_theaters' => Theater::count(),
        ];

        return view('admin.showtimes.index', compact('showtimes', 'movies', 'theaters', 'stats'));
    }

    /**
     * Show the form for creating a new showtime.
     */
    public function create()
    {
        $movies = Movie::orderBy('title')->get();
        $theaters = Theater::orderBy('name')->get();

        return view('admin.showtimes.create', compact('movies', 'theaters'));
    }

    /**
     * Store a newly created showtime in storage.
     */
    public function store(ShowtimeRequest $request)
    {
        $showtime = Showtime::create($request->validated());

        return redirect()->route('admin.showtimes.index')
            ->with('success', "Showtime for '{$showtime->movie->title}' on {$showtime->show_date->format('M d, Y')} at {$showtime->start_time} scheduled successfully!");
    }

    /**
     * Show the form for editing the specified showtime.
     */
    public function edit($id)
    {
        $showtime = Showtime::with(['movie', 'theater'])->findOrFail($id);
        $movies = Movie::orderBy('title')->get();
        $theaters = Theater::orderBy('name')->get();

        return view('admin.showtimes.edit', compact('showtime', 'movies', 'theaters'));
    }

    /**
     * Update the specified showtime in storage.
     */
    public function update(ShowtimeRequest $request, $id)
    {
        $showtime = Showtime::findOrFail($id);
        $showtime->update($request->validated());

        return redirect()->route('admin.showtimes.index')
            ->with('success', "Showtime updated successfully!");
    }

    /**
     * Remove the specified showtime from storage.
     */
    public function destroy($id)
    {
        $showtime = Showtime::withCount('bookings')->findOrFail($id);

        if ($showtime->bookings_count > 0) {
            return back()->with('error', "Cannot delete this showtime because it already has {$showtime->bookings_count} customer reservation(s). Deleting it would compromise active tickets.");
        }

        $showtime->delete();

        return redirect()->route('admin.showtimes.index')
            ->with('success', "Showtime removed successfully!");
    }
}
