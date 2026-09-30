<?php declare(strict_types = 1);

/**
 * Test: App\Model\Database\ORM\GithubComposer\GithubComposer.data
 */

use App\Model\Database\ORM\EntityModel;
use App\Model\Database\ORM\GithubComposer\GithubComposer;
use Nette\DI\Container;
use Tester\Assert;

/** @var Container $container */
$container = require __DIR__ . '/../../../../../../bootstrap.container.php';

// Initialize ORM metadata (no database connection is opened)
$container->getByType(EntityModel::class);

// Insert serializes json into data
test(function (): void {
	$composer = new GithubComposer();
	$composer->json = ['name' => 'foo/bar'];
	$composer->onBeforeInsert();

	Assert::same('foo/bar', json_decode($composer->getRawValue('data'))->name);
});

// Update serializes changed json into data
test(function (): void {
	$composer = new GithubComposer();
	$composer->onLoad(['id' => 1, 'type' => GithubComposer::TYPE_BRANCH, 'custom' => GithubComposer::BRANCH_MASTER, 'data' => '{"name":"old/name"}']);
	Assert::same('old/name', $composer->name);

	$composer->json = ['name' => 'new/name'];
	$composer->onBeforeUpdate();

	Assert::same('new/name', json_decode($composer->getRawValue('data'))->name);
	Assert::true($composer->isModified('data'));
});
