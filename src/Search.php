<?php
    namespace WillyWeather;
    
    class Search
    {
        protected $client;
        
        public function __construct(Client $client)
        {
            $this->client = $client;   
        }
        
        public function searchByQuery(string $query)
        {
            $params = ["query" => $query];
            
            $path = "search.json";
            
            // Hardcode metric units
            $parameters["units"] = "distance:km";
            
            $result = $this->client->httpClient->get($path, $params);
            
            return $this->processResult($result);
        }
        
        public function searchByCoordinates(double $lat, double $lng)
        {
            $params = ["lat" => $lat, "lng" => $lng];
            
            // Hardcode metric units
            $parameters["units"] = "distance:km";
            
            $path = "search.json";
            
            $result = $this->client->httpClient->get($path, $params);
            
            return $this->processResult($result);
        }
        
        protected function processResult(array $result) {
            foreach($result as &$location) {
                $location = new Location($this->client, $location["id"], [], $location);
            }
            
            return $result;
        }
    }
    