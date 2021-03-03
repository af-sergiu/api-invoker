# api-invoker
Library for invoke api requests

## Usage
#### Define constructor for your API


    <?php
    
    /**
     * Default request builder for this API
     */
     
    use AfSergiu\ApiInvoker\Contracts\Http\IRequestBuilder;
    
    class DefaultRequestBuilder implements IRequestBuilder
    {   
        public function setParameters(array $parameters)
        {
            $this->body = json_encode($parameters);
        }
    }
    
    <?php
    
    use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
    
    class ConceteApiRequestConstructor implements IRequestConstructor
    {
        /**
         * @var DefaultRequestBuilder 
         */
        private $defaultBuilder;
        
        public function __construct(DefaultRequestBuilder $requestBuilder)
        {
            $this->defaultBuilder = $defaultBuilder;
        }
        
        public function create(IRequestBuilder $requestBuilder): RequestInterface
        {
            $requestBuilder->setHeaders($this->getMustHaveHeaders());
            return $requestBuilder->getResult();
        }
        
        public function createByDefaultBuilder(
            string $uri, 
            array $parameters=[], 
            string $method='GET'
        ): RequestInterface {
            $this->defaultBuilder->setMethod($method);
            $this->defaultBuilder->defaultBuilder->setUri($uri);
            $this->defaultBuilder->setParameters($this->$parameters());
            $this->defaultBuilder->setHeaders($this->getMustHaveHeaders());
            return $requestBuilder->getResult();
        }
    }
#### Define concrete API method
##### Simple api method, which contains api parameters, without special builder
    <?php
    
    use AfSergiu\ApiInvoker\Http\ApiMethods\BaseApiMethod;
    use YourNamespace/functionName1;
    use YourNamespace/functionName2;
    
    class ConcreteSimpleApiMethod extends BaseApiMethod
    {
        protected $httpMethod = 'POST';
        protected $uri = 'https://your.api/method';
        protected $beforeMiddleware = [
            MiddlwareHandler::class,
            'functionName1'
        ]
        protected $afterMiddleware = [
            MiddlwareHandler1::class,
            'functionName2'
        ]
        protected $parameters = [];
        
        protected function setRequestParameters(array $parameters)
        {
            $this->parameters = [
                'apiParameter1' => $this->prepareParameter(
                    $parameters['apiParameter1']
                ),
                'apiParameter2' => $this->prepareParameter(
                    $parameters['apiParameter2']
                )
            ];
        }
    }
    
##### Api-method, which has very complicated request parameters and must have a request builder
    <?php
    
    use AfSergiu\ApiInvoker\Contracts\Http\IRequestBuilder;
    
    class ConcreteRequestBuilder implements IRequestBuilder
    {   
        public function setParameters(array $parameters)
        {
            $this->body = json_encode($parameters);
        }
    }
     

## Cook book

