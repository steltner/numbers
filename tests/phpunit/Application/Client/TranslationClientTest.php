<?php declare(strict_types=1);

namespace Application\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class TranslationClientTest extends TestCase
{
    private Client|MockObject $client;
    private TranslationClient $translationClient;

    protected function setUp(): void
    {
        $this->client = $this->createMock(Client::class);

        $this->translationClient = new TranslationClient($this->client);
    }

    public function testTranslateOriginalLanguage(): void
    {
        $text = '1 ist a wonderful number';
        $language = 'en';

        $this->assertSame($text, $this->translationClient->translate($text, $language));
    }

    public function testTranslateBadRequest(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Translation API is gone');

        $text = '1 ist a wonderful number';
        $language = 'de';

        $response = new Response(400);

        $this->client->expects($this->once())
            ->method('__call')
            ->with('get', ['https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=de&dt=t&q=1+ist+a+wonderful+number'])
            ->willReturn($response);

        $this->translationClient->translate($text, $language);
    }

    public function testTranslateInvalidResult(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Invalid translation API result');

        $text = '1 ist a wonderful number';
        $language = 'de';
        $translation = '1 ist eine wundervolle Nummer';

        $response = new Response(200, [], json_encode($translation));

        $this->client->expects($this->once())
            ->method('__call')
            ->with('get', ['https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=de&dt=t&q=1+ist+a+wonderful+number'])
            ->willReturn($response);

        $this->translationClient->translate($text, $language);
    }

    public function testTranslateSuccess(): void
    {
        $text = '1 ist a wonderful number';
        $language = 'de';
        $translation = '1 ist eine wundervolle Nummer';

        $response = new Response(200, [], json_encode([[[$translation]]]));

        $this->client->expects($this->once())
            ->method('__call')
            ->with('get', ['https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=de&dt=t&q=1+ist+a+wonderful+number'])
            ->willReturn($response);

        $this->assertSame($translation, $this->translationClient->translate($text, $language));
    }
}
