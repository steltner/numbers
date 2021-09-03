<?php declare(strict_types=1);

use Laminas\ConfigAggregator\PhpFileProvider;

return (new Easy\InvocableResolver())->resolveList([
    Laminas\HttpHandlerRunner\ConfigProvider::class,
    Mezzio\Router\FastRouteRouter\ConfigProvider::class,

    Mezzio\Helper\ConfigProvider::class,
    Mezzio\ConfigProvider::class,
    Mezzio\Router\ConfigProvider::class,

    // Add module based ConfigProviders
    Easy\ConfigProvider::class,
    Easy\Error\ConfigProvider::class,

    Application\ConfigProvider::class,

    new PhpFileProvider(CONFIG . 'global.php.dist'),
    new PhpFileProvider(CONFIG . 'global.php'),

    new PhpFileProvider(CONFIG . 'projects.php.dist'),
    new PhpFileProvider(CONFIG . 'projects.php'),
]);
