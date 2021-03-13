# api-invoker
Library for invoke api requests

## Usage
Для организации работы с api нам необходиом создать конструктор запросов `AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor`, необходимые нам методы api `AfSergiu\ApiInvoker\Contracts\Http\IResponseReader` и ридеры к ним `AfSergiu\ApiInvoker\Contracts\Http\IResponseReader`.

Конструктор запросов, отвечает за управление конструированием запроса api, вызывая методы билдера, он устанавливает параметры, характерные для всех запросов к api: http-аутентификацию, заголовки, порядок установки параметров в тело запроса (xml, json и т.п.). Конструктор должен реализовывать интерфейс `AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor`.

Конструктор должен реализовывать два метода:
 * `create(Psr\Http\Message\RequestInterface\IRequestBuilder $requestBuilder): Psr\Http\Message\RequestInterface` - создает запрос с помощью конкретного билдера запроса, где билдер содержит уже установленный uri, метод запроса и параметры запроса. Конструктор только установливает базовые параметры, указанные выше;
 * `сreateByDefaultBuilder(string $uri, array $parameters=[], string $method='GET'): Psr\Http\Message\RequestInterface` - создает запрос с помощью базового билдера `AfSergiu\ApiInvoker\Http\Builders\BaseRequestBuilder`, где uri метод запроса и параметры передаются, как аргументы. Если запрос использует подпись, то она формируется в этом методе
 
 
 
#### Define constructor for your API
    
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
        ];
        protected $afterMiddleware = [
            MiddlwareHandler1::class,
            'functionName2'
        ];
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

