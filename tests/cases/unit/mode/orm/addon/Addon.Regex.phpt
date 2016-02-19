<?php

/**
 * Test: App\Model\ORM\Addon\Addon.Regex
 */

require __DIR__ . '/../../../../../bootstrap.php';

use App\Model\ORM\Addon\Addon;
use Nette\Utils\Strings;
use Tester\Assert;

// Truthy
test(function () {
    Assert::true(Strings::match('foo/bar', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
    Assert::true(Strings::match('foo/bar123', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
    Assert::true(Strings::match('123/foobar', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
    Assert::true(Strings::match('foo-bar/b-a-r', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
    Assert::true(Strings::match('github.com/foo/bar', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
    Assert::true(Strings::match('www.github.com/foo/bar', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
    Assert::true(Strings::match('http://github.com/foo/bar', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
    Assert::true(Strings::match('https://github.com/foo/bar', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
    Assert::true(Strings::match('http://www.github.com/foo/bar', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
    Assert::true(Strings::match('https://www.github.com/foo/bar', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
    Assert::true(Strings::match('https://www.github.com/foo.foo/bar', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
    Assert::true(Strings::match('https://www.github.com/foo/bar.bar', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
    Assert::true(Strings::match('https://www.github.com/foo.foo/bar.bar', '#' . Addon::GITHUB_REGEX . '#') !== NULL);
});

// Invalid
test(function () {
    Assert::true(Strings::match('foobar', '#' . Addon::GITHUB_REGEX . '#') === NULL);
    Assert::true(Strings::match('foo/foo/bar', '#' . Addon::GITHUB_REGEX . '#') === NULL);
    Assert::true(Strings::match('github.cz/foo/bar', '#' . Addon::GITHUB_REGEX . '#') === NULL);
    Assert::true(Strings::match('vvv.github.cz/foo/bar', '#' . Addon::GITHUB_REGEX . '#') === NULL);
    Assert::true(Strings::match('httttp://github.cz/foo/bar', '#' . Addon::GITHUB_REGEX . '#') === NULL);
    Assert::true(Strings::match('httttp://github.cz/f$/bar', '#' . Addon::GITHUB_REGEX . '#') === NULL);
    Assert::true(Strings::match('httttp://github.cz/foo/b_a', '#' . Addon::GITHUB_REGEX . '#') === NULL);
});
