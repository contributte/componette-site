<?php

namespace App\Model\ORM;

use App\Model\Search\Search;
use Nextras\Orm\Collection\ICollection;

final class PackagesFacade
{

    /** @var PackagesRepository */
    private $packages;

    /** @var Search */
    private $search;

    /**
     * @param PackagesRepository $packages
     * @param Search $search
     */
    function __construct(
        PackagesRepository $packages,
        Search $search
    )
    {
        $this->packages = $packages;
        $this->search = $search;
    }

    /**
     * HOMEPAGE ****************************************************************
     */

    /**
     * @return ICollection
     */
    public function findNewests()
    {
        return $this->packages
            ->findActive()
            ->orderBy('created', 'DESC')
            ->limitBy(3);
    }

    /**
     * @return ICollection
     */
    public function findByLastActivity()
    {
        return $this->packages
            ->findActive()
            ->orderBy('this->metadata->pushed', 'DESC')
            ->limitBy(3);
    }


    /**
     * @return ICollection
     */
    public function findMostPopular()
    {
        return $this->packages
            ->findOrdered('popularity')
            ->limitBy(3);
    }

    /**
     * COMMON ******************************************************************
     */

    /**
     * @return ICollection
     */
    public function findAll()
    {
        $builder = $this->packages->findActive($this->search->by);

        return $builder;
    }

    /**
     * @param string $owner
     * @return ICollection
     */
    public function findByOwner($owner)
    {
        $builder = $this->packages->findOrdered($this->search->by)->findBy(['this->metadata->owner' => $owner]);
        $builder = $this->formatLimit($builder);
        return $builder;
    }

    /**
     * @param string $q
     * @return ICollection
     */
    public function findByQuery($q)
    {
        $builder = $this->packages->search($q, $this->search->by);
        $builder = $this->formatLimit($builder);
        return $builder;
    }

    /**
     * @param string $tag
     * @return ICollection
     */
    public function findByTag($tag)
    {
        $builder = $this->packages->findOrdered($this->search->by)->findBy(['this->tags->name' => $tag, 'state' => Package::STATE_ACTIVE]);
        $builder = $this->formatLimit($builder);

        return $builder;
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
