# Cinema Website

Ce projet est un site web de cinéma similaire à Allociné, développé avec Symfony 6 et utilisant l'API TheMovieDB pour récupérer les données sur les films.

### Key Features

- **Create a Symfony application**: The application is built on the Symfony framework, allowing for robust and scalable web application development.

- **Manage API authentication & logic with Symfony**: The application includes secure API authentication methods to manage access to data, ensuring that only authorized requests can interact with the API endpoints.

- **Use and manipulate JSON API data**: The application communicates with external movie databases using JSON API. Data manipulation, such as fetching movie details, ratings, and reviews, is handled seamlessly.

- **Implement algorithm with DRY principle**: All algorithms within the application follow the DRY (Don't Repeat Yourself) principle, promoting code reusability and maintainability.

- **Manage assets with Webpack**: Webpack is used to manage and bundle application assets, including stylesheets and JavaScript files, ensuring optimized loading times and efficient resource management.

- **Use Docker and Docker-Compose**: The application is containerized using Docker, allowing for easy setup and deployment. Docker Compose is utilized for orchestrating multi-container applications.

- **Create HTML template with a clean design using stylesheets**: The frontend features a clean and responsive design, implemented with modern HTML and CSS stylesheets, ensuring a user-friendly experience.

- **User registration & login is not required**: This application does not require user registration or login. Users can browse movies and submit ratings without needing to create an account.

## Prérequis

- PHP 8.1+
- Composer 2.8+
- Symfony 6
- Docker
- Node.js et npm (pour gérer Webpack)

## Installation
composer install
npm install
docker-compose up -d

### Explication des commandes Webpack :
- `npm run dev` : Compile les fichiers pour le développement avec des fichiers non minifiés et avec des sourcemaps.
- `npm run dev --watch` : Surveille les fichiers pour détecter toute modification et recompiler automatiquement.
- `npm run build` : Optimise les fichiers pour la production (minifiés, sans sourcemaps) pour une performance maximale.

Ce `README.md` donne une vue complète de ton projet et permet de reproduire facilement l'environnement de développement.