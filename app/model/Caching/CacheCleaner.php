<?php

namespace App\Core\Cache;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;

final class CacheCleaner
{

	/** @var IStorage */
	private $storage;

	/**
	 * @param IStorage $storage
	 */
	public function __construct(IStorage $storage)
	{
		$this->storage = $storage;
	}

	/**
	 * Clean whole cache
	 *
	 * @return void
	 */
	public function clean(): void
	{
		$this->storage->clean([Cache::ALL => TRUE]);
	}

	/**
	 * Clean by given tags
	 *
	 * @param array $tags
	 * @return void
	 */
	public function cleanByTags(array $tags): void
	{
		$this->storage->clean([Cache::TAGS => $tags]);
	}

	/**
	 * Clear by priority
	 *
	 * @param int $priority
	 * @return void
	 */
	public function cleanByPriority($priority): void
	{
		$this->storage->clean([Cache::PRIORITY => intval($priority)]);
	}

	/**
	 * Custom clear by
	 *
	 * @param array $conditions
	 * @return void
	 */
	public function cleanBy(array $conditions): void
	{
		$this->storage->clean($conditions);
	}

}
