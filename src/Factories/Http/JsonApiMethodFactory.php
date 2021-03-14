<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Factories\Http;

use AfSergiu\ApiInvoker\Contracts\Http\IRequestConstructor;
use AfSergiu\ApiInvoker\Http\Builders\JsonRequestBuilder;
use AfSergiu\ApiInvoker\Http\Constructors\RequestConstructor;

class JsonApiMethodFactory extends ApiMethodFactory
{
    protected function createApiRequestConstructor(): IRequestConstructor
    {
        return new RequestConstructor(
            new JsonRequestBuilder()
        );
    }
}
