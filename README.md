# willyweather-php
A PHP client for willyweather.com.au's v2 API.

**Note: This is not an official library, nor is it fully featured.**

## Requirments
- PHP 7 or newer
- [A WillyWeather API key](http://www.willyweather.com.au/info/api.html)

## Installation
```bash
composer require owenandrews/willyweather-php
```

## Usage
### Get a location
Retrieve basic information for a given location ID.
```php
use WillyWeather\Client;

$willyWeather = new Client('<API-KEY>');
$sydney = $willyWeather->location(4950);
echo $sydney->getName();
```

### Get forecast and observational data
Retrieve a location's basic forecast and observational data.
```php
use WillyWeather\Client;

$willyWeather = new Client('<API-KEY>');
$sydney = $willyWeather->location(4950);
print_r($sydney->getForecasts());
print_r($sydney->getObservational());
```

### Get custom forecast data
By default only the 7 day 'precis' forecast is returned. To override this, just add an array of forecast types to the function call. Check out the [API documentation](http://www.willyweather.com/api/docs/v2.html) for available forecast types. Keep in mind you must enable each type for your API key, otherwise the request will fail.
```php
use WillyWeather\Client;

$willyWeather = new Client('<API-KEY>');
$sydney = $willyWeather->location(4950);
print_r($sydney->getForecasts(["forecasts" => ["temperature", "wind", "rainfallprobability"], "days" => 3]));
```

## Shorthand
So far we've recieved location, forecast and observational data, each time making a new API request. We can bundle that up into one API call, provided we know what we want ahead of time.
```php
use WillyWeather\Client;

$willyWeather = new Client('<API-KEY>');
$sydney = $willyWeather->location(4950, ["forecasts" => ["temperature", "wind", "rainfallprobability"], "days" => 3, "observational" => true]);
print_r($sydney->getForecasts());
print_r($sydney->getObservational());
```
This time only one API call was made.
