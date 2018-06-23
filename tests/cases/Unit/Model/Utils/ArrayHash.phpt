<?php declare(strict_types = 1);

require __DIR__ . '/../../../../bootstrap.php';

use App\Model\Utils\Arrays;
use Nette\Utils\ArrayHash;
use Tester\Assert;

// array => ArrayHash
test(function (): void {
	$array = [
		'foo' => [
			'bar' => 1,
		],
	];

	$ah = ArrayHash::from($array, true);
	Assert::type(ArrayHash::class, $ah->foo);
	Assert::false(is_array($ah->foo));
});

// ArrayHash => array
test(function (): void {
	$array = [
		'foo' => [
			'bar' => 1,
		],
	];

	$ah = ArrayHash::from($array, true);
	$r = (array) $ah;

	Assert::true(is_array($r));
	Assert::type(ArrayHash::class, $r['foo']);

	$r = Arrays::ensure($ah);

	Assert::true(is_array($r));
	Assert::true(is_array($r['foo']));
});
