<?php declare(strict_types=1);

namespace Application\Middleware;

use Application\Client\TranslationClient;
use Easy\Test\MiddlewareTestCase;
use Laminas\Diactoros\Response\JsonResponse;
use PHPUnit\Framework\MockObject\MockObject;

use function json_decode;

class TranslationMiddlewareTest extends MiddlewareTestCase
{
    private TranslationClient|MockObject $translationClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->translationClient = $this->createMock(TranslationClient::class);
        $languages = [
            'de' => 'German',
        ];

        $this->middleware = new TranslationMiddleware($this->translationClient, $languages);
    }

    public function testTranslate(): void
    {
        $fact = '1 ist a wonderful number';
        $translation = '1 ist eine wundervolle Nummer';
        $language = 'de';

        $this->request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn(['language' => $language]);

        $this->request->expects($this->once())
            ->method('getAttribute')
            ->with('fact')
            ->willReturn($fact);

        $this->translationClient->expects($this->once())
            ->method('translate')
            ->with($fact, $language)
            ->willReturn($translation);

        $this->request->expects($this->once())
            ->method('withAttribute')
            ->with('translation', $translation)
            ->willReturnSelf();

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request);

        $this->middleware->process($this->request, $this->handler);
    }

    public function testNoLanguage(): void
    {
        $this->request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn(['language' => null]);

        $this->handler->expects($this->once())
            ->method('handle')
            ->with($this->request);

        $this->middleware->process($this->request, $this->handler);
    }

    public function testLanguageDoesNotExist(): void
    {
        $language = 'dummy';

        $this->request->expects($this->once())
            ->method('getQueryParams')
            ->willReturn(['language' => $language]);

        $response = $this->middleware->process($this->request, $this->handler);

        $json = json_decode($response->getBody()->getContents(), true);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame(400, $response->getStatusCode());
        $this->assertEquals(['message' => 'Language does not exist'], $json);
    }
}
