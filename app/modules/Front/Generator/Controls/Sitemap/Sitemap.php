<?php declare(strict_types = 1);

namespace App\Modules\Front\Generator\Controls\Sitemap;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Tag\Tag;
use App\Model\Database\ORM\Tag\TagRepository;
use App\Model\UI\BaseControl;

final class Sitemap extends BaseControl
{

	private AddonRepository $addonRepository;

	private TagRepository $tagRepository;

	public function __construct(AddonRepository $addonRepository, TagRepository $tagRepository)
	{
		$this->addonRepository = $addonRepository;
		$this->tagRepository = $tagRepository;
	}

	/**
	 * @return mixed[]
	 */
	private function getUrls(): array
	{
		$urls = [];

		// Build static urls
		$urls[] = [
			'loc' => $this->presenter->link('//:Front:Home:default'),
			'priority' => 1,
			'change' => 'hourly',
		];

		$urls[] = [
			'loc' => $this->presenter->link('//:Front:Index:all'),
			'priority' => 0.9,
			'change' => 'daily',
		];

		// Build authors urls
		$authors = $this->findAuthors();
		foreach ($authors as $addon) {
			$urls[] = [
				'loc' => $this->presenter->link('//:Front:Index:author', ['slug' => $addon->getAuthor()]),
				'priority' => 0.6,
				'change' => 'weekly',
			];
		}

		// Build addons urls
		$addons = $this->findAddons();
		foreach ($addons as $addon) {
			$urls[] = [
				'loc' => $this->presenter->link('//:Front:Addon:detail', ['slug' => $addon->getId()]),
				'priority' => 0.5,
				'change' => 'weekly',
			];
		}

		// Build tags urls
		$tags = $this->findTags();
		foreach ($tags as $tag) {
			$urls[] = [
				'loc' => $this->presenter->link('//:Front:Index:tag', ['tag' => $tag->getName()]),
				'priority' => 0.3,
				'change' => 'yearly',
			];
		}

		return $urls;
	}

	/**
	 * DATA ********************************************************************
	 */

	/**
	 * @return Addon[]
	 */
	private function findAuthors(): array
	{
		$addons = $this->addonRepository->findBy(['state' => Addon::STATE_ACTIVE], ['id' => 'DESC']);
		$authors = [];
		foreach ($addons as $addon) {
			$authors[$addon->getAuthor()] = $addon;
		}
		return $authors;
	}

	/**
	 * @return Addon[]
	 */
	private function findAddons(): array
	{
		return $this->addonRepository->findBy(['state' => Addon::STATE_ACTIVE], ['id' => 'DESC']);
	}

	/**
	 * @return Tag[]
	 */
	private function findTags(): array
	{
		return $this->tagRepository->findBy([], ['id' => 'DESC']);
	}

	/**
	 * RENDER ******************************************************************
	 */

	/**
	 * Render component
	 */
	public function render(): void
	{
		$this->template->urls = $this->getUrls();
		$this->template->setFile(__DIR__ . '/templates/sitemap.latte');
		$this->template->render();
	}

}
