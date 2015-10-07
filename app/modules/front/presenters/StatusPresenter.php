<?php

namespace App\Modules\Front;

use App\Model\ORM\Package;
use App\Model\ORM\PackagesRepository;
use App\Model\WebServices\Github\Service;
use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Nette\Utils\DateTime;

final class StatusPresenter extends BasePresenter
{

    /** @var PackagesRepository @inject */
    public $packagesRepository;

    /** @var Service @inject */
    public $github;

    /** @var Cache */
    private $cache;

    /**
     * @param IStorage $storage
     */
    public function injectCacheStorage(IStorage $storage)
    {
        $this->cache = new Cache($storage, 'Status.Page');
    }

    public function renderDefault()
    {
        $status = $this->cache->load('data', function (&$dependencies) {
            $dependencies[Cache::EXPIRE] = new DateTime('+30 minutes');
            $dependencies[Cache::TAGS] = ['status', 'status.page'];

            $status = [];

            // Build packages status ===========================================

            if (($response = $this->github->limit())) {
                $status['packages']['active']['composer'] = $this->packagesRepository->findBy(['state' => Package::STATE_ACTIVE, 'type' => Package::TYPE_COMPOSER])->countStored();
                $status['packages']['active']['bower'] = $this->packagesRepository->findBy(['state' => Package::STATE_ACTIVE, 'type' => Package::TYPE_BOWER])->countStored();
                $status['packages']['active']['unknown'] = $this->packagesRepository->findBy(['state' => Package::STATE_ACTIVE, 'type' => Package::TYPE_UNKNOWN])->countStored();
                $status['packages']['active']['total'] = $this->packagesRepository->findBy(['state' => Package::STATE_ACTIVE])->countStored();

                $status['packages']['queued']['composer'] = $this->packagesRepository->findBy(['state' => Package::STATE_QUEUED, 'type' => Package::TYPE_COMPOSER])->countStored();
                $status['packages']['queued']['bower'] = $this->packagesRepository->findBy(['state' => Package::STATE_QUEUED, 'type' => Package::TYPE_BOWER])->countStored();
                $status['packages']['queued']['unknown'] = $this->packagesRepository->findBy(['state' => Package::STATE_QUEUED, 'type' => Package::TYPE_UNKNOWN])->countStored();
                $status['packages']['queued']['total'] = $this->packagesRepository->findBy(['state' => Package::STATE_QUEUED])->countStored();

                $status['packages']['archived']['composer'] = $this->packagesRepository->findBy(['state' => Package::STATE_ARCHIVED, 'type' => Package::TYPE_COMPOSER])->countStored();
                $status['packages']['archived']['bower'] = $this->packagesRepository->findBy(['state' => Package::STATE_ARCHIVED, 'type' => Package::TYPE_BOWER])->countStored();
                $status['packages']['archived']['unknown'] = $this->packagesRepository->findBy(['state' => Package::STATE_ARCHIVED, 'type' => Package::TYPE_UNKNOWN])->countStored();
                $status['packages']['archived']['total'] = $this->packagesRepository->findBy(['state' => Package::STATE_ARCHIVED])->countStored();

                $status['packages']['total']['composer'] = $this->packagesRepository->findBy(['type' => Package::TYPE_COMPOSER])->countStored();
                $status['packages']['total']['bower'] = $this->packagesRepository->findBy(['type' => Package::TYPE_BOWER])->countStored();
                $status['packages']['total']['unknown'] = $this->packagesRepository->findBy(['type' => Package::TYPE_UNKNOWN])->countStored();
                $status['packages']['total']['total'] = $this->packagesRepository->findAll()->countStored();
            }

            // Build github status =============================================

            if (($response = $this->github->limit())) {
                $status['github']['core']['limit'] = $response['resources']['core']['limit'];
                $status['github']['core']['remaining'] = $response['resources']['core']['remaining'];
                $status['github']['core']['reset'] = DateTime::from($response['resources']['core']['reset']);

                $status['github']['search']['limit'] = $response['resources']['search']['limit'];
                $status['github']['search']['remaining'] = $response['resources']['search']['remaining'];
                $status['github']['search']['reset'] = DateTime::from($response['resources']['search']['reset']);
            }

            return $status;
        });

        // Fill template
        $this->template->status = $status;
    }
}
