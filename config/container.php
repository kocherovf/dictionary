<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

$builder = new ContainerBuilder();

$aggregator = new ConfigAggregator([new PhpFileProvider(__DIR__ . '/packages/*.php'),]);

$builder->addDefinitions($aggregator->getMergedConfig());

return $builder->build();
