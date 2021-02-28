<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Factories\Http;

use AfSergiu\ApiInvoker\Contracts\Http\IApiMethod;

interface IApiMethodFactory
{
    /**
     * @param string $className
     * @return IApiMethod
     */
    public function create(string $className): IApiMethod;
}
