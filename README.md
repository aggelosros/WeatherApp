# Weather App

## Overview

The Weather App is a web application that allows users to retrieve real-time weather data for a manually entered city. The project is designed to demonstrate a backend implementation in PHP with a React-based frontend. This application is ideal for showcasing backend development skills, especially in PHP.

## Features

### Current Features
- Fetch and display weather data for a manually entered city.
- Show dynamic weather icons based on the weather conditions.
- Caches API responses to improve performance and reduce unnecessary API calls.

### Planned Features
- **Recent Searches**: Save and retrieve a list of recently searched cities.
- **Favorite Cities**: Allow users to save and manage a list of favorite cities for quick access.
- Additional backend-focused features to enhance functionality.

## Technology Stack

### Frontend
- **Framework**: React
- **Languages**: JavaScript, HTML, CSS

### Backend
- **Language**: PHP
- **Server**: Apache (via XAMPP)
- **Directory Structure**: Backend files are stored in `weatherapp/backend`.

## Installation

### Prerequisites
- **Frontend**: Ensure you have Node.js and npm installed.
- **Backend**: Ensure you have XAMPP or a similar PHP server environment installed.

### Backend Setup
1. Move the `weatherapp/backend` directory to your PHP server's root directory (e.g., `C:/xampp/htdocs/`).
2. Start your PHP server (e.g., using XAMPP).

### Frontend Setup
1. Navigate to the React frontend directory.
2. Run the following commands:
   ```bash
   npm install
   npm start
   ```
3. Open your browser and navigate to the specified localhost URL to view the application.

## Usage

1. Enter a city's name in the input field.
2. The app will fetch and display the weather data, including temperature, condition, and an appropriate weather icon.

## Directory Structure
```
weatherapp/
|-- backend/
|   |-- index.php
|   |-- weather.php
|
|-- frontend/
|   |-- src/
|   |   |-- components/
|   |   |-- App.js
```

## Contribution
Contributions are welcome! If you find a bug or want to suggest a new feature, feel free to open an issue or submit a pull request.

## License
This project is licensed under the MIT License.

---

Enjoy using the Weather App! If you have any feedback or questions, feel free to reach out.
