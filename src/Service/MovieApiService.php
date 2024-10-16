<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

class MovieApiService
{
    private $client;
    private $apiKey;

    public function __construct(HttpClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    private function apiRequest(string $endpoint, array $query = []): array
    {
        $query['api_key'] = $this->apiKey;

        try {
            $response = $this->client->request('GET', $endpoint, ['query' => $query]);
            return $response->toArray();
        } catch (TransportExceptionInterface $e) {
            throw new \RuntimeException('Network error: ' . $e->getMessage());
        } catch (ClientExceptionInterface $e) {
            throw new \RuntimeException('Client error: ' . $e->getMessage());
        } catch (ServerExceptionInterface $e) {
            throw new \RuntimeException('Server error: ' . $e->getMessage());
        }
    }

    public function getGenres(): array
    {
        return $this->apiRequest('https://api.themoviedb.org/3/genre/movie/list');
    }

    public function getMoviesByGenre(int $genreId): array
    {
        return $this->apiRequest('https://api.themoviedb.org/3/discover/movie', ['with_genres' => $genreId]);
    }

    public function getMovieDetails(int $movieId): array
    {
        $url = 'https://api.themoviedb.org/3/movie/' . $movieId . '?api_key=' . $this->apiKey . '&append_to_response=videos';
    
        return $this->apiRequest($url);
    }
    
    public function getMovieVideos(int $movieId): array
    {
        $videos = $this->apiRequest('https://api.themoviedb.org/3/movie/' . $movieId . '/videos');
        return array_filter($videos['results'], function ($video) {
            return $video['type'] === 'Trailer' || $video['type'] === 'Teaser';
        });
    }

    public function getBestMovieByGenre(int $genreId): array
    {
        $movies = $this->getMoviesByGenre($genreId);
        $bestMovie = null;
        $highestScore = 0;

        foreach ($movies['results'] as $movie) {
            $score = $movie['vote_average'] * $movie['vote_count'];
            if ($score > $highestScore) {
                $highestScore = $score;
                $bestMovie = $movie;
            }
        }

        $trailer = null;
        if ($bestMovie) {
            $videos = $this->getMovieVideos($bestMovie['id']);
            if (!empty($videos)) {
                $trailer = $videos[0]['key'] ?? null;
            }
        }

        return [
            'bestMovie' => $bestMovie,
            'trailer' => $trailer,
        ];
    }

    public function searchMovies(string $query): array
    {
        return $this->apiRequest('https://api.themoviedb.org/3/search/movie', ['query' => $query]);
    }
}
