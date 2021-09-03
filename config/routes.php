<?php declare(strict_types=1);

use Mezzio\Application;

return function (Application $application, array $configProvider): void {
    // register global routes

    // register local routes
    foreach ($configProvider as $provider) {
        if (is_callable([$provider, 'registerRoutes',])) {
            $provider->registerRoutes($application);
        }
    }
};
