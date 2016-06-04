<?php

/**
 * Test: App\Core\Latte\Filters.timeAgo
 */

require __DIR__ . '/../../../../bootstrap.php';

use App\Core\Latte\Filters\Filters;
use Tester\Assert;

// N/A
test(function () {
    Assert::equal('N/A', Filters::timeAgo(FALSE));
    Assert::equal('N/A', Filters::timeAgo(time() + 1));
});

// Time
test(function () {
    $time = time();
    $minute = 60;
    $hour = 60 * 60;
    $day = 60 * 60 * 24;
    $year = 60 * 60 * 24 * 365.25;

    // Hot
    Assert::equal('hot', Filters::timeAgo($time - 10 * $minute));
    Assert::equal('hot', Filters::timeAgo($time - 60 * $minute));
    Assert::equal('hot', Filters::timeAgo($time - $hour));

    // *h
    Assert::equal('2h', Filters::timeAgo($time - 1.1 * $hour));
    Assert::equal('2h', Filters::timeAgo($time - 2 * $hour));
    Assert::equal('12h', Filters::timeAgo($time - 12 * $hour));
    Assert::equal('14h', Filters::timeAgo($time - 13.6 * $hour));
    Assert::equal('24h', Filters::timeAgo($time - 24 * $hour));

    // *d
    Assert::equal('30d', Filters::timeAgo($time - 30 * $day));
    Assert::equal('31d', Filters::timeAgo($time - 30.1 * $day));
    Assert::equal('120d', Filters::timeAgo($time - 120 * $day));
    Assert::equal('121d', Filters::timeAgo($time - 120.9 * $day));
    Assert::equal('365d', Filters::timeAgo($time - 365 * $day));

    // *y
    Assert::equal('1y', Filters::timeAgo($time - 1 * $year));
    Assert::equal('1.1y', Filters::timeAgo($time - 410 * $day));
    Assert::equal('10y', Filters::timeAgo($time - 10 * 365 * $day));
});
