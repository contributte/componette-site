<?php

use Contributte\Bootstrap\Configurator;

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Configurator();

$configurator->autoDebugMode();
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

$container = $configurator->createContainer();

return $container;
