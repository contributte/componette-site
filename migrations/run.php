<?php

use Nette\DI\Container;
use Nextras\Dbal\Connection;
use Nextras\Migrations\Bridges;
use Nextras\Migrations\Bridges\NextrasDbal\NextrasAdapter;
use Nextras\Migrations\Controllers\ConsoleController;
use Nextras\Migrations\Controllers\HttpController;
use Nextras\Migrations\Drivers\MySqlDriver;
use Nextras\Migrations\Extensions\SqlHandler;

/** @var Container $container */
$container = require_once __DIR__ . '/../app/bootstrap.php';

$connection = $container->getByType(Connection::class);
$dbal = new NextrasAdapter($connection);
$driver = new MySqlDriver($dbal);

if (PHP_SAPI === 'cli') {
    $controller = new ConsoleController($driver);
} else {
    $controller = new HttpController($driver);
}

$baseDir = __DIR__;
$controller->addGroup('structures', "$baseDir/structures");
$controller->addGroup('basic-data', "$baseDir/basic-data", ['structures']);
$controller->addGroup('dummy-data', "$baseDir/dummy-data", ['basic-data']);
$controller->addExtension('sql', new SqlHandler($driver));

$controller->run();
