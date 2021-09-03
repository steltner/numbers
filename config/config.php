<?php declare(strict_types=1);

$config = require CONFIG . 'providers.php';

return (new Laminas\ConfigAggregator\ConfigAggregator($config))->getMergedConfig();
