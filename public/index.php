<?php declare(strict_types=1);

const DS = DIRECTORY_SEPARATOR;
const PS = '.';

const ROOT = __DIR__ . DS . '..' . DS;

const CONFIG = ROOT . 'config' . DS;

// Delegate static file requests back to the PHP built-in webserver
if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

require __DIR__ . DS . '..' . DS . 'vendor' . DS . 'autoload.php';

(function () {
    $config = require CONFIG . 'providers.php';

    $aggregatedConfig = (new Laminas\ConfigAggregator\ConfigAggregator($config))->getMergedConfig();

    $dependencies = $aggregatedConfig['dependencies'];
    $dependencies['services']['config'] = $aggregatedConfig;

    $container = new Laminas\ServiceManager\ServiceManager($dependencies);

    /** @var Mezzio\Application $application */
    $application = $container->get(Mezzio\Application::class);

    (require CONFIG . 'pipeline.php')($application);
    (require CONFIG . 'routes.php')($application, $config);

    $application->run();
})();
