<?php

namespace App\Modules\Front\Generator\Controls\Sitemap;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\EntityModel;
use App\Model\Database\ORM\Tag\Tag;
use Nette\Application\UI\Control;
use Nextras\Orm\Collection\ICollection;

final class Sitemap extends Control
{

	/** @var EntityModel */
	private $em;

	/**
	 * @param EntityModel $em
	 */
	public function __construct(EntityModel $em)
	{
		parent::__construct();
		$this->em = $em;
	}

	/**
	 * @return array
	 */
	private function getUrls()
	{
		$urls = [];

		// Build static urls
		$urls[] = [
			'loc' => $this->presenter->link('//:Front:Portal:Home:default'),
			'priority' => 1,
			'change' => 'hourly',
		];

		$urls[] = [
			'loc' => $this->presenter->link('//:Front:Portal:Index:all'),
			'priority' => 0.9,
			'change' => 'daily',
		];

		// Build authors urls
		$authors = $this->findAuthors();
		foreach ($authors as $addon) {
			$urls[] = [
				'loc' => $this->presenter->link('//:Front:Portal:Index:author', ['slug' => $addon->author]),
				'priority' => 0.6,
				'change' => 'weekly',
			];
		}

		// Build addons urls
		$addons = $this->findAddons();
		foreach ($addons as $addon) {
			$urls[] = [
				'loc' => $this->presenter->link('//:Front:Portal:Addon:detail', ['slug' => $addon->id]),
				'priority' => 0.5,
				'change' => 'weekly',
			];
		}

		// Build tags urls
		$tags = $this->findTags();
		foreach ($tags as $tag) {
			$urls[] = [
				'loc' => $this->presenter->link('//:Front:Portal:Index:tag', ['tag' => $tag->name]),
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
	private function findAuthors()
	{
		return $this->em->getRepositoryForEntity(Addon::class)
			->findBy(['state' => Addon::STATE_ACTIVE])
			->orderBy(['id' => 'DESC'])
			->fetchPairs('author');
	}

	/**
	 * @return Addon[]|ICollection
	 */
	private function findAddons()
	{
		return $this->em->getRepositoryForEntity(Addon::class)
			->findBy(['state' => Addon::STATE_ACTIVE])
			->orderBy(['id' => 'DESC']);
	}

	/**
	 * @return Tag[]|ICollection
	 */
	private function findTags()
	{
		return $this->em->getRepositoryForEntity(Tag::class)
			->findAll()
			->orderBy(['id' => 'DESC']);
	}

	/**
	 * RENDER ******************************************************************
	 */

	/**
	 * Render component
	 *
	 * @return void
	 */
	public function render()
	{
		$this->template->urls = $this->getUrls();
		$this->template->setFile(__DIR__ . '/templates/sitemap.latte');
		$this->template->render();
	}

}
