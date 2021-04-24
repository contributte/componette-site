<?php declare(strict_types = 1);

namespace App\Model\Routing;

use App\Model\Caching\CacheKeys;
use App\Model\Database\ORM\Addon\AddonRepository;
use Contributte\Cache\CacheFactory;
use Nette\Caching\Cache;

final class RouterHelper
{

	/** @var AddonRepository */
	private $repository;

	/** @var Cache */
	private $cache;

	/** @var string[][] */
	private $data = [
		'addons' => [],
		'authors' => [],
	];

	/** @var bool */
	private $build;

	public function __construct(AddonRepository $repository, CacheFactory $cacheFactory)
	{
		$this->repository = $repository;
		$this->cache = $cacheFactory->create(CacheKeys::ROUTING);
	}

	/**
	 * Build cache
	 */
	protected function build(): void
	{
		if (!$this->build) {
			$this->data = (array) $this->cache->load('routes', function (&$dependencies): array {
				$dependencies[Cache::EXPIRE] = '+1 day';
				$dependencies[Cache::TAGS] = ['routing', 'routes'];

				$data = ['addons' => [], 'authors' => []];

				foreach ($this->repository->findAll() as $addon) {
					$data['addons'][$addon->id] = strtolower($addon->author . '/' . $addon->name);
					$data['authors'][strtolower($addon->author)] = strtolower($addon->author);
				}

				return $data;
			});
			$this->build = true;
		}
	}

	public function addonIn(string $slug): ?int
	{
		$this->build();
		$addon = array_search(strtolower($slug), $this->data['addons'], true);

		return $addon ? ((int) $addon) : null;
	}

	public function addonOut(string $id): ?string
	{
		$this->build();
		if (isset($this->data['addons'][intval($id)])) {
			return strtolower($this->data['addons'][intval($id)]);
		}

		return null;
	}

	public function authorIn(string $slug): ?string
	{
		$this->build();
		$slug = strtolower($slug);
		if (isset($this->data['authors'][$slug])) {
			return strtolower($this->data['authors'][$slug]);
		}

		return null;
	}

	public function authorOut(string $slug): ?string
	{
		$this->build();
		$slug = strtolower($slug);
		if (isset($this->data['authors'][$slug])) {
			return strtolower($this->data['authors'][$slug]);
		}

		return null;
	}

}
