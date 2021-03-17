<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Factories\Http\Invokers;

use AfSergiu\ApiInvoker\Contracts\Exceptions\IExceptionsAdapter;
use AfSergiu\ApiInvoker\Contracts\Factories\Http\IInvokerFactory;
use AfSergiu\ApiInvoker\Contracts\Http\IRequestInvoker;
use AfSergiu\ApiInvoker\Http\Invokers\GuzzleExceptionsAdapter;
use AfSergiu\ApiInvoker\Http\Invokers\GuzzleInvoker;
use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;

class GuzzleInvokerFactory implements IInvokerFactory
{
    public function create(): IRequestInvoker
    {
        return new GuzzleInvoker(
            $this->getGuzzleClient(),
            $this->getExceptionAdapter()
        );
    }

    private function getGuzzleClient(): ClientInterface
    {
        return new Client($this->getGuzzleConfig());
    }

    private function getGuzzleConfig(): array
    {
        return [];
    }

    private function getExceptionAdapter(): IExceptionsAdapter
    {
        return new GuzzleExceptionsAdapter();
    }

}
