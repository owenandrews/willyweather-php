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
$sydney->getName();
```

### Get forecast and observational data
Retrieve a location's basic forecast and observational data.
```php
use WillyWeather\Client;

$willyWeather = new Client('<API-KEY>');
$sydney = $willyWeather->location(4950);
$sydney->getForecasts();
$sydney->getObservational();
```

### Get custom forecast data
By default, only the basic 7 day weather forecast is returned. To override this, just add an array of forecast types to the function call. Check out the [API documentation](http://www.willyweather.com/api/docs/v2.html) for all available forecast types. Keep in mind you must enable each forecast type for your API key, otherwise the request will fail.
```php
use WillyWeather\Client;

$willyWeather = new Client('<API-KEY>');
$sydney = $willyWeather->location(4950);
$sydney->getForecasts(["forecasts" => ["temperature", "wind", "rainfallprobability"], "days" => 3]);
```

### Shorthand
So far we've recieved location, forecast and observational data, each time making a seperate API request. Thankfully, we can bundle that up into one API call.
```php
use WillyWeather\Client;

$willyWeather = new Client('<API-KEY>');
$sydney = $willyWeather->location(4950, ["forecasts" => ["temperature", "wind", "rainfallprobability"], "days" => 3, "observational" => true]);
$sydney->getForecasts();
$sydney->getObservational();
```
This time only one API call was made.

### Search
Search for locations based on placename or postcode.
```php
use WillyWeather\Client;

$willyWeather = new Client('<API-KEY>');
$sydney = $willyWeather->searchByQuery("Sydney")[0];
```
Search for locations based on proximity to a set of coordinates.
```php
use WillyWeather\Client;

$willyWeather = new Client('<API-KEY>');
$mackenziesBay = $willyWeather->searchByCoordinates(["lat" => -33.8996141, "lng" => 151.272962])[0];
```

### Caching
A basic file-based cache is included, this can help reduce repetitive API calls. To enable it, just pass in a suitable path while constructing the client.
```php
use WillyWeather\Client;

$willyWeather = new Client('<API-KEY>', '/tmp/');
```
