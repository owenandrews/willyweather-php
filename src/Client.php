<?php
    namespace WillyWeather;
    
    use WillyWeather\Http\HttpClient;
    
    class Client
    {   
        /**
         * httpClient
         * 
         * @var WillyWeather\Http\HttpClient
         * @access protected
         */
        public $httpClient;
        
        /**
         * __construct function.
         * 
         * @access public
         * @param string $apiKey
         * @return void
         */
        public function __construct(string $apiKey, string $cachePath = null)
        {
            $this->httpClient = new HttpClient($apiKey, $cachePath);
        }
        
        /**
         * Get a location object.
         * 
         * @access public
         * @param int $id
         * @return WillyWeather\Location
         */
        public function location(int $id, array $weather = [])
        {
            return new Location($this, $id, $weather);
        }
        
        public function searchByQuery(string $query)
        {
            return (new Search($this))->searchByQuery($query);
        }
        
        public function searchByCoordinates(double $lat, double $lng)
        {
            return (new Search($this))->searchByCoordinates($lat, $lng);
        }
    }
    