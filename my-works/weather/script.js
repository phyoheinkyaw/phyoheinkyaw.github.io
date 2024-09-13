document.getElementById("getWeather").addEventListener("click", function () {
  const city = document.getElementById("city").value;
  const apiKey = 'bb56937480d8c77afbff2a4d5c210bb5';  // Replace 'YOUR_API_KEY' with your actual API key
  const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric`;

  fetch(apiUrl)
    .then(response => response.json())
    .then(data => {
      if (data.cod === 200) {
        // Show city name and country
        document.getElementById("cityName").textContent = `${data.name}, ${data.sys.country}`;

        // Show weather information
        document.getElementById("temperature").textContent = `Temperature: ${data.main.temp}°C`;
        document.getElementById("humidity").textContent = `Humidity: ${data.main.humidity}%`;
        document.getElementById("wind").textContent = `Wind: ${data.wind.speed} m/s, ${data.wind.deg}°`;
        document.getElementById("clouds").textContent = `Cloudiness: ${data.clouds.all}%`;
        document.getElementById("pressure").textContent = `Pressure: ${data.main.pressure} hPa`;
        document.getElementById("seaLevel").textContent = `Sea Level: ${data.main.sea_level} hPa`;

        // Convert Unix timestamps to readable times for sunrise and sunset
        const sunrise = new Date(data.sys.sunrise * 1000).toLocaleTimeString();
        const sunset = new Date(data.sys.sunset * 1000).toLocaleTimeString();
        document.getElementById("sunrise").textContent = `Sunrise: ${sunrise}`;
        document.getElementById("sunset").textContent = `Sunset: ${sunset}`;

        // Update the weather icon, set to visible after loading
        const iconUrl = `http://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`;
        const weatherIcon = document.getElementById("weatherIcon");
        weatherIcon.src = iconUrl;
        weatherIcon.alt = data.weather[0].description;
        weatherIcon.classList.remove("hidden");

        // Show the weather information div
        document.getElementById("weatherInfo").classList.remove("hidden");

      } else {
        alert('City not found');
      }
    })
    .catch(error => {
      console.log('Error:', error);
    });
});
