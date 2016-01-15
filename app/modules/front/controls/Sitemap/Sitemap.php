<?php

namespace App\Modules\Front\Controls\Sitemap;

use App\Model\Facade\AddonFacade;
use Nette\Application\UI\Control;

class Sitemap extends Control
{

    /** @var AddonFacade */
    private $facade;

    /**
     * @param AddonFacade $facade
     */
    public function __construct(AddonFacade $facade)
    {
        parent::__construct();
        $this->facade = $facade;
    }

    /**
     * @return array
     */
    private function getUrls()
    {
        $urls = [];

        // Build static urls
        $urls[] = [
            'loc' => $this->presenter->link('//:Front:Home:default'),
            'priority' => 1,
            'change' => 'hourly',
        ];
        $urls[] = [
            'loc' => $this->presenter->link('//:Front:List:default'),
            'priority' => 0.9,
            'change' => 'daily',
        ];

        // Build owners urls
        foreach ($this->facade->findActiveOwners() as $addon) {
            $urls[] = [
                'loc' => $this->presenter->link('//:Front:List:owner', ['slug' => $addon->owner]),
                'priority' => 0.6,
                'change' => 'weekly',
            ];
        }

        // Build addons urls
        foreach ($this->facade->findActive() as $addon) {
            $urls[] = [
                'loc' => $this->presenter->link('//:Front:Addon:detail', ['slug' => $addon->id]),
                'priority' => 0.5,
                'change' => 'weekly',
            ];
        }

        return $urls;
    }

    /**
     * RENDER ******************************************************************
     */

    public function render()
    {
        $this->template->urls = $this->getUrls();
        $this->template->setFile(__DIR__ . '/templates/sitemap.latte');
        $this->template->render();
    }

}
