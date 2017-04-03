<?php

namespace App\Modules\Front\Portal\Rss\Controls\RssFeed;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\EntityModel;
use App\Model\Database\Query\RssFeedQuery;
use Minetro\Nextras\Orm\QueryObject\Queryable;

final class RssFeedFactory
{

	/** @var EntityModel */
	private $em;

	/** @var IRssFeedFactory */
	private $rssFeedFactory;

	/**
	 * @param EntityModel $em
	 * @param IRssFeedFactory $rssFeedFactory
	 */
	public function __construct(EntityModel $em, IRssFeedFactory $rssFeedFactory)
	{
		$this->em = $em;
		$this->rssFeedFactory = $rssFeedFactory;
	}

	/**
	 * @return RssFeed
	 */
	public function createNewest()
	{
		$query = new RssFeedQuery();
		$query->byLatest();
		$query->setLimit(25);
		$list = $this->em->getRepositoryForEntity(Addon::class)->fetch($query, Queryable::HYDRATION_ENTITY);
		$control = $this->rssFeedFactory->create($list);

		$control->setTitle('Componette - latest addons');
		$control->setDescription('Latest added addons');

		return $control;
	}

	/**
	 * @param string $author
	 * @return RssFeed
	 */
	public function createByAuthor($author)
	{
		$query = new RssFeedQuery();
		$query->byAuthor($author);
		$list = $this->em->getRepositoryForEntity(Addon::class)->fetch($query, Queryable::HYDRATION_ENTITY);
		$control = $this->rssFeedFactory->create($list);

		$control->setTitle('Componette - addons by ' . $author);
		$control->setDescription('Addons created by ' . $author);

		return $control;
	}

}
