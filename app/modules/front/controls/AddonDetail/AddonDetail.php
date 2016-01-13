<?php

namespace App\Modules\Front\Controls\AddonDetail;

use App\Core\UI\BaseControl;
use App\Model\ORM\Addon\Addon;
use App\Modules\Front\Controls\AddonMeta\AddonMeta;
use App\Modules\Front\Controls\AddonMeta\IAddonMetaFactory;
use Nette\Utils\DateTime;

final class AddonDetail extends BaseControl
{

    /** @var Addon */
    private $addon;

    /** @var IAddonMetaFactory */
    private $addonMetaFactory;

    /**
     * @param Addon $addon
     * @param IAddonMetaFactory $addonMetaFactory
     */
    public function __construct(Addon $addon, IAddonMetaFactory $addonMetaFactory)
    {
        parent::__construct();
        $this->addon = $addon;
        $this->addonMetaFactory = $addonMetaFactory;
    }

    /**
     * CONTROLS ****************************************************************
     */

    /**
     * @return AddonMeta
     */
    protected function createComponentMeta()
    {
        return $this->addonMetaFactory->create();
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

}