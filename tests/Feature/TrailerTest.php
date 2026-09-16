<?php

namespace Tests\Feature;

use App\Models\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrailerTest extends TestCase
{
    use RefreshDatabase;

    public function test_movie_embed_url_accessor_formats_youtube_url(): void
    {
        $movie = Movie::create([
            'title' => 'Inception',
            'description' => 'A thief who steals corporate secrets through dream-sharing tech.',
            'release_year' => 2010,
            'rating' => 8.8,
            'image' => 'https://example.com/inception.jpg',
            'trailer_url' => 'https://www.youtube.com/watch?v=YoHD9XEInc0',
        ]);

        $this->assertStringContainsString('youtube.com/embed/YoHD9XEInc0', $movie->trailer_embed_url);
        $this->assertStringContainsString('autoplay=1', $movie->trailer_embed_url);
    }

    public function test_movie_embed_url_fallback_when_no_trailer_url_provided(): void
    {
        $movie = Movie::create([
            'title' => 'Interstellar',
            'description' => 'A team of explorers travel through a wormhole in space.',
            'release_year' => 2014,
            'rating' => 8.7,
            'image' => 'https://example.com/interstellar.jpg',
            'trailer_url' => null,
        ]);

        $this->assertStringContainsString('youtube.com/embed?listType=search', $movie->trailer_embed_url);
        $this->assertStringContainsString('Interstellar', $movie->trailer_embed_url);
    }

    public function test_movie_page_renders_watch_trailer_button(): void
    {
        $movie = Movie::create([
            'title' => 'The Dark Knight',
            'description' => 'Batman faces the Joker in Gotham.',
            'release_year' => 2008,
            'rating' => 9.0,
            'image' => 'https://example.com/darkknight.jpg',
            'trailer_url' => 'https://youtu.be/EXeTwQWrcwY',
        ]);

        $response = $this->get(route('movies.show', $movie->id));
        $response->assertStatus(200);
        $response->assertSee('Watch Trailer');
        $response->assertSee('trailer-modal');
    }
}
