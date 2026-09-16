<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Movie;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. حساب مدير النظام (Admin)
        User::firstOrCreate(
            ['email' => 'admin@cinema.com'],
            [
                'name' => 'Cinema Admin',
                'password' => Hash::make('password'),
            ]
        );

        // 2. قائمة 15 فيلم عالمي
        $movies = [
            [
                'title' => 'Inception',
                'description' => 'A thief who steals corporate secrets through the use of dream-sharing technology.',
                'release_year' => 2010,
                'rating' => 8.8,
                'image' => 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?auto=format&fit=crop&w=800&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=YoHD9XEInc0',
            ],
            [
                'title' => 'The Dark Knight',
                'description' => 'When the menace known as the Joker wreaks havoc and chaos on Gotham.',
                'release_year' => 2008,
                'rating' => 9.0,
                'image' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=800&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=EXeTwQWrcwY',
            ],
            [
                'title' => 'Interstellar',
                'description' => 'A team of explorers travel through a wormhole in space to ensure humanity survival.',
                'release_year' => 2014,
                'rating' => 8.7,
                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=zSWdZVtXT7E',
            ],
            [
                'title' => 'Oppenheimer',
                'description' => 'The story of American scientist J. Robert Oppenheimer and his role in World War II.',
                'release_year' => 2023,
                'rating' => 8.9,
                'image' => 'https://images.unsplash.com/photo-1440404653325-ab127d49abc1?auto=format&fit=crop&w=800&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=uYPbbksJxIg',
            ],
            [
                'title' => 'Dune: Part Two',
                'description' => 'Paul Atreides unites with Chani and the Fremen while seeking revenge against conspirators.',
                'release_year' => 2024,
                'rating' => 8.6,
                'image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80',
                'trailer_url' => 'https://www.youtube.com/watch?v=Way9Dexny3w',
            ],
            [
                'title' => 'Pulp Fiction',
                'description' => 'The lives of two mob hitmen, a boxer, a gangster and his wife intertwine in tales of violence.',
                'release_year' => 1994,
                'rating' => 8.9,
                'image' => 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Spider-Man: Across the Spider-Verse',
                'description' => 'Miles Morales catapults across the Multiverse, encountering a team of Spider-People.',
                'release_year' => 2023,
                'rating' => 8.7,
                'image' => 'https://images.unsplash.com/photo-1635805737707-575885ab0820?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Gladiator',
                'description' => 'A former Roman General sets out to exact vengeance against the corrupt emperor who murdered his family.',
                'release_year' => 2000,
                'rating' => 8.5,
                'image' => 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'The Matrix',
                'description' => 'A computer hacker learns from mysterious rebels about the true nature of his reality.',
                'release_year' => 1999,
                'rating' => 8.7,
                'image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Fight Club',
                'description' => 'An insomniac office worker and a devil-may-care soap maker form an underground fight club.',
                'release_year' => 1999,
                'rating' => 8.8,
                'image' => 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Forrest Gump',
                'description' => 'The history of the United States from the 1950s to the 70s unfolds through the perspective of an Alabama man.',
                'release_year' => 1994,
                'rating' => 8.8,
                'image' => 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'The Shawshank Redemption',
                'description' => 'Over the course of several years, two convicts form a friendship, seeking solace and eventual redemption.',
                'release_year' => 1994,
                'rating' => 9.3,
                'image' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Avengers: Endgame',
                'description' => 'After the devastating events of Infinity War, the universe is in ruins.',
                'release_year' => 2019,
                'rating' => 8.4,
                'image' => 'https://images.unsplash.com/photo-1607604276583-eef5d076aa5f?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Avatar',
                'description' => 'A paraplegic Marine dispatched to the moon Pandora on a unique mission becomes torn between following his orders and protecting the world.',
                'release_year' => 2009,
                'rating' => 7.9,
                'image' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Parasite',
                'description' => 'Greed and class discrimination threaten the newly formed symbiotic relationship between the wealthy Park family and the destitute Kim clan.',
                'release_year' => 2019,
                'rating' => 8.5,
                'image' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        foreach ($movies as $movieData) {
            Movie::updateOrCreate(
                ['title' => $movieData['title']],
                $movieData
            );
        }

        // 3. صالات العرض (Theaters)
        $theaters = [
            [
                'name' => 'Hall 1 - IMAX Grand',
                'type' => 'IMAX 3D',
                'rows_count' => 6,
                'seats_per_row' => 10,
            ],
            [
                'name' => 'Hall 2 - Dolby Atmos',
                'type' => 'Dolby Atmos',
                'rows_count' => 6,
                'seats_per_row' => 10,
            ],
            [
                'name' => 'Hall 3 - VIP Prestige',
                'type' => 'VIP Lounge',
                'rows_count' => 5,
                'seats_per_row' => 8,
            ],
        ];

        $createdTheaters = [];
        foreach ($theaters as $th) {
            $createdTheaters[] = \App\Models\Theater::firstOrCreate(['name' => $th['name']], $th);
        }

        // 4. مواعيد العرض (Showtimes) لكل الأفلام
        $allMovies = Movie::all();
        $today = now()->toDateString();
        $tomorrow = now()->addDay()->toDateString();

        $scheduleTemplates = [
            ['time' => '04:30 PM', 'theater_idx' => 0, 'price' => 140.00, 'date' => $today],
            ['time' => '08:00 PM', 'theater_idx' => 1, 'price' => 160.00, 'date' => $today],
            ['time' => '10:45 PM', 'theater_idx' => 2, 'price' => 220.00, 'date' => $today],
            ['time' => '06:00 PM', 'theater_idx' => 0, 'price' => 150.00, 'date' => $tomorrow],
            ['time' => '09:30 PM', 'theater_idx' => 1, 'price' => 180.00, 'date' => $tomorrow],
        ];

        foreach ($allMovies as $mov) {
            foreach ($scheduleTemplates as $st) {
                $theater = $createdTheaters[$st['theater_idx']];
                \App\Models\Showtime::firstOrCreate([
                    'movie_id' => $mov->id,
                    'theater_id' => $theater->id,
                    'show_date' => $st['date'],
                    'start_time' => $st['time'],
                ], [
                    'price' => $st['price'],
                ]);
            }
        }

        // 5. حجز تجريبي لفيلم لإظهار واقعية خريطة المقاعد المحجوزة
        $firstShowtime = \App\Models\Showtime::first();
        if ($firstShowtime && \App\Models\Booking::where('showtime_id', $firstShowtime->id)->doesntExist()) {
            $booking = \App\Models\Booking::create([
                'booking_reference' => 'CNX-' . strtoupper(substr(uniqid(), -6)),
                'showtime_id' => $firstShowtime->id,
                'customer_name' => 'Ahmed Mahmoud',
                'customer_email' => 'ahmed@example.com',
                'customer_phone' => '01012345678',
                'total_price' => $firstShowtime->price * 2,
                'status' => 'confirmed',
            ]);

            \App\Models\BookedSeat::create([
                'booking_id' => $booking->id,
                'showtime_id' => $firstShowtime->id,
                'seat_number' => 'C4',
            ]);

            \App\Models\BookedSeat::create([
                'booking_id' => $booking->id,
                'showtime_id' => $firstShowtime->id,
                'seat_number' => 'C5',
            ]);
        }
    }
}
