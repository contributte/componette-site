<?php

/**
 * Test: App\Model\Templating\Filters\Helpers.isPhp
 */

require __DIR__ . '/../../../../../bootstrap.php';

use App\Model\Templating\Filters\Helpers;
use Tester\Assert;

// Truthy
test(function () {
	Assert::true(Helpers::isPhp('php'));
	Assert::true(Helpers::isPhp('ext-mysql'));
	Assert::true(Helpers::isPhp('ext-pdo'));
});

// Falsey
test(function () {
	Assert::false(Helpers::isPhp('my-php-addon'));
	Assert::false(Helpers::isPhp('mysql-ext'));
	Assert::false(Helpers::isPhp('php-extras'));
});
