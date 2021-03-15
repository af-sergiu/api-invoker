<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Methods;

use AfSergiu\ApiInvoker\Contracts\Http\IMethod;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use AfSergiu\ApiInvoker\Contracts\Http\IResponseReader;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddlewareInvoker;
use AfSergiu\ApiInvoker\Contracts\IArrayStructureBuilder;
use AfSergiu\ApiInvoker\Http\Invokers\BaseRequestInvoker;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IBeforeMiddleware;
use AfSergiu\ApiInvoker\Contracts\Http\Middleware\IAfterMiddleware;

abstract class BaseMethod implements IMethod
{
    /**
     * @var string
     */
    protected $httpMethod = 'GET';
    /**
     * @var string
     */
    protected $uri = '';
    /**
     * @var array
     */
    protected $addHeaders=[];
    /**
     * @var array<\Closure|IBeforeMiddleware>
     */
    protected $beforeMiddleware = [];
    /**
     * @var array<\Closure|IAfterMiddleware>
     */
    protected $afterMiddleware = [];
    /**
     * @var mixed
     */
    private $parameters = '';
    /**
     * @var IRequestConstructor
     */
    private $requestConstructor;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var BaseRequestInvoker
     */
    private $requestInvoker;
    /**
     * @var IBeforeMiddlewareInvoker
     */
    private $beforeMiddlewareInvoker;
    /**
     * @var IAfterMiddlewareInvoker
     */
    private $afterMiddlewareInvoker;
    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(
        IRequestConstructor $requestConstructor,
        BaseRequestInvoker $requestInvoker,
        IBeforeMiddlewareInvoker $beforeMiddlewareInvoker,
        IAfterMiddlewareInvoker $afterMiddlewareInvoker
    ) {
        $this->requestConstructor = $requestConstructor;
        $this->requestInvoker = $requestInvoker;
        $this->beforeMiddlewareInvoker = $beforeMiddlewareInvoker;
        $this->afterMiddlewareInvoker = $afterMiddlewareInvoker;
    }

    final public function setParameters($parameters): IMethod
    {
        if ($parameters instanceof IArrayStructureBuilder) {
            $this->parameters = $parameters->build();
        } else {
            $this->parameters = $parameters;
        }
        return $this;
    }

    /**
     * @param IResponseReader $responseReader
     * @return mixed
     */
    final public function call(IResponseReader $responseReader)
    {
        $this->request = $this->createRequest();
        $this->invokeBeforeMiddleware();
        $this->response = $this->callRequest();
        $this->invokeAfterMiddleware();
        return $this->readRequest($responseReader);
    }

    protected function createRequest(): RequestInterface
    {
        return $this->requestConstructor->create(
            $this->httpMethod,
            $this->uri,
            $this->parameters,
            $this->headers
        );
    }

    private function invokeBeforeMiddleware(): void
    {
        $this->beforeMiddlewareInvoker->createChain($this->beforeMiddleware);
        $this->beforeMiddlewareInvoker->invokeChain($this->request);
    }

    private function callRequest(): ResponseInterface
    {
        return $this->requestInvoker->invoke($this->request);
    }

    private function invokeAfterMiddleware(): void
    {
        $this->afterMiddlewareInvoker->createChain($this->afterMiddleware);
        $this->afterMiddlewareInvoker->invokeChain($this->response, $this->request);
    }

    protected function getAfterMiddleware(): array
    {
        return [];
    }

    private function readRequest(IResponseReader $responseReader)
    {
        $responseReader->read($this->response);
    }
}
