<?php

/**
 * Test: App\Core\Latte\Filters.isPhpDependency
 */

require __DIR__ . '/../../../../bootstrap.php';

use App\Core\Latte\ComposerHelper;
use Tester\Assert;

// Truthy
test(function () {
    Assert::true(ComposerHelper::isPhpDependency('php'));
    Assert::true(ComposerHelper::isPhpDependency('ext-mysql'));
    Assert::true(ComposerHelper::isPhpDependency('ext-pdo'));
});

// Falsey
test(function () {
    Assert::false(ComposerHelper::isPhpDependency('my-php-addon'));
    Assert::false(ComposerHelper::isPhpDependency('mysql-ext'));
    Assert::false(ComposerHelper::isPhpDependency('php-extras'));
});
