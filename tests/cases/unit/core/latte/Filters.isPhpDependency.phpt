<?php

/**
 * Test: App\Core\Latte\Filters.isPhpDependency
 */

require __DIR__ . '/../../../../bootstrap.php';

use App\Core\Latte\Filters;
use Tester\Assert;

// Truthy
test(function () {
    Assert::true(Filters::isPhpDependency('php'));
    Assert::true(Filters::isPhpDependency('ext-mysql'));
    Assert::true(Filters::isPhpDependency('ext-pdo'));
});

// Falsey
test(function () {
    Assert::false(Filters::isPhpDependency('my-php-addon'));
    Assert::false(Filters::isPhpDependency('mysql-ext'));
    Assert::false(Filters::isPhpDependency('php-extras'));
});

