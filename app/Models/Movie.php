<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'release_year',
        'rating',
        'image',
        'trailer_url',
    ];
    // convert data types
    protected $casts = [
        'release_year' => 'integer',
        'rating' => 'float',
    ];

    protected $appends = [
        'trailer_embed_url',
    ];
    // relation between movie and watchlist
    public function watchlists(): HasMany
    {
        return $this->hasMany(Watchlist::class);
    }

    // relation between movie and showtimes
    public function showtimes(): HasMany
    {
        return $this->hasMany(Showtime::class);
    }

    /**
     * Get responsive YouTube embed URL with autoplay enabled.
     */
    public function getTrailerEmbedUrlAttribute(): string
    {
        if (!empty($this->trailer_url)) {
            if (str_contains($this->trailer_url, 'youtube.com/embed/')) {
                return $this->trailer_url . (str_contains($this->trailer_url, '?') ? '&autoplay=1' : '?autoplay=1');
            }

            // Extract 11-char YouTube ID from watch, share, or short link
            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $this->trailer_url, $matches)) {
                return "https://www.youtube.com/embed/{$matches[1]}?autoplay=1&rel=0";
            }

            return $this->trailer_url;
        }

        // Fallback trailer: Official movie trailer query
        $query = urlencode($this->title . ' official trailer ' . $this->release_year);
        return "https://www.youtube.com/embed?listType=search&list={$query}&autoplay=1";
    }
}
