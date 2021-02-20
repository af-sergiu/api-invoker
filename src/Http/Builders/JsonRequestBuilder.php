<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Http\Builders;

class JsonRequestBuilder extends BaseRequestBuilder
{
    public function setParameters(array $parameters)
    {
        $this->body = json_encode($parameters);
    }
}
