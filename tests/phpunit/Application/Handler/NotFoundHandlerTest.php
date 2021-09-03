<?php declare(strict_types=1);

namespace Application\Handler;

use Easy\Test\HandlerTestCase;
use Laminas\Diactoros\Response\JsonResponse;

class NotFoundHandlerTest extends HandlerTestCase
{
    public function testResponse(): void
    {
        $this->handler = new NotFoundHandler();

        $response = $this->handler->handle($this->request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(404, $response->getStatusCode());
    }
}
