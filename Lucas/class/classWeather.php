<?php
class Weather
{
    // OpenWeatherMap API key
    private string $apiKey = 'a6b250987f502fd417b8e97e6d19c7ef';
    private string $stad = 'Cuijk,NL';

    // Get current weather for the restaurant location
    public function getWeather(): ?array
    {
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$this->stad}&appid={$this->apiKey}&units=metric&lang=nl";

        $response = @file_get_contents($url);

        if ($response === false) {
            return null;
        }

        $data = json_decode($response, true);

        if (!isset($data['main'])) {
            return null;
        }

        return [
            'temperatuur' => round($data['main']['temp']),
            'gevoel' => round($data['main']['feels_like']),
            'omschrijving' => $data['weather'][0]['description'],
            'icoon' => $data['weather'][0]['icon'],
            'stad' => $data['name']
        ];
    }
}