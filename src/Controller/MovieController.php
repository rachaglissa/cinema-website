<?php

namespace App\Controller;

use App\Service\MovieApiService;
use App\Service\MovieService; // Importez le MovieService
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class MovieController extends AbstractController
{
    private $movieApiService;
    private $movieService;

    public function __construct(MovieApiService $movieApiService, MovieService $movieService)
    {
        $this->movieApiService = $movieApiService;
        $this->movieService = $movieService;
    }

    #[Route('/', name: 'homepage')]
    public function index(Request $request): Response
    {
        $genres = $this->movieApiService->getGenres();
    
        $selectedGenreId = (int) ($request->query->get('genre_id') ?? $genres['genres'][0]['id']);
    
        // Récupérer les films par genre
        $movies = $this->movieApiService->getMoviesByGenre($selectedGenreId);
        [$bestMovie, $moviesWithStars] = $this->movieService->getBestMovieAndStars($movies['results']);
        
        // Récupérer les vidéos du meilleur film
        $videos = $this->movieApiService->getMovieVideos($bestMovie['id']);
        $trailerKey = null; // Initialiser trailerKey pour le meilleur film
        foreach ($videos as $video) {
            if ($video['site'] === 'YouTube' && $video['type'] === 'Teaser' && $video['official']) {
                $trailerKey = $video['key']; // Prendre la première bande-annonce YouTube officielle
                break;
            }
        }
    
        // Ajouter les trailers pour chaque film
        foreach ($moviesWithStars as &$movie) {
            $movieVideos = $this->movieApiService->getMovieVideos($movie['id']);
            $movie['trailerKey'] = null; // Initialiser trailerKey pour chaque film
            foreach ($movieVideos as $video) {
                if ($video['site'] === 'YouTube' && $video['type'] === 'Teaser' && $video['official']) {
                    $movie['trailerKey'] = $video['key'];
                    break;
                }
            }
        }
        
        return $this->render('cinema.html.twig', [
            'genres' => $genres['genres'],
            'bestMovie' => $bestMovie, 
            'trailerKey' => $trailerKey,
            'movies' => $moviesWithStars,
            'selectedGenreId' => $selectedGenreId,
        ]);
    }
    
    #[Route('/api/movie/{id}', name: 'movie_details')]
    public function details(int $id): Response
    {
        $movie = $this->movieApiService->getMovieDetails($id);
        return $this->json($movie);
    }

    #[Route('/api/movie/{id}/videos', name: 'movie_videos')]
    public function getMovieVideos(int $id): Response
    {
        $videos = $this->movieApiService->getMovieVideos($id);
        return $this->json($videos);
    }

    #[Route('/api/search', name: 'api_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('query');
        $movies = $this->movieApiService->searchMovies($query);

        return $this->json($movies);
    }
}
