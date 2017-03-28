<?php

/**
 * Test: App\Core\Latte\Filters.isPhpDependency
 */

require __DIR__ . '/../../../../bootstrap.php';

use App\Model\Templating\Filters\Helpers;
use Tester\Assert;

// Truthy
test(function () {
	Assert::true(Helpers::isPhpDependency('php'));
	Assert::true(Helpers::isPhpDependency('ext-mysql'));
	Assert::true(Helpers::isPhpDependency('ext-pdo'));
});

// Falsey
test(function () {
	Assert::false(Helpers::isPhpDependency('my-php-addon'));
	Assert::false(Helpers::isPhpDependency('mysql-ext'));
	Assert::false(Helpers::isPhpDependency('php-extras'));
});
