<?php declare(strict_types=1);

namespace Application\Middleware;

use Easy\Test\MiddlewareTestCase;
use Laminas\Diactoros\Response\JsonResponse;

class NumberValidationMiddlewareTest extends MiddlewareTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = new NumberValidationMiddleware();
    }

    public function testWithValidNumber(): void
    {
        $number = 1;

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('number')
            ->willReturn($number);

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request);

        $this->middleware->process($this->request, $this->handler);
    }

    public function testWithoutNumber(): void
    {
        $number = null;

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('number')
            ->willReturn($number);

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request);

        $this->middleware->process($this->request, $this->handler);
    }

    public function testWithNegativeNumber(): void
    {
        $number = -1;

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('number')
            ->willReturn($number);

        $response = $this->middleware->process($this->request, $this->handler);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertEquals(['message' => 'Number must be positive'], $json);
    }

    public function testWithFloatNumber(): void
    {
        $number = '1.2';

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('number')
            ->willReturn($number);

        $response = $this->middleware->process($this->request, $this->handler);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertEquals(['message' => 'Number must be an integer'], $json);
    }
}
