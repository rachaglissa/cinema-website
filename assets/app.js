let movies = []; 

function searchMovies(query) {
    const autocompleteList = document.getElementById('autocomplete-list');
    autocompleteList.innerHTML = ''; 

    if (!query) {
        return; 
    }

    const filteredMovies = movies.filter(movie => movie.title.toLowerCase().includes(query.toLowerCase()));
    updateMovieList(filteredMovies); 
}

function updateMovieList(filteredMovies) {
    const movieContainer = document.getElementById('movieContainer');
    movieContainer.innerHTML = ''; 

    if (filteredMovies.length === 0) {
        const noMoviesMessage = document.createElement('p');
        noMoviesMessage.textContent = 'Aucun film trouvé.';
        movieContainer.appendChild(noMoviesMessage); // Ajouter le message si aucun film n'est trouvé
        return;
    }

    filteredMovies.forEach(movie => {
        const movieItem = document.createElement('div');
        movieItem.classList.add('movie-item');

        const mov1Div = document.createElement('div');
        mov1Div.classList.add('mov1');

        const img = document.createElement('img');
        img.src = `https://image.tmdb.org/t/p/w500/${movie.poster_path}`;
        img.alt = movie.title;

        const movDiv = document.createElement('div');
        movDiv.classList.add('mov');

        const title = document.createElement('h4');
        title.textContent = movie.title;

        const releaseDate = document.createElement('p');
        releaseDate.textContent = `Sortie : ${movie.release_date}`;

        const overview = document.createElement('p');
        overview.textContent = movie.overview;

        const starRatingDiv = document.createElement('div');
        starRatingDiv.classList.add('star-rating');
        for (let i = 0; i < 5; i++) {
            const starSpan = document.createElement('span');
            starSpan.textContent = i < movie.stars ? '★' : '☆';
            starRatingDiv.appendChild(starSpan);
        }

        const averageRating = document.createElement('p');
        averageRating.textContent = `Note moyenne : ${movie.vote_average} / 10`;

        const button = document.createElement('button');
        button.textContent = 'Lire les détails';
        button.onclick = () => openModal(
            movie.title,
            movie.trailerKey,
            movie.overview,
            movie.stars
        );

        // Assemblez les éléments
        movDiv.appendChild(title);
        movDiv.appendChild(releaseDate);
        movDiv.appendChild(overview);
        movDiv.appendChild(starRatingDiv);
        movDiv.appendChild(averageRating);
        movDiv.appendChild(button);

        mov1Div.appendChild(img);
        mov1Div.appendChild(movDiv);
        movieItem.appendChild(mov1Div);
        movieContainer.appendChild(movieItem);
    });
}

function openModal(title, trailerKey, overview, stars) {
    document.getElementById('movieTitle').innerText = title;
    document.getElementById('movieVideo').src = "https://www.youtube.com/embed/" + trailerKey;
    document.getElementById('movieDescription').innerText = overview;

    const starContainer = document.getElementById('modalStarRating');
    starContainer.innerHTML = ''; // Clear previous stars

    for (let i = 1; i <= 5; i++) {
        const star = document.createElement('span');
        star.innerText = (i <= stars) ? '★' : '☆';
        star.classList.add('star');
        star.addEventListener('click', () => updateStarRating(i));
        starContainer.appendChild(star);
    }

    document.getElementById('movieModal').style.display = 'flex';
}

function updateStarRating(newRating) {
    const starContainer = document.getElementById('modalStarRating');
    const stars = starContainer.querySelectorAll('.star');
    stars.forEach((star, index) => {
        star.innerText = (index < newRating) ? '★' : '☆';
    });
}

function closeModal() {
    document.getElementById('movieModal').style.display = 'none';
    document.getElementById('movieVideo').src = ''; // Clear video source
}

// Charger les films après le chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    // Récupérer les films à partir de l'élément avec data-movies
    const moviesElement = document.getElementById('movie-data');
    movies = JSON.parse(moviesElement.getAttribute('data-movies')); // Utiliser la variable movies ici

    // Afficher les films dans la console pour vérifier
    console.log(movies);

    // Ajouter l'écouteur d'événements pour la recherche
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        searchMovies(query); // Appeler la fonction de recherche
    });
});

function displayFilteredMovies(filteredMovies) {
    const autocompleteList = document.getElementById('autocomplete-list');
    autocompleteList.innerHTML = ''; // Effacer les résultats précédents

    filteredMovies.forEach(movie => {
        const listItem = document.createElement('div');
        listItem.textContent = movie.title;
        listItem.classList.add('autocomplete-item');

        // Ajouter un événement de clic pour sélectionner le film
        listItem.addEventListener('click', () => {
            openModal(movie.title, movie.trailerKey, movie.overview, movie.stars);
            document.getElementById('searchInput').value = movie.title; // Optionnel : définir la valeur de l'input
            autocompleteList.innerHTML = ''; // Effacer la liste d'autocomplétion
        });

        autocompleteList.appendChild(listItem);
    });
}
