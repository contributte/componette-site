<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Addon\Controls\AddonDetail;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\UI\BaseControl;
use App\Modules\Front\Portal\Base\Controls\AddonMeta\AddonMeta;
use Nette\Utils\DateTime;

final class AddonDetail extends BaseControl
{

	/** @var Addon */
	private $addon;

	public function __construct(Addon $addon)
	{
		parent::__construct();
		$this->addon = $addon;
	}

	/**
	 * CONTROLS ****************************************************************
	 */

	protected function createComponentMeta(): AddonMeta
	{
		return new AddonMeta();
	}

	/**
	 * RENDER ******************************************************************
	 */

	/**
	 * Render header
	 */
	public function renderHeader(): void
	{
		$this->template->addon = $this->addon;
		$this->template->setFile(__DIR__ . '/templates/header.latte');
		$this->template->render();
	}

	/**
	 * Render content
	 */
	public function renderContent(): void
	{
		$this->template->addon = $this->addon;
		$this->template->setFile(__DIR__ . '/templates/content.latte');
		$this->template->render();
	}

	/**
	 * Render sidebar
	 */
	public function renderSidebar(): void
	{
		$this->template->addon = $this->addon;
		$this->template->setFile(__DIR__ . '/templates/sidebar.latte');
		$this->template->render();
	}

	/**
	 * Render statistics
	 */
	public function renderStats(): void
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
			$this->template->totalDownloads = null;
		}

		$this->template->setFile(__DIR__ . '/templates/stats.latte');
		$this->template->render();
	}

	/**
	 * Render report
	 */
	public function renderReport(): void
	{
		$this->template->setFile(__DIR__ . '/templates/report.latte');
		$this->template->render();
	}

	/**
	 * Render releases
	 */
	public function renderReleases(): void
	{
		$this->template->addon = $this->addon;
		$this->template->setFile(__DIR__ . '/templates/releases.latte');
		$this->template->render();
	}

	/**
	 * Render comments
	 */
	public function renderComments(): void
	{
		$this->template->setFile(__DIR__ . '/templates/comments.latte');
		$this->template->render();
	}

}
