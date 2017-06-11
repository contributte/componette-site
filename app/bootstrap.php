<?php

use Contributte\Bootstrap\ExtraConfigurator;
use Nette\Configurator;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Configurator();

$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

ExtraConfigurator::wrap($configurator);

$container = $configurator->createContainer();

return $container;
