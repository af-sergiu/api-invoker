<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Http;

interface IApiMethod
{
    public function call(IResponseReader $responseReader);

    public function changeRequestBuilder(IRequestBuilder $requestBuilder);

    public function changeRequestArrayBuilder(array $requestBuilder);
}
