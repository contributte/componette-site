<?php

namespace App\Model\Facade;

use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\Search\Search;
use Nextras\Orm\Collection\ICollection;

final class AddonFacade
{

    /** @var AddonRepository */
    private $addonRepository;

    /** @var Search */
    private $search;

    /**
     * @param AddonRepository $addonRepository
     * @param Search $search
     */
    public function __construct(AddonRepository $addonRepository, Search $search)
    {
        $this->addonRepository = $addonRepository;
        $this->search = $search;
    }

    /**
     * @param int $id
     * @return Addon|NULL
     */
    public function getById($id)
    {
        return $this->addonRepository->getById($id);
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findActive()
    {
        return $this->addonRepository
            ->findActive();
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findActiveOwners()
    {
        return $this->findActive()
            ->fetchPairs('owner');
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findNewest()
    {
        return $this->addonRepository
            ->findActive()
            ->orderBy('createdAt', 'DESC')
            ->limitBy(3);
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findByLastActivity()
    {
        return $this->addonRepository
            ->findActive()
            ->orderBy('this->github->pushedAt', 'DESC')
            ->limitBy(3);
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findMostPopular()
    {
        return $this->addonRepository
            ->findOrdered('popularity')
            ->limitBy(3);
    }

}
