<?php require_once __DIR__ . '/../../../../includes/header.php'; ?>
<section class="movies-page">

    <div class="container">

        <div class="movies-page-heading">
            <p class="eyebrow">NOW SHOWING</p>

            <h1>Explore Our Movies</h1>

            <p>
                Discover your favourite movies and get ready
                for your next cinema experience.
            </p>
        </div>

        <!-- LIVE SEARCH BAR ADDED HERE -->
        <div class="search-container" style="margin-bottom: 30px; text-align: center;">
            <input 
                type="text" 
                id="movie-search" 
                placeholder="Search movies by title or genre..." 
                style="padding: 10px 15px; width: 100%; max-width: 400px; border-radius: 5px; border: 1px solid #ccc;"
            >
        </div>

        <!-- ADDED ID TO GRID FOR JAVASCRIPT -->
        <div class="movie-grid" id="movie-grid">

            <?php if (count($movies) > 0): ?>

                <?php foreach ($movies as $movie): ?>

                    <article class="movie-card">

                        <div class="movie-poster">

                        <?php
                            $image = $movieImages[$movie['title']] ?? '';
                        ?>

                            <?php if ($image !== ''): ?>

                                <img
                                    src="../assets/images/<?= htmlspecialchars($image) ?>"
                                    alt="<?= htmlspecialchars($movie['title']) ?>"
                                >

                            <?php else: ?>

                                <span>SCREENHOUSE</span>

                                <h3>
                                    <?= htmlspecialchars($movie['title']) ?>
                                </h3>

                            <?php endif; ?>

                        </div>

                        <div class="movie-info">

                            <h3>
                                <?= htmlspecialchars($movie['title']) ?>
                            </h3>

                            <p>
                                <?= htmlspecialchars($movie['genre']) ?>
                                <span>•</span>
                                <?= (int) $movie['duration'] ?> minutes
                            </p>
                            <a href="movie-details?id=<?= (int) $movie['movie_id'] ?>">
                                 View Showtimes →
                            </a>
                            

                        </div>

                    </article>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="empty-state" style="grid-column: 1 / -1;">
                    <h3>No Movies Available</h3>

                    <p>
                        Our movie collection is being prepared.
                        Please check back soon.
                    </p>
                </div>

            <?php endif; ?>

        </div>

    </div>

</section>

<!-- JAVASCRIPT FOR LIVE SEARCH (JSON API) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('movie-search');
    const movieGrid = document.getElementById('movie-grid');

    // Listen for typing in the search bar
    searchInput.addEventListener('input', function() {
        const query = this.value;

        // Fetch data from our new JSON API
        fetch('/movies-booking/api/movies/search?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(movies => {
                
                // Clear the current grid
                movieGrid.innerHTML = '';

                if (movies.length > 0) {
                    // Loop through the JSON response and build HTML
                    movies.forEach(movie => {
                        // Determine image (matching your PHP logic)
                        const movieImages = {
                            'Avatar': 'avatar.jpg',
                            'John Wick': 'john-wick.jpeg',
                            'The Batman': 'the-batman.jpg',
                            'Doinglife': 'doinglife.jpg',
                            'Wicked': 'wicked.jpg',
                            'Gradiator': 'gradiator.jpg'
                        };
                        
                        const image = movieImages[movie.title] || '';
                        let posterHTML = '';

                        if (image !== '') {
                            posterHTML = `<img src="../assets/images/${image}" alt="${movie.title}">`;
                        } else {
                            posterHTML = `<span>SCREENHOUSE</span><h3>${movie.title}</h3>`;
                        }

                        // Create the movie card HTML
                        const cardHTML = `
                            <article class="movie-card">
                                <div class="movie-poster">
                                    ${posterHTML}
                                </div>
                                <div class="movie-info">
                                    <h3>${movie.title}</h3>
                                    <p>${movie.genre} <span>•</span> ${movie.duration} minutes</p>
                                    <a href="movie-details?id=${movie.movie_id}">View Showtimes →</a>
                                </div>
                            </article>
                        `;

                        movieGrid.insertAdjacentHTML('beforeend', cardHTML);
                    });
                } else {
                    // Show empty state if no results
                    movieGrid.innerHTML = `
                        <div class="empty-state" style="grid-column: 1 / -1;">
                            <h3>No Movies Found</h3>
                            <p>Try searching for a different title or genre.</p>
                        </div>
                    `;
                }
            })
            .catch(error => console.error('Error fetching movies:', error));
    });
});
</script>

<?php require_once __DIR__ . '/../../../../includes/footer.php'; ?>