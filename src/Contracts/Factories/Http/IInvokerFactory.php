<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Contracts\Factories\Http;

use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;

interface IInvokerFactory
{
    public function create(): IRequestInvoker;
}
