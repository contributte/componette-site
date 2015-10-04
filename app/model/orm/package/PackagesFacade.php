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
            ->limitBy(6);
    }

    /**
     * @return ICollection
     */
    public function findRecentlyPushed()
    {
        return $this->packages
            ->findActive()
            ->orderBy('this->metadata->pushed', 'DESC')
            ->limitBy(6);
    }

    /**
     * COMMON ******************************************************************
     */

    /**
     * @return ICollection
     */
    public function findAll()
    {
        $builder = $this->packages->findActive();
        $builder = $this->formatOrder($builder);
        return $builder;
    }

    /**
     * @param string $owner
     * @return ICollection
     */
    public function findByOwner($owner)
    {
        $builder = $this->packages->findBy(['this->metadata->owner' => $owner]);
        $builder = $this->formatOrder($builder);
        $builder = $this->formatLimit($builder);
        return $builder;
    }

    /**
     * @param string $q
     * @return ICollection
     */
    public function findByQuery($q)
    {
        $builder = $this->packages->search($q);
        $builder = $this->formatOrder($builder);
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
    protected function formatOrder(ICollection $collection)
    {
        $direction = $this->search->order ? 'DESC' : 'ASC';
        switch ($this->search->by) {
            case 'push':
                $collection = $collection->orderBy('this->metadata->pushed', $direction);
                break;
            case 'stars':
                $collection = $collection->orderBy('this->metadata->stars', $direction);
                break;
            case 'downloads':
                $collection = $collection->orderBy('this->metadata->downloads', $direction);
                break;
        }

        return $collection;
    }

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
