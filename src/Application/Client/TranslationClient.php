<?php declare(strict_types=1);

namespace Application\Client;

use InvalidArgumentException;
use GuzzleHttp\Client;
use RuntimeException;

use function json_decode;
use function sprintf;
use function urlencode;

class TranslationClient
{
    private const URL = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=en&tl=%s&dt=t&q=%s';

    public function __construct(private Client $client)
    {
    }

    public function translate(string $text, string $language): string
    {
        if ($language === 'en') {
            return $text;
        }

        $response = $this->client->get(sprintf(self::URL, $language, urlencode($text)));

        if ($response->getStatusCode() !== 200) {
            throw new InvalidArgumentException('Translation API is gone');
        }

        $body = $response->getBody()->getContents();
        $body = json_decode($body, true);

        if (!is_array($body) || !isset($body[0][0][0])) {
            throw new RuntimeException('Invalid translation API result');
        }

        return $body[0][0][0];
    }
}
