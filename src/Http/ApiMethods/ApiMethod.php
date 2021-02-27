<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\ApiMethods;

use AfSergiu\ApiInvoker\Contracts\Http\IApiMethod;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestBuilder;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\IResponseReader;

abstract class ApiMethod implements IApiMethod
{
    /**
     * @var IRequestConstructor
     */
    private $requestConstructor;
    /**
     * @var IRequestInvoker
     */
    private $requestInvoker;
    /**
     * @var string
     */
    protected $httpMethod = '';
    /**
     * @var string
     */
    protected $uri = '';
    /**
     * @var array
     */
    protected $headers = [];

    public function __construct(IRequestConstructor $requestConstructor, IRequestInvoker $requestInvoker)
    {

        $this->requestConstructor = $requestConstructor;
        $this->requestInvoker = $requestInvoker;
    }

    /**
     * @param IResponseReader $responseReader
     * @return mixed
     */
    public function call(IResponseReader $responseReader)
    {
        $this->createRequest();
        $this->invokeBeforeMiddlewares();
        $this->callRequest();
        $this->invokeAfterMiddlewares();
        return $this->readRequest($responseReader);
    }

    abstract protected function createRequest(): void;

    private function invokeBeforeMiddlewares(): void
    {
        $middlewares = $this->getBeforeMiddlewares();
        $middlewareChain = $this->getCallableChain($middlewares);
        $middlewareChain->__invoke($this->request);
    }

    private function getCallableChain(array $middlewares, int $currentIdx=0): \Closure
    {
        return function (...$arguments) use ($middlewares, $currentIdx)
        {
            $middleware = $this->instantiateMiddleware($middlewares[$currentIdx]);
            if ($currentIdx === array_key_last($middlewares)) {
                return $middleware->handle(null, function(){});
            } else {
                $nextIdx = $currentIdx + 1;
                return $middleware->handle(null, $this->getCallableChain($middlewares, $nextIdx));
            }
        };
    }

    protected function getBeforeMiddlewares(): array
    {
        return [];
    }

    protected function getAfterMiddlewares(): array
    {
        return [];
    }

    public function changeRequestBuilder(IRequestBuilder $requestBuilder)
    {
        // TODO: Implement changeRequestBuilder() method.
    }

    public function changeRequestArrayBuilder(array $requestBuilder)
    {
        // TODO: Implement changeRequestArrayBuilder() method.
    }
}
