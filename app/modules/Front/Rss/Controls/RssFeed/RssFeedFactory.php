<?php declare(strict_types = 1);

namespace App\Modules\Front\Rss\Controls\RssFeed;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\EntityModel;
use App\Model\Database\Query\RssFeedQuery;
use Nextras\Orm\Collection\ICollection;

final class RssFeedFactory
{

	/** @var EntityModel */
	private $em;

	/** @var IRssFeedFactory */
	private $rssFeedFactory;

	public function __construct(EntityModel $em, IRssFeedFactory $rssFeedFactory)
	{
		$this->em = $em;
		$this->rssFeedFactory = $rssFeedFactory;
	}

	public function createNewest(): RssFeed
	{
		$query = new RssFeedQuery();
		$query->byLatest();
		$query->setLimit(25);

		/** @var ICollection<Addon> $list */
		$list = $this->em->getRepositoryForEntity(Addon::class)->fetchEntities($query);
		$control = $this->rssFeedFactory->create($list);

		$control->setTitle('Componette - latest addons');
		$control->setDescription('Latest added addons');

		return $control;
	}

	public function createByAuthor(string $author): RssFeed
	{
		$query = new RssFeedQuery();
		$query->byAuthor($author);

		/** @var ICollection<Addon> $list */
		$list = $this->em->getRepositoryForEntity(Addon::class)->fetchEntities($query);
		$control = $this->rssFeedFactory->create($list);

		$control->setTitle('Componette - addons by ' . $author);
		$control->setDescription('Addons created by ' . $author);

		return $control;
	}

}
