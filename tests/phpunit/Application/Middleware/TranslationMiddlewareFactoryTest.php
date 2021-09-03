<?php declare(strict_types=1);

namespace Application\Middleware;

use Application\Client\TranslationClient;
use Easy\Test\FactoryTestCase;

class TranslationMiddlewareFactoryTest extends FactoryTestCase
{
    public function testResponse(): void
    {
        $this->container->expects($this->exactly(2))
            ->method('get')
            ->withConsecutive(
                [TranslationClient::class],
                ['config'],
            )
            ->willReturnOnConsecutiveCalls(
                $this->createMock(TranslationClient::class),
                ['languages' => ['de' => 'German']],
            );

        $this->assertInstanceOf(TranslationMiddleware::class, (new TranslationMiddlewareFactory())($this->container));
    }
}
