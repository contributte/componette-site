<?php

namespace App\Model\Routing\Helpers;

use App\Model\Caching\CacheKeys;
use App\Model\Database\ORM\Addon\AddonRepository;
use Contributte\Cache\CacheFactory;
use Nette\Caching\Cache;

final class AddonsHelper
{

	/** @var AddonRepository */
	private $repository;

	/** @var Cache */
	private $cache;

	/** @var array */
	private $data = [
		'addons' => [],
		'owners' => [],
	];

	/** @var bool */
	private $build;

	/**
	 * @param AddonRepository $repository
	 * @param CacheFactory $cacheFactory
	 */
	public function __construct(AddonRepository $repository, CacheFactory $cacheFactory)
	{
		$this->repository = $repository;
		$this->cache = $cacheFactory->create(CacheKeys::ROUTING);
	}

	/**
	 * Build cache
	 *
	 * @return void
	 */
	protected function build(): void
	{
		if (!$this->build) {
			$this->data = $this->cache->load('routes', function (&$dependencies) {
				$dependencies[Cache::EXPIRE] = '+1 day';
				$dependencies[Cache::TAGS] = ['routing', 'routes'];

				$data = ['addons' => [], 'owners' => []];

				foreach ($this->repository->findAll() as $addon) {
					$data['addons'][$addon->id] = strtolower($addon->owner . '/' . $addon->name);
					$data['owners'][strtolower($addon->owner)] = strtolower($addon->owner);
				}

				return $data;
			});
			$this->build = TRUE;
		}
	}

	/**
	 * ADDON *******************************************************************
	 */

	/**
	 * @param string $slug
	 * @return int|NULL
	 */
	public function addonIn($slug): ?int
	{
		$this->build();
		$addon = array_search(strtolower($slug), $this->data['addons']);

		return $addon ? $addon : NULL;
	}

	/**
	 * @param int $id
	 * @return string|NULL
	 */
	public function addonOut($id): ?string
	{
		$this->build();
		if (isset($this->data['addons'][$id])) {
			return strtolower($this->data['addons'][$id]);
		}

		return NULL;
	}

	/**
	 * OWNER *******************************************************************
	 */

	/**
	 * @param string $slug
	 * @return string|NULL
	 */
	public function ownerIn($slug): ?string
	{
		$this->build();
		$slug = strtolower($slug);
		if (isset($this->data['owners'][$slug])) {
			return strtolower($this->data['owners'][$slug]);
		}

		return NULL;
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public function ownerOut($slug): ?string
	{
		$this->build();
		$slug = strtolower($slug);
		if (isset($this->data['owners'][$slug])) {
			return strtolower($this->data['owners'][$slug]);
		}

		return NULL;
	}

}
