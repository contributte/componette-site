<?php

namespace App\Model\Facade;

use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\ORM\Tag\TagRepository;
use App\Model\Search\Search;
use Nextras\Orm\Collection\ICollection;

final class SearchFacade
{

    /** @var AddonRepository */
    private $addonRepository;

    /** @var TagRepository */
    private $tagRepository;

    /** @var Search */
    private $search;

    /**
     * @param AddonRepository $addonRepository
     * @param TagRepository $tagRepository
     * @param Search $search
     */
    public function __construct(AddonRepository $addonRepository, TagRepository $tagRepository, Search $search)
    {
        $this->addonRepository = $addonRepository;
        $this->tagRepository = $tagRepository;
        $this->search = $search;
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findAll()
    {
        $collection = $this->addonRepository->findActive();
        $collection = $this->formatLimit($collection);

        return $collection;
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findCategories()
    {
        return $this->tagRepository->findAll();
    }

    /**
     * @return ICollection|Addon[]
     */
    public function findSorted($by)
    {
        $this->search->by = $by;

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
            ->findOrdered('popularity')
            ->findBy(['owner' => $owner]);

        $collection = $this->formatLimit($collection);

        return $collection;
    }

    /**
     * @param string $q
     * @return ICollection|Addon[]
     */
    public function findByOwnerOrName($q)
    {
        $collection = $this->addonRepository
            ->findByOwnerOrName($q, 'popularity');

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
            ->findOrdered('popularity')
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
