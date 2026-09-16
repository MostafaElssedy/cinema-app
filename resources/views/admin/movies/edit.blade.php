@extends('layouts.admin')

@section('title', 'Edit Movie: ' . $movie->title . ' - Admin Portal')
@section('header_title', 'Edit Movie: ' . $movie->title)

@section('content')
<div class="max-w-4xl mx-auto">
    
    <!-- Top Action Bar -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-extrabold text-white tracking-tight">Edit Movie Details</h2>
            <p class="text-xs text-slate-400 mt-1">Updating movie information for ID: #{{ $movie->id }}</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 rounded-xl bg-slate-900 border border-slate-800 text-slate-300 hover:text-white font-semibold text-xs flex items-center gap-1.5">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <!-- Form Container Card -->
    <div class="admin-glass p-8 rounded-3xl shadow-2xl">
        <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.movies.form', ['movie' => $movie])
        </form>
    </div>

</div>
@endsection
