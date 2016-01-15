<?php

namespace App\Model\Facade;

use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\Search\Search;
use Nextras\Orm\Collection\ICollection;

final class SearchFacade
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
     * @return ICollection|Addon[]
     */
    public function findAll()
    {
        $collection = $this->addonRepository
            ->findOrdered($this->search->by);

        $collection = $this->formatLimit($collection);

        return $collection;
    }

    /**
     * @param string $owner
     * @return ICollection|Addon[]
     */
    public function findByOwner($owner)
    {
        $collection = $this->addonRepository
            ->findOrdered($this->search->by)
            ->findBy(['owner' => $owner]);

        $collection = $this->formatLimit($collection);

        return $collection;
    }

    /**
     * @param string $q
     * @return ICollection|Addon[]
     */
    public function findByQuery($q)
    {
        $collection = $this->addonRepository
            ->findByQuery($q, 'popularity');

        $collection = $this->formatLimit($collection);

        return $collection;
    }

    /**
     * @param string $tag
     * @return ICollection|Addon[]
     */
    public function findByTag($tag)
    {
        $collection = $this->addonRepository
            ->findOrdered($this->search->by)
            ->findBy([
                'this->tags->name' => $tag,
                'state' => Addon::STATE_ACTIVE
            ]);

        $collection = $this->formatLimit($collection);

        return $collection;
    }

    /**
     * HELPERS *****************************************************************
     */

    /**
     * @param ICollection $collection
     * @return ICollection
     */
    protected function formatLimit(ICollection $collection)
    {
        if ($this->search->limit) {
            $collection = $collection->limitBy($this->search->limit);
        }

        return $collection;
    }

}
