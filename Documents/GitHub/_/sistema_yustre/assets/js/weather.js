// ================================================
// WEATHER WIDGET — Open-Meteo API (no key needed)
// ================================================

const WMO_CODES = {
    0:  ['☀️',  'Clear sky'],
    1:  ['🌤️', 'Mainly clear'],
    2:  ['⛅',  'Partly cloudy'],
    3:  ['☁️',  'Overcast'],
    45: ['🌫️', 'Foggy'],
    48: ['🌫️', 'Icy fog'],
    51: ['🌦️', 'Light drizzle'],
    53: ['🌦️', 'Drizzle'],
    55: ['🌧️', 'Heavy drizzle'],
    61: ['🌧️', 'Light rain'],
    63: ['🌧️', 'Rain'],
    65: ['🌧️', 'Heavy rain'],
    71: ['🌨️', 'Light snow'],
    73: ['🌨️', 'Snow'],
    75: ['❄️',  'Heavy snow'],
    80: ['🌦️', 'Rain showers'],
    81: ['🌧️', 'Heavy showers'],
    82: ['⛈️', 'Violent showers'],
    95: ['⛈️', 'Thunderstorm'],
    96: ['⛈️', 'Thunderstorm + hail'],
    99: ['⛈️', 'Thunderstorm + hail'],
};

let savedLocation = JSON.parse(localStorage.getItem('weatherLocation') || 'null');

function normalize(s) {
    return (s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

async function searchCity() {
    const query = document.getElementById('cityInput').value.trim();
    if (!query) return;

    // First token = city name sent to API; remaining tokens = client-side filter
    const tokens     = query.split(/[\s,]+/).filter(Boolean);
    const cityQuery  = tokens[0];
    const hintTokens = tokens.slice(1).map(normalize);

    const res  = await fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(cityQuery)}&count=20&language=es`);
    const data = await res.json();
    let results = data.results || [];

    if (hintTokens.length > 0) {
        results = results.filter(r => {
            const fields = normalize([r.name, r.admin1, r.admin2, r.country].filter(Boolean).join(' '));
            return hintTokens.some(h => fields.includes(h));
        });
    }

    const box = document.getElementById('cityResults');
    if (results.length === 0) {
        box.innerHTML = '<div class="city-result-item">No results found</div>';
        box.style.display = 'block';
        return;
    }
    box.innerHTML = results.slice(0, 5).map(r =>
        `<div class="city-result-item" onclick="selectCity(${r.latitude}, ${r.longitude}, '${r.name}', '${r.country || ''}')">
            📍 ${r.name}${r.admin1 ? ', ' + r.admin1 : ''}, ${r.country || ''}
        </div>`
    ).join('');
    box.style.display = 'block';
}

function selectCity(lat, lon, name, country) {
    savedLocation = { lat, lon, name, country };
    localStorage.setItem('weatherLocation', JSON.stringify(savedLocation));
    document.getElementById('cityResults').style.display = 'none';
    document.getElementById('cityInput').value = '';
    loadWeather(lat, lon, name, country);
}

async function loadWeather(lat, lon, name, country) {
    document.getElementById('weatherLocation').textContent = name + (country ? ', ' + country : '');
    document.getElementById('weatherBody').innerHTML = '<p class="placeholder-text">Loading...</p>';

    const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}` +
        '&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m,apparent_temperature' +
        '&daily=temperature_2m_max,temperature_2m_min,weather_code' +
        '&timezone=auto&forecast_days=5';
    try {
        const res  = await fetch(url);
        const data = await res.json();
        const c    = data.current;
        const d    = data.daily;
        const [icon, desc] = WMO_CODES[c.weather_code] || ['🌡️', 'Unknown'];

        const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        let forecastHTML = '';
        for (let i = 1; i < 5; i++) {
            const date    = new Date(d.time[i] + 'T12:00:00');
            const dayName = days[date.getDay()];
            const [fi]    = WMO_CODES[d.weather_code[i]] || ['🌡️'];
            forecastHTML += `
                <div class="forecast-day">
                    <div class="forecast-name">${dayName}</div>
                    <div class="forecast-icon">${fi}</div>
                    <div class="forecast-temps">
                        <span class="temp-max">${Math.round(d.temperature_2m_max[i])}°</span>
                        <span class="temp-min">${Math.round(d.temperature_2m_min[i])}°</span>
                    </div>
                </div>`;
        }

        document.getElementById('weatherBody').innerHTML = `
            <div class="weather-widget">
                <div class="weather-current">
                    <div class="weather-icon-big">${icon}</div>
                    <div class="weather-info">
                        <div class="weather-temp">${Math.round(c.temperature_2m)}°C</div>
                        <div class="weather-desc">${desc}</div>
                        <div class="weather-feels">Feels like ${Math.round(c.apparent_temperature)}°C</div>
                    </div>
                    <div class="weather-details">
                        <div class="weather-detail">💧 ${c.relative_humidity_2m}% humidity</div>
                        <div class="weather-detail">💨 ${c.wind_speed_10m} km/h wind</div>
                    </div>
                </div>
                <div class="weather-forecast">${forecastHTML}</div>
            </div>`;
    } catch (e) {
        document.getElementById('weatherBody').innerHTML =
            '<p class="placeholder-text">⚠️ Could not load weather data</p>';
    }
}

// ------------------------------------------------
// INIT on DOM ready
// ------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    let debounceTimer;
    const cityInput = document.getElementById('cityInput');

    cityInput.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        const query = cityInput.value.trim();
        if (!query) {
            document.getElementById('cityResults').style.display = 'none';
            return;
        }
        debounceTimer = setTimeout(searchCity, 350);
    });

    // Load saved city or default (Tampico)
    if (savedLocation) {
        loadWeather(savedLocation.lat, savedLocation.lon, savedLocation.name, savedLocation.country);
    } else {
        loadWeather(22.2331, -97.8614, 'Tampico', 'Mexico');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', e => {
        if (!e.target.closest('.weather-search-wrap') && !e.target.closest('#cityResults')) {
            document.getElementById('cityResults').style.display = 'none';
        }
    });
});