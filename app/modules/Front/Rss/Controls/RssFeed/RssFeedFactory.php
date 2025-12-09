<?php declare(strict_types = 1);

namespace App\Modules\Front\Rss\Controls\RssFeed;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;

final class RssFeedFactory
{

	private AddonRepository $addonRepository;

	private IRssFeedFactory $rssFeedFactory;

	public function __construct(AddonRepository $addonRepository, IRssFeedFactory $rssFeedFactory)
	{
		$this->addonRepository = $addonRepository;
		$this->rssFeedFactory = $rssFeedFactory;
	}

	public function createNewest(): RssFeed
	{
		$list = $this->addonRepository->findBy(
			['state' => Addon::STATE_ACTIVE],
			['createdAt' => 'DESC'],
			25
		);

		$control = $this->rssFeedFactory->create($list);

		$control->setTitle('Componette - latest addons');
		$control->setDescription('Latest added addons');

		return $control;
	}

	public function createByAuthor(string $author): RssFeed
	{
		$list = $this->addonRepository->findBy(
			['state' => Addon::STATE_ACTIVE, 'author' => $author]
		);

		$control = $this->rssFeedFactory->create($list);

		$control->setTitle('Componette - addons by ' . $author);
		$control->setDescription('Addons created by ' . $author);

		return $control;
	}

}
