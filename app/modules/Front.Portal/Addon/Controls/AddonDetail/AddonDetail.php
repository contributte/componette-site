<?php

namespace App\Modules\Front\Portal\Addon\Controls\AddonDetail;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\UI\BaseControl;
use App\Modules\Front\Portal\Controls\AddonMeta\AddonMeta;
use Nette\Utils\DateTime;

final class AddonDetail extends BaseControl
{

	/** @var Addon */
	private $addon;

	/**
	 * @param Addon $addon
	 */
	public function __construct(Addon $addon)
	{
		parent::__construct();
		$this->addon = $addon;
	}

	/**
	 * CONTROLS ****************************************************************
	 */

	/**
	 * @return AddonMeta
	 */
	protected function createComponentMeta()
	{
		return new AddonMeta();
	}

	/**
	 * RENDER ******************************************************************
	 */

	/**
	 * Render header
	 *
	 * @return void
	 */
	public function renderHeader()
	{
		$this->template->addon = $this->addon;
		$this->template->setFile(__DIR__ . '/templates/header.latte');
		$this->template->render();
	}

	/**
	 * Render content
	 *
	 * @return void
	 */
	public function renderContent()
	{
		$this->template->addon = $this->addon;
		$this->template->setFile(__DIR__ . '/templates/content.latte');
		$this->template->render();
	}

	/**
	 * Render sidebar
	 *
	 * @return void
	 */
	public function renderSidebar()
	{
		$this->template->addon = $this->addon;
		$this->template->setFile(__DIR__ . '/templates/sidebar.latte');
		$this->template->render();
	}

	/**
	 * Render statistics
	 *
	 * @return void
	 */
	public function renderStats()
	{
		$totalDownloads = [];

		// Calculate total downloads
		$stats = $this->addon->composerLatestStatistics;
		if ($stats && $stats->json) {
			foreach ($stats->json['labels'] as $key => $label) {
				$totalDownloads[] = ['x' => DateTime::from($label)->format('c'), 'y' => $stats->json['values'][$key]];
			}
			$this->template->totalDownloads = json_encode($totalDownloads);
		} else {
			$this->template->totalDownloads = NULL;
		}

		$this->template->setFile(__DIR__ . '/templates/stats.latte');
		$this->template->render();
	}

	/**
	 * Render report
	 *
	 * @return void
	 */
	public function renderReport()
	{
		$this->template->setFile(__DIR__ . '/templates/report.latte');
		$this->template->render();
	}

	/**
	 * Render releases
	 *
	 * @return void
	 */
	public function renderReleases()
	{
		$this->template->addon = $this->addon;
		$this->template->setFile(__DIR__ . '/templates/releases.latte');
		$this->template->render();
	}

}
