<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\Statistics;

use App\Model\Facade\StatisticsFacade;
use App\Model\UI\BaseControl;

final class Statistics extends BaseControl
{

	/** @var StatisticsFacade */
	private $facade;

	public function __construct(StatisticsFacade $facade)
	{
		$this->facade = $facade;
	}

	/**
	 * RENDER ******************************************************************
	 */

	/**
	 * Render component
	 */
	public function renderFooter(): void
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
