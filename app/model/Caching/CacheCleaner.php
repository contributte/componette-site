<?php declare(strict_types = 1);

namespace App\Model\Caching;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;

final class CacheCleaner
{

	/** @var IStorage */
	private $storage;

	public function __construct(IStorage $storage)
	{
		$this->storage = $storage;
	}

	/**
	 * Clean whole cache
	 */
	public function clean(): void
	{
		$this->storage->clean([Cache::ALL => true]);
	}

	/**
	 * Clean by given tags
	 *
	 * @param string[] $tags
	 */
	public function cleanByTags(array $tags): void
	{
		$this->storage->clean([Cache::TAGS => $tags]);
	}

	/**
	 * Clear by priority
	 */
	public function cleanByPriority(int $priority): void
	{
		$this->storage->clean([Cache::PRIORITY => intval($priority)]);
	}

	/**
	 * Custom clear by
	 *
	 * @param string[] $conditions
	 */
	public function cleanBy(array $conditions): void
	{
		$this->storage->clean($conditions);
	}

}
