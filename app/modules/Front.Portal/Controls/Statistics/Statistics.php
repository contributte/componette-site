<?php

namespace App\Modules\Front\Portal\Controls\Statistics;

use App\Model\Facade\StatisticsFacade;
use Nette\Application\UI\Control;

final class Statistics extends Control
{

	/** @var StatisticsFacade */
	private $facade;

	/**
	 * @param StatisticsFacade $facade
	 */
	public function __construct(StatisticsFacade $facade)
	{
		parent::__construct();
		$this->facade = $facade;
	}

	/**
	 * RENDER ******************************************************************
	 */

	public function renderFooter()
	{
		// Stats
		$this->template->_stats = function () {
			$data = [];
			$data['addons'] = $this->facade->countAddons();
			$data['queued'] = $this->facade->countQueued();
			$data['owners'] = $this->facade->countOwners();
			$data['tags'] = $this->facade->countTags();

			return (object) $data;
		};

		// List of popular
		$this->template->_popular = function () {
			return $this->facade->findMostPopular();
		};

		// List of newest
		$this->template->_newest = function () {
			return $this->facade->findNewest();
		};

		$this->template->setFile(__DIR__ . '/templates/footer.latte');
		$this->template->render();
	}

}
