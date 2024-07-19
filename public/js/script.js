document.addEventListener('DOMContentLoaded', async function () {
    // const apiKey = $_ENV['API_SECRET_KEY'];
    // const apiKey = $api_secret_key;
    

    const responseApi = await fetch('/api/config');
    const dataKey = await responseApi.json();
    const apiKey = dataKey.api_secret_key;

// console.log(apiKey)

    const form = document.getElementById('form');
    const section = document.getElementById('meteo');

        // Fonction pour obtenir le code INSEE
        async function getInseeCode(cityName) {
            const url = `https://api.meteo-concept.com/api/location/cities?token=${apiKey}&search=${cityName}`;
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();
                if (data.cities && data.cities.length > 0) {
                    return data.cities[0].insee; // Retourne le code INSEE de la première ville trouvée
                } else {
                    throw new Error('Aucune ville trouvée');
                }
            } catch (err) {
                console.error('Error fetching the INSEE code:', err);
            }
        }

        // Fonction d'appel de l'API pour obtenir les données météorologiques
        async function getWeatherData(insee) {
            const url = `https://api.meteo-concept.com/api/ephemeride/0?token=${apiKey}&insee=${insee}`;
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                const data = await response.json();
                return data;
            } catch (err) {
                console.error('Error fetching the weather data:', err);
            }
        }

          // Gestionnaire de soumission du formulaire
          form.addEventListener('submit', async function (event) {
            event.preventDefault(); // Empêche le rechargement de la page lors de la soumission du formulaire
            // Valeur de l'input
            const cityName = document.getElementById('cityName').value;

            // Obtenir le code INSEE pour la ville
            const insee = await getInseeCode(cityName);
            section.innerHTML = ''; // Nettoyer la section avant d'y ajouter du nouveau contenu

            if (insee) {
                // Obtenir les données météorologiques pour le code INSEE
                const data = await getWeatherData(insee);
                if (data) {
                    // Créer les éléments de manière sécurisée
                    const container = document.createElement('div');
                    
                    const title = document.createElement('h3');
                    title.textContent = `Météo du jour pour ${data.city.name}`;
                    container.appendChild(title);

                    const city = document.createElement('p');
                    city.textContent = `Ville: ${data.city.name}`;
                    container.appendChild(city);

                    const date = document.createElement('p');
                    date.textContent = `Date: ${data.ephemeride.datetime}`;
                    container.appendChild(date);

                    const sunrise = document.createElement('p');
                    sunrise.textContent = `Lever du soleil: ${data.ephemeride.sunrise}`;
                    container.appendChild(sunrise);

                    const sunset = document.createElement('p');
                    sunset.textContent = `Coucher du soleil: ${data.ephemeride.sunset}`;
                    container.appendChild(sunset);

                    const tmax = document.createElement('p');
                    tmax.textContent = `Température max: ${data.ephemeride.tmax}°C`;
                    container.appendChild(tmax);

                    const tmin = document.createElement('p');
                    tmin.textContent = `Température min: ${data.ephemeride.tmin}°C`;
                    container.appendChild(tmin);

                    section.appendChild(container);

                }
            } else {
                const error = document.createElement('p');
                error.textContent = 'Ville introuvable.';
                section.appendChild(error);
            }
        });

//         // Fonction pour afficher les météos des stations
//         async function displayWeather(){
//             const inseeSkiResorts = [74080,74056, 38253, 73150];
//             const sectionSkiResort = document.getElementById('meteo-ski-resort');

//             // Boucle pour chaque code INSEE
//             for (const inseeSkiResort of inseeSkiResorts) {

//                 // const url = `https://api.meteo-concept.com/api/ephemeride/0?token=${apiKey}&insee=${inseeSkiResort}`;
           

//             const dataSki = await getWeatherData(inseeSkiResort);

//             const skiContainer = `
//                         <div class="bg-white p-4 shadow-md w-1/2">
//                             <h3> ${dataSki.city.name}</h3>
//                             <p>Date: ${dataSki.ephemeride.datetime}</p>
//                             <p>Lever du soleil: ${dataSki.ephemeride.sunrise}</p>
//                             <p>Coucher du soleil: ${dataSki.ephemeride.sunset}</p>
//                             <p>Température max: ${dataSki.ephemeride.tmax}°C</p>
//                             <p>Température min: ${dataSki.ephemeride.tmin}°C</p>
//                         </div>
//                 `;
//                 sectionSkiResort.innerHTML += skiContainer;
//         } 
//     }
//         displayWeather();

});


