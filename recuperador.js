const fetch = (...args) => import('node-fetch').then(({default: fetch}) => fetch(...args));
const fs = require('fs');
const path = require('path');

// Películas 

const movies = [
    'Body Heat',
    'Peeping Tom',
    'Four Lions',
    'Prizzis Honor',
    'Bridesmaids',
    'La grande bellezza',
    'LAtalante',
    'Cabaret',
    'Die Ehe der Maria Braun',
    'Strange Days',
    'The Automat',
    'The Breakfast Club',
    'Kill Bill Vol 1y2',
    'Babel',
    'The Eagle',
    'Shadows',
    'The Docks of New York',
    'Jodaeiye Nader az Simin (A Separation)',
    'King Kong',
    'The Lady From Shanghai',
    'Meshes of the Afternoon',
    'Amarcord',
    'La strategia del ragno',
    'The Jazz Singer',
    'The Dark Crystal',
    'Labyrinth',
    'Les fabuleuses aventures du legendaire Baron de Munchausen',
    'One More Time with Feeling',
    'Saving Private Ryan',
    'The Chronicle History of King Henry the Fifth with His Battell Fought at Agincourt in France (Henry V)',
    'Star Wars. Episode V: The Empire Strikes Back',
    'Tokyo monogatari',
    'Mutiny on the Bounty',
    'Zero de conduite: Jeunes diables au college',
    'Chariots of Fire',
    'The Bridges of Madison County',
    'A Fish Called Wanda',
    'When Harry Met Sally',
    'The Hangover',
    'Match Point',
    'La pelota vasca, la piel contra la piedra',
    'Fahrenheit 451',
    'Wo hu cang long (Crouching Tiger, Hidden Dragon)',
    'Paradise Now',
    'Apocalypse Now',
    'Full Metal Jacket',
    'Nuovo Cinema Paradiso',
    'All the Kings Men',
    'Mary Poppins'
];

// Dirección de OMDB y contraseña
const OMDB_ENDPOINT = 'https://www.omdbapi.com/';
const OMDB_API_KEY = 'df9707ba';

// Crear carpeta poster si no existe
const postersDir = path.join(__dirname, 'posters');
if (!fs.existsSync(postersDir)) {
    fs.mkdirSync(postersDir);
}

async function fetchPoster(movie) {
    // Formato URL
    const searchUrl = `${OMDB_ENDPOINT}?apikey=${OMDB_API_KEY}&s=${movie}&type=movie`;

    try {
        // Petición al buscador OMDB
        const response = await fetch(searchUrl);

        // Extrae el primer resultado de la búsqueda
        const { Search } = await response.json();
        if (Search && Search.length > 0) {
            const result = Search[0];

            // Formato que he elegido
            const detailsUrl = `${OMDB_ENDPOINT}?apikey=${OMDB_API_KEY}&i=${result.imdbID}&type=movie`;

            try {
                // Envía la petición de detalles
                const response = await fetch(detailsUrl);

                // Extrae la imagen
                const { Poster } = await response.json();

                // Descargar la imagen del poster
                if (Poster) {
                    const posterResponse = await fetch(Poster);
                    const posterFilename = `${movie.replace(/\s/g, '_')}.jpg`;
                    const posterPath = path.join(postersDir, posterFilename);
                    const posterStream = fs.createWriteStream(posterPath);
                    posterResponse.body.pipe(posterStream);
                    console.log(`Downloaded poster for ${movie} to ${posterPath}`);
                } else {
                    console.log(`No poster found for ${movie}`);
                }
            } catch (error) {
                console.log(`Error fetching details for ${movie}: ${error.message}`);
            }
        } else {
            console.log(`No search result found for ${movie}`);
        }
    } catch (error) {
        console.log(`Error searching for ${movie}: ${error.message}`);
    }
}

async function fetchPosters() {
    for (const movie of movies) {
        await fetchPoster(movie);
    }
}

fetchPosters().catch((error) => {
    console.error(error);
});
