<?php

namespace App\Service;

class MovieService
{
    public function getBestMovieAndStars(array $movies): array
    {
        $bestMovie = null;
        $highestScore = 0;
        $moviesWithStars = [];

        foreach ($movies as $movie) {
            $score = $movie['vote_average'] * $movie['vote_count'];
            if ($score > $highestScore) {
                $highestScore = $score;
                $bestMovie = $movie;
            }

            $movie['stars'] = round($movie['vote_average'] / 2);
            $moviesWithStars[] = $movie;
        }

        return [$bestMovie, $moviesWithStars];
    }
}