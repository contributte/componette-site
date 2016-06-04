<?php

namespace App\Modules\Front\Portal\Controls\AddonDetail;

use App\Core\Latte\ComposerHelper;
use App\Core\UI\BaseControl;
use App\Model\ORM\Addon\Addon;
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

    public function renderHeader()
    {
        $this->template->addon = $this->addon;
        $this->template->setFile(__DIR__ . '/templates/header.latte');
        $this->template->render();
    }

    public function renderContent()
    {
        $this->template->addon = $this->addon;
        $this->template->setFile(__DIR__ . '/templates/content.latte');
        $this->template->render();
    }

    public function renderSidebar()
    {
        $this->template->addon = $this->addon;
        $this->template->setFile(__DIR__ . '/templates/sidebar.latte');
        $this->template->render();
    }

    public function renderStats()
    {
        $totalDownloads = [];

        // Calculate total downloads
        if (($stats = $this->addon->github->extra->get('composer-stats'))) {
            foreach ($stats['all']['labels'] as $key => $label) {
                $totalDownloads[] = ['x' => DateTime::from($label)->format('c'), 'y' => $stats['all']['values'][$key]];
            }
        }

        $this->template->totalDownloads = json_encode($totalDownloads);
        $this->template->setFile(__DIR__ . '/templates/stats.latte');
        $this->template->render();
    }

    public function renderReport()
    {
        $this->template->setFile(__DIR__ . '/templates/report.latte');
        $this->template->render();
    }

}
