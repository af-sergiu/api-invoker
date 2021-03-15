<?php declare(strict_types=1);

/**
 * Тестирует связку из двух классов AfSergiu\ApiInvoker\Http\Constructors\RequestConstructor и
 * AfSergiu\ApiInvoker\Http\Builders\BaseRequestBuilder
 */

namespace AfSergiu\ApiInvoker\Tests\Integrations\Http\Constructors;

use AfSergiu\ApiInvoker\Contracts\Http\IRequestBuilder;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use AfSergiu\ApiInvoker\Http\Builders\BaseRequestBuilder;
use AfSergiu\ApiInvoker\Http\Constructors\RequestConstructor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RequestConstructorTest extends TestCase
{
    public function testBuildedRequestHasCorrectHttpMethod(): void
    {
        $httpMethod = 'DELETE';
        $builder = $this->getRequestBuilderMock([], function (){});
        $constructor = $this->getRequestConstructor($builder);

        $request = $constructor->create($httpMethod, '', '');

        $this->assertEquals($httpMethod, $request->getMethod());
    }

    private function getRequestBuilderMock(array $requiredHeaders, \Closure $prepareBodyParametersStrategy): MockObject
    {
        $mock = $this->getMockForAbstractClass(BaseRequestBuilder::class);
        $mock->method('getRequiredHeaders')
            ->willReturn($requiredHeaders);
        $mock->method('prepareBodyParameters')
            ->willReturnCallback($prepareBodyParametersStrategy);
        return $mock;
    }

    /**
     * @param IRequestBuilder $requestBuilder
     * @return IRequestConstructor
     */
    private function getRequestConstructor(IRequestBuilder $requestBuilder): IRequestConstructor
    {
        $requestConstructor = new RequestConstructor();
        $requestConstructor->setBuilder($requestBuilder);
        return $requestConstructor;
    }

    public function testBuildedRequestHasCorrectUri(): void
    {
        $uri = 'https://site.domain/uri';
        $builder = $this->getRequestBuilderMock([], function (){});
        $constructor = $this->getRequestConstructor($builder);

        $request = $constructor->create('POST', $uri, '');

        $this->assertEquals($uri, $request->getUri());
    }

    public function testBuildedRequestHasCorrectStringParametersInBody(): void
    {
        $stringBody = 'some string in body';
        $builder = $this->getRequestBuilderMock([], function (){});
        $constructor = $this->getRequestConstructor($builder);

        $request = $constructor->create('POST', '', $stringBody);

        $this->assertEquals($stringBody, $request->getBody()->getContents());
    }

    public function testBuildedRequestHasCorrectParametersInBody(): void
    {
        $parameters = ['param1' => 'value1'];
        $parametersHandler = function(array $parameters): string {
            return json_encode($parameters);
        };
        $builder = $this->getRequestBuilderMock([], $parametersHandler);
        $constructor = $this->getRequestConstructor($builder);

        $request = $constructor->create('POST', '', $parameters);

        $this->assertEquals($parametersHandler($parameters), $request->getBody()->getContents());
    }

    public function testBuildedRequestHasCorrectHeaders(): void
    {
        $headers = [
            'Header-One' => ['header one value']
        ];
        $addHeaders = [
            'Header-Two' => ['header two value']
        ];
        $builder = $this->getRequestBuilderMock($headers, function (){});
        $constructor = $this->getRequestConstructor($builder);
        $request = $constructor->create('POST', '', '', $addHeaders);

        $this->assertEquals(array_merge($headers, $addHeaders), $request->getHeaders());
    }
}
