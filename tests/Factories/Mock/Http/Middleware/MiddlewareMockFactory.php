<?php declare(strict_types=1);

namespace AfSergiu\ApiInvoker\Tests\Factories\Mock\Http\Middleware;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MiddlewareMockFactory
{
    /**
     * @var TestCase
     */
    private $testCase;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
    }

    public function create(): MockObject
    {
        $mock = $this->testCase->getMockBuilder(\stdClass::class)
            ->addMethods(['handle'])
            ->getMock();
        $mock->method('handle')
            ->willReturn($this->testCase->returnCallback(function ()
            {
                $middlewareArgs = func_get_args();
                $nextHandler = $this->getNextHandlerFromArgs($middlewareArgs);
                $nextHandlerArguments = $this->getArgsWithoutNextHandler($middlewareArgs);
                return $nextHandler(...$nextHandlerArguments);
            })
            );
        return $mock;
    }

    private function getNextHandlerFromArgs(array $middlewareArgs): \Closure
    {
        $lastArgumentKey = array_key_last($middlewareArgs);
        return $middlewareArgs[$lastArgumentKey];
    }

    private function getArgsWithoutNextHandler(array $middlewareArgs): array
    {
        $argsCount = count($middlewareArgs);
        return array_slice($middlewareArgs, 0, $argsCount-1);
    }
}
