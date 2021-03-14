<?php declare(strict_types=1);
/**
 * Конструирует json Запрос
 */

namespace AfSergiu\ApiInvoker\Http\Builders;


class JsonRequestBuilder extends BaseRequestBuilder
{
    protected function getRequireHeaders(): array
    {
        return [
            'Content-Type' => 'text/json'
        ];
    }

    protected function prepareBodyParameters(array $parameters): string
    {
        return json_encode($parameters);
    }
}
