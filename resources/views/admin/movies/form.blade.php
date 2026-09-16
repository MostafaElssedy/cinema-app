@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Left Column: Movie Information -->
    <div class="space-y-5">
        <!-- Title -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Movie Title *</label>
            <input type="text" name="title" value="{{ old('title', $movie->title ?? '') }}" required placeholder="e.g. Inception"
                   class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500">
            @error('title') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Release Year & Rating -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Release Year *</label>
                <input type="number" name="release_year" value="{{ old('release_year', $movie->release_year ?? date('Y')) }}" min="1888" max="{{ date('Y') + 5 }}" required
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500">
                @error('release_year') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Rating (0 - 10) *</label>
                <input type="number" step="0.1" name="rating" value="{{ old('rating', $movie->rating ?? 8.5) }}" min="0" max="10" required
                       class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500">
                @error('rating') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Synopsis / Description *</label>
            <textarea name="description" rows="4" required placeholder="Brief movie synopsis..."
                      class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500 leading-relaxed">{{ old('description', $movie->description ?? '') }}</textarea>
            @error('description') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- YouTube Trailer URL -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">
                <i class="fa-brands fa-youtube text-red-500 mr-1"></i> YouTube Trailer Link (Optional)
            </label>
            <input type="url" name="trailer_url" value="{{ old('trailer_url', $movie->trailer_url ?? '') }}" placeholder="https://www.youtube.com/watch?v=..."
                   class="w-full bg-slate-900 border border-slate-700 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-red-500">
            <span class="text-xs text-slate-500 mt-1 block">Paste any YouTube watch or share link.</span>
            @error('trailer_url') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    <!-- Right Column: Poster Image Upload from Device -->
    <div class="space-y-5">
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Upload Poster Image from Device</label>
            <input type="file" name="image" id="image_file_input" accept="image/*" onchange="previewSelectedImage(this)"
                   class="w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-slate-200 hover:file:bg-slate-700 cursor-pointer">
            <span class="text-xs text-slate-500 mt-1 block">Supported formats: JPG, PNG, WEBP (Max: 4MB)</span>
            @error('image') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Live Poster Preview -->
        <div>
            <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Poster Preview</label>
            <div id="preview_container" class="aspect-[2/3] max-w-[200px] rounded-2xl overflow-hidden bg-slate-900 border border-slate-700/80 shadow-lg flex items-center justify-center relative">
                @if(isset($movie) && $movie->image)
                    <img id="poster_preview" src="{{ $movie->image }}" alt="Poster Preview" class="w-full h-full object-cover">
                @else
                    <img id="poster_preview" src="" alt="Poster Preview" class="w-full h-full object-cover hidden">
                    <div id="poster_placeholder" class="text-center text-slate-500 p-4">
                        <i class="fa-solid fa-image text-4xl mb-2 block text-slate-600"></i>
                        <span class="text-xs font-medium">Select an image file to preview</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- Submit & Cancel Buttons -->
<div class="mt-8 pt-6 border-t border-slate-800 flex items-center justify-end gap-4">
    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold text-sm transition-colors">
        Cancel
    </a>
    <button type="submit" class="px-8 py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-sm shadow-xl shadow-red-600/30 transition-all flex items-center gap-2">
        <i class="fa-solid fa-floppy-disk"></i> {{ isset($movie) ? 'Update Movie' : 'Save Movie' }}
    </button>
</div>

<script>
    function previewSelectedImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('poster_preview');
                const placeholder = document.getElementById('poster_placeholder');
                
                img.src = e.target.result;
                img.classList.remove('hidden');
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
