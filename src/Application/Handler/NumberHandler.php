<?php declare(strict_types=1);

namespace Application\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NumberHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $original = $request->getAttribute('fact');
        $found = $request->getAttribute('found');
        $fact = $request->getAttribute('translation') ?? $original;

        return new JsonResponse(
            [
                'original' => $original,
                'message' => $fact,
                'found' => $found,
            ]
        );
    }
}
