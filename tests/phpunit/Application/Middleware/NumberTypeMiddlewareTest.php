<?php declare(strict_types=1);

namespace Application\Middleware;

use Easy\Test\MiddlewareTestCase;

class NumberTypeMiddlewareTest extends MiddlewareTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new NumberTypeMiddleware();
    }

    public function testWithShortUri(): void
    {
        $this->request->expects($this->once())
            ->method('getServerParams')
            ->willReturn(['REQUEST_URI' => '/math']);

        $this->request->expects($this->once())
            ->method('withAttribute')
            ->with('type', 'math')
            ->willReturnSelf();

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request);

        $this->middleware->process($this->request, $this->handler);
    }

    public function testWithNumber(): void
    {
        $this->request->expects($this->once())
            ->method('getServerParams')
            ->willReturn(['REQUEST_URI' => '/year/12']);

        $this->request->expects($this->once())
            ->method('withAttribute')
            ->with('type', 'year')
            ->willReturnSelf();

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request);

        $this->middleware->process($this->request, $this->handler);
    }

    public function testWithDate(): void
    {
        $this->request->expects($this->once())
            ->method('getServerParams')
            ->willReturn(['REQUEST_URI' => '/date/10/3']);

        $this->request->expects($this->once())
            ->method('withAttribute')
            ->with('type', 'date')
            ->willReturnSelf();

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request);

        $this->middleware->process($this->request, $this->handler);
    }

    public function testWithShortUriAndParameter(): void
    {
        $this->request->expects($this->once())
            ->method('getServerParams')
            ->willReturn(['REQUEST_URI' => '/math?language=de']);

        $this->request->expects($this->once())
            ->method('withAttribute')
            ->with('type', 'math')
            ->willReturnSelf();

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request);

        $this->middleware->process($this->request, $this->handler);
    }

    public function testWithNumberAndParameter(): void
    {
        $this->request->expects($this->once())
            ->method('getServerParams')
            ->willReturn(['REQUEST_URI' => '/trivia?language=de']);

        $this->request->expects($this->once())
            ->method('withAttribute')
            ->with('type', 'trivia')
            ->willReturnSelf();

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request);

        $this->middleware->process($this->request, $this->handler);
    }
}
