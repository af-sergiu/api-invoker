<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Tests\Systems\Http\Middleware;

use AfSergiu\ApiInvoker\Tests\Factories\Mock\ContainerMockFactory;
use AfSergiu\ApiInvoker\Tests\Factories\Mock\Http\Middleware\MiddlewareMockFactory;
use AfSergiu\ApiInvoker\Tests\Factories\Mock\Http\RequestMockFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class MiddlewareInvoker extends TestCase
{
    /**
     * @var array
     */
    protected $containerMiddlewareEntries;
    /**
     * @var MockObject
     */
    protected $container;
    /**
     * @var MockObject
     */
    protected $request;

    public function __construct(?string $name = null, array $data = [], string $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->containerMiddlewareEntries = $this->getContainerMiddlewareEntries();
        $this->container = $this->createContainerMock();
        $this->request = $this->createRequestMock();
    }
    private function getContainerMiddlewareEntries(): array
    {
        $middlewareMockFactory = new MiddlewareMockFactory($this);
        $containerMiddlewareEntries = [
            'middleware1' => $this->createMiddlewareMockInstance($middlewareMockFactory),
            'middleware2' => $this->createMiddlewareMockInstance($middlewareMockFactory),
            'middleware3' => $this->createMiddlewareMockInstance($middlewareMockFactory)
        ];
        return $containerMiddlewareEntries;
    }

    private function createMiddlewareMockInstance(MiddlewareMockFactory $middlewareMockFactory): MockObject
    {
        return $middlewareMockFactory->create();
    }

    private function createContainerMock(): MockObject
    {
        return (new ContainerMockFactory($this))->create($this->containerMiddlewareEntries);
    }

    private function createRequestMock(): MockObject
    {
        return (new RequestMockFactory($this))->create();
    }

    protected function getMiddlewareList(): array
    {
        return array_keys($this->containerMiddlewareEntries);
    }
}
