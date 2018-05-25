<?php declare(strict_types = 1);

require __DIR__ . '/../vendor/autoload.php';

$configurator = new \Mangoweb\Tester\Infrastructure\InfrastructureConfigurator(__DIR__ . '/tmp/');
$configurator->addParameters([
	'appDir' => __DIR__ . '/../app/',
	'consoleMode' => false,
]);

$configurator->addConfig(__DIR__ . '/config/infrastructure.neon');
$configurator->addConfig(__DIR__ . '/../app/config/ext/nextras.neon');
$configurator->addConfig(__DIR__ . '/../app/config/config.local.neon');

$configurator->setTimeZone('UTC');
$configurator->setupTester();

return $configurator->getContainerFactory();
