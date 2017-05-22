<?php
    namespace WillyWeather\Http;
    
    use GuzzleHttp\Psr7\Response;
    use GuzzleHttp\Client as GuzzleClient;
    use GuzzleHttp\HandlerStack;
    use Kevinrob\GuzzleCache\CacheMiddleware;
    use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
    use Kevinrob\GuzzleCache\Storage\FlysystemStorage;
    use League\Flysystem\Adapter\Local;
    
    class HttpClient
    {
        protected $baseUrl = "https://api.willyweather.com.au/v2/";
        
        protected $apiKey;
        
        protected $client;
        
        public function __construct(string $apiKey, string $cachePath = null)
        {
            $this->apiKey = $apiKey;
            $this->cachePath = $cachePath;
            $this->client = new GuzzleClient(["handler" => $this->getHandlerStack($cachePath)]);
        }
        
        /**
         * Perform get request.
         * 
         * @access public
         * @param string $path
         * @param array $parameters (default: [])
         * @return array
         */
        public function get(string $path, array $parameters = [])
        {   
            $response = $this->client->request("GET", $this->baseUrl.$this->apiKey."/".$path, ["query" => $parameters]);
            
            return $this->processResponse($response);
        }
        
        /**
         * processResponse function.
         * 
         * @access protected
         * @param Response $response
         * @return array
         */
        protected function processResponse(Response $response)
        {
            $body = $response->getBody(true);
            
            return json_decode($body, true);
        }
    
        /**
         * Return middleware handler stack with cacheing middleware added.
         * 
         * @access protected
         * @return GuzzleHttp\HandlerStack
         */
        protected function getHandlerStack(string $cachePath)
        {
            $stack = HandlerStack::create();
            
            if (isset($cachePath)) {
                $stack->push(new CacheMiddleware(
                    new GreedyCacheStrategy(
                        new FlysystemStorage(
                            new Local($cachePath)
                        ),
                        (60 * 60 * 3) // TTL 3 hours
                    )
                ), "cache");
            }
            
            return $stack;
        }
    }
    