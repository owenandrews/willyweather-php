<?php
    namespace WillyWeather;
    
    use Carbon\Carbon;
    
    class Location
    {    
        /**
         * client
         * 
         * @var WillyWeather\Client
         * @access protected
         */
        protected $client;
        
        /**
         * id
         * 
         * @var int
         * @access protected
         */
        protected $id;
        
        protected $name;
        
        protected $region;
        
        protected $state;
        
        protected $postcode;
        
        protected $timeZone;
        
        protected $coordinates;
        
        protected $typeId;
        
        protected $distance;
        
        protected $forecasts;
        
        protected $observational;
        
        public function __construct(Client $client, int $id, array $weather = [], array $defaultValues = [])
        {
            $this->client = $client;
            
            if (!empty($defaultValues)) {
                $this->id = $defaultValues["id"];
                $this->processResult(["location" => $defaultValues]);
            } else {
                $this->id = $id;
                $this->fetch($weather);   
            }
        }
        
        protected function fetch(array $weather)
        {
            $params = [];
            
            if (!empty($weather)) {
                if (isset($weather["days"])){
                    $params["days"] = $weather["days"];
                }
                if (isset($weather["forecasts"])) {
                    $params["forecasts"] = implode(",", $weather["forecasts"]);
                }
                
                if (isset($weather["observationalGraphs"])) {
                    $params["observationalGraphs"] = implode(",", $weather["observationalGraphs"]);
                }

                if (isset($weather["observational"]) && $weather["observational"] == true) {
                    $params["observational"] = "true";
                }

            }
            
            // Hardcode metric units
            $params["units"] = "amount:mm,distance:km,speed:km/h,swellHeight:m,temperature:c,tideHeight:m";
            
            $path = 'locations/'.$this->id.'/weather.json';
            
            $this->processResult($this->client->httpClient->get($path, $params));
        }
        
        protected function processResult(array $result)
        {
            if(isset($result["location"])) {
                $this->name = $result["location"]["name"] ?? null;
                $this->region = $result["location"]["region"] ?? null;
                $this->state = $result["location"]["state"] ?? null;
                $this->postcode = $result["location"]["postcode"] ?? null;
                $this->timeZone = $result["location"]["timeZone"] ?? null;
                $this->coordinates = ["lat" => $result["location"]["lat"] ?? null, "lng" => $result["location"]["lng"] ?? null];
                $this->typeId = $result["location"]["typeId"] ?? null;
                $this->distance = $result["location"]["distance"] ?? null;
            }
            
            $result = $this->convertDateTimeStrings($result);
            
            if(isset($result["forecasts"])) {
                $this->forecasts = $result["forecasts"];
            }
            
            if(isset($result["observationalGraphs"])) {
                $this->observationalGraphs = $result["observationalGraphs"];
            }

            if(isset($result["observational"])) {
                $this->observational = $result["observational"];
            }
        }
        
        protected function convertDateTimeStrings(array $result)
        {
            array_walk_recursive($result, function(&$value) {
                if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/", $value)) {
                    $value = Carbon::createFromFormat('Y-m-d H:i:s', $value, $this->timeZone);
                }
            });
            
            return $result;
        }
        
        public function getName()
        {
            return $this->name;
        }
        
        public function getRegion()
        {
            return $this->region;
        }
        
        public function getState()
        {
            return $this->state;
        }
        
        public function getPostcode()
        {
            return $this->postcode;
        }
        
        public function getTimeZone()
        {
            return $this->timeZone;
        }
        
        public function getCoordinates()
        {
            return $this->coordinates;
        }
        
        public function getTypeId()
        {
            return $this->typeId;
        }
        
        public function getDistance()
        {
            return $this->distance;
        }
        
        public function getForecasts(array $weather = ["forecasts" => ["weather"]])
        {
            if (!isset($this->forecasts)) {
                $this->fetch($weather);
            }
            
            return $this->forecasts;
        }

        public function getObservationalGraphs(array $weather = ["observationalGraphs" => ["dew-point"]])
        {
            if (!isset($this->observationalGraphs)) {
                $this->fetch($weather);
            }
            
            return $this->observationalGraphs;
        }
        
        public function getObservational()
        {
            if (!isset($this->observational)) {
                $this->fetch(["observational" => true]);
            }
            
            return $this->observational;
        }
    }
