<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Factories\Http;

use AfSergiu\ApiInvoker\Contracts\Http\IMethod;

interface IMethodFactory
{
    /**
     * @param string $className
     * @return IMethod
     */
    public function create(string $className): IMethod;
}
