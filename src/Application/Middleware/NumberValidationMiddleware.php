<?php declare(strict_types=1);

namespace Application\Middleware;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NumberValidationMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $number = $request->getAttribute('number');

        if (isset($number)) {
            if ($number < 0) {
                return new JsonResponse(
                    [
                        'message' => 'Number must be positive',
                    ],
                    400
                );
            }

            if ((int) $number != $number) {
                return new JsonResponse(
                    [
                        'message' => 'Number must be an integer',
                    ],
                    400
                );
            }
        }

        return $handler->handle($request);
    }
}
