<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{

    /**
     * There's a little fudging due to lack of data & time constraints.
     *
     * @return void
     */
    public function run()
    {
        $movies = json_decode(file_get_contents(__DIR__ . '/../raw/movies.json'));

        $actors = collect([]);
        $genres;
        $movies;
        
        /* insert actors */
        collect($movies->data)
            ->flatMap(function($movie) {
                return $movie->cast;
            })
            ->map(function($role) use ($actors) {
                if ($actors->contains('name', $role->actor)) {
                    return false;
                }

                $actors->push(App\Actor::create([
                    'name' => $role->actor,
                    'birth_date' => Carbon::now()->subYear(rand(20, 60))
                ]));
            });
            
        /* insert genres, data contains multiple genres per movie but we'll use one */
        $genres = collect($movies->data)
            ->flatMap(function($movie) {
                return $movie->genres;
            })
            ->unique()
            ->map(function($genre){
                return App\Genre::create([
                    'name' => $genre,
                ]);
            });
        
        /* insert movies */
        collect($movies->data)
            ->each(function($_movie) use($genres, $actors) {
                $movie = App\Movie::create([
                    'name' => $_movie->title,
                    'description' => $_movie->description,
                    'rating' => rand(30, 100) / 10
                ]);

                /* associate genre */
                $genre = $genres->where('name', $_movie->genres[0])->first();

                $movie->genre()->associate($genre);

                /* associate actors */
                collect($_movie->cast)
                    ->each(function($_role) use ($movie, $actors) {
                        if (!$_role->character) {
                            return;
                        }

                        $actor = $actors->where('name', $_role->actor)->first();

                        $castMember = App\CastMember::create([
                            'role' => $_role->character,
                            'actor_id' => $actor->id,
                            'movie_id' => $movie->id
                        ]);

                    });

                $movie->save();
            });
    }
}
