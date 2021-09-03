<?php declare(strict_types=1);

return function (Mezzio\Application $application) : void {
    $application->pipe(Laminas\Stratigility\Middleware\ErrorHandler::class);

    $application->pipe(Mezzio\Helper\ServerUrlMiddleware::class);

    $application->pipe(Mezzio\Router\Middleware\RouteMiddleware::class);

    $application->pipe(Mezzio\Router\Middleware\ImplicitHeadMiddleware::class);
    $application->pipe(Mezzio\Router\Middleware\ImplicitOptionsMiddleware::class);
    $application->pipe(Mezzio\Router\Middleware\MethodNotAllowedMiddleware::class);

    $application->pipe(Mezzio\Helper\UrlHelperMiddleware::class);

    $application->pipe(Mezzio\Helper\BodyParams\BodyParamsMiddleware::class);

    $application->pipe(Mezzio\Router\Middleware\DispatchMiddleware::class);

    $application->pipe(Application\Handler\NotFoundHandler::class);
};
