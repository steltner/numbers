<?php declare(strict_types=1);

namespace Application\Middleware;

use Application\Client\TranslationClient;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class TranslationMiddleware implements MiddlewareInterface
{
    public function __construct(private TranslationClient $translationClient, private array $languages)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /** @var string $language */
        $params = $request->getQueryParams();

        if (!isset($params['language']) || empty($params['language'])) {
            return $handler->handle($request);
        }

        if (!isset($this->languages[$params['language']])) {
            return new JsonResponse(
                [
                    'message' => 'Language does not exist',
                ],
                400
            );
        }

        $fact = $request->getAttribute('fact');

        $fact = $this->translationClient->translate($fact, $params['language']);

        return $handler->handle($request->withAttribute('translation', $fact));
    }
}
