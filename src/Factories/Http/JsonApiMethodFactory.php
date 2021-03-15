<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Factories\Http;

use AfSergiu\ApiInvoker\Contracts\Http\IRequestBuilder;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Http\Builders\JsonRequestBuilder;
use AfSergiu\ApiInvoker\Http\Invokers\GuzzleExceptionsAdapter;
use AfSergiu\ApiInvoker\Http\Invokers\GuzzleInvoker;
use GuzzleHttp\Client;

class JsonApiMethodFactory extends MethodFactory
{
    function createRequestBuilder(): IRequestBuilder
    {
        return new JsonRequestBuilder();
    }

    protected function createApiInvoker(): IRequestInvoker
    {
        return new GuzzleInvoker(new Client(), new GuzzleExceptionsAdapter());
    }
}
