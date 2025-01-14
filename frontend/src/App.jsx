import React, { useState, useEffect } from "react";
import axios from "axios";
import "./App.css";

function App() {
  const [city, setCity] = useState("");
  const [weather, setWeather] = useState(null);
  const [favorites, setFavorites] = useState([]);
  const [error, setError] = useState("");
  const [recentSearches, setRecentSearches] = useState([]);

  useEffect(() => {
    fetchFavorites();
  }, []);

  useEffect(() => {
    if (weather) {
      const condition = weather.weather[0].main.toLowerCase();
      document.body.className = condition; // Set the class based on weather
    }
  }, [weather]);

  useEffect(() => {
    fetchRecentSearches();
  }, []);

  const fetchRecentSearches = async () => {
    try {
      const response = await axios.get(
        `${import.meta.env.VITE_BACKEND_URL}/recent_searches.php`
      );
      setRecentSearches(response.data);
    } catch (error) {
      console.error("Error fetching recent searches:", error);
    }
  };

  const fetchWeather = async (cityName = city) => {
    try {
      const response = await axios.get(
        `${import.meta.env.VITE_BACKEND_URL}/weather.php?city=${cityName}`
      );
      if (response.data.cod === "404") {
        setError("City not found! Please try again.");
        setWeather(null);
      } else {
        setError("");
        setWeather(response.data);

        // Save the search to recent searches
        await axios.post(
          `${import.meta.env.VITE_BACKEND_URL}/recent_searches.php`,
          {
            city: cityName,
          }
        );
      }
    } catch (error) {
      console.error("Error fetching weather data:", error);
      setError(
        "An error occurred while fetching the weather data. Please try again."
      );
      fetchRecentSearches(); // Refresh recent searches
    }
  };

  const saveFavorite = async (city) => {
    try {
      const response = await axios.post(
        `${import.meta.env.VITE_BACKEND_URL}/favorites.php`,
        { city }
      );
      if (response.data.status === "success") {
        fetchFavorites();
      } else if (response.data.status === "exists") {
        alert(`${city} is already in favorites.`);
      }
    } catch (error) {
      console.error("Error saving favorite city:", error);
    }
  };

  const fetchFavorites = async () => {
    try {
      const response = await axios.get(
        `${import.meta.env.VITE_BACKEND_URL}/favorites.php`
      );
      setFavorites(response.data);
    } catch (error) {
      console.error("Error fetching favorite cities:", error);
    }
  };

  return (
    <div>
      <h1>Weather App</h1>
      <div>
        <input
          type="text"
          placeholder="Enter city name"
          value={city}
          onChange={(e) => setCity(e.target.value)}
        />
        <button onClick={() => fetchWeather(city)}>Get Weather</button>
        <button
          onClick={() => weather && saveFavorite(weather.name)}
          disabled={!weather}
          className={!weather ? "disabled" : ""}
        >
          Save as Favorite
        </button>
        {error && <p style={{ color: "red" }}>{error}</p>}
      </div>

      <div className="recent-searches">
        <h2>Recent Searches</h2>
        <ul>
          {recentSearches.map((search, index) => (
            <li key={index} onClick={() => fetchWeather(search)}>
              {search}
            </li>
          ))}
        </ul>
      </div>

      {weather && (
        <div className="weather-details">
          <h2>{weather.name}</h2>
          <img
            src={`https://openweathermap.org/img/wn/${weather.weather[0].icon}@2x.png`}
            alt={weather.weather[0].description}
          />
          <p>Temperature: {Math.round(weather.main.temp - 273.15)}°C</p>
          <p>Weather: {weather.weather[0].description}</p>
          <p>Wind Speed: {weather.wind.speed} m/s</p>
          <p>Humidity: {weather.main.humidity}%</p>
          <p>Visibility: {weather.visibility / 1000} km</p>
        </div>
      )}

      <div className="favorites">
        <h2>Favorite Cities</h2>
        <ul>
          {favorites.map((fav, index) => (
            <li key={index} onClick={() => fetchWeather(fav)}>
              {fav}
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}

export default App;
