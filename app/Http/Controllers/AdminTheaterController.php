<?php

namespace App\Http\Controllers;

use App\Http\Requests\TheaterRequest;
use App\Models\Theater;
use Illuminate\Http\Request;

class AdminTheaterController extends Controller
{
    /**
     * Display a listing of theaters.
     */
    public function index()
    {
        $theaters = Theater::withCount('showtimes')
            ->orderBy('name')
            ->get();

        return view('admin.theaters.index', compact('theaters'));
    }

    /**
     * Show the form for creating a new theater hall.
     */
    public function create()
    {
        return view('admin.theaters.create');
    }

    /**
     * Store a newly created theater in storage.
     */
    public function store(TheaterRequest $request)
    {
        $theater = Theater::create($request->validated());

        return redirect()->route('admin.theaters.index')
            ->with('success', "Theater '{$theater->name}' created successfully!");
    }

    /**
     * Show the form for editing the specified theater.
     */
    public function edit($id)
    {
        $theater = Theater::withCount('showtimes')->findOrFail($id);
        return view('admin.theaters.edit', compact('theater'));
    }

    /**
     * Update the specified theater in storage.
     */
    public function update(TheaterRequest $request, $id)
    {
        $theater = Theater::findOrFail($id);
        $theater->update($request->validated());

        return redirect()->route('admin.theaters.index')
            ->with('success', "Theater '{$theater->name}' updated successfully!");
    }

    /**
     * Remove the specified theater from storage.
     */
    public function destroy($id)
    {
        $theater = Theater::withCount('showtimes')->findOrFail($id);

        if ($theater->showtimes_count > 0) {
            return back()->with('error', "Cannot delete theater '{$theater->name}' because it has active showtimes scheduled. Please delete or reassign its showtimes first.");
        }

        $name = $theater->name;
        $theater->delete();

        return redirect()->route('admin.theaters.index')
            ->with('success', "Theater '{$name}' deleted successfully!");
    }
}
