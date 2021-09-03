<?php declare(strict_types=1);

namespace Application\Handler;

use Easy\Test\FactoryTestCase;

class IndexHandlerFactoryTest extends FactoryTestCase
{
    public function testResponse(): void
    {
        $this->container->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn(['languages' => ['de' => 'German']]);

        $this->assertInstanceOf(IndexHandler::class, (new IndexHandlerFactory())($this->container));
    }
}
