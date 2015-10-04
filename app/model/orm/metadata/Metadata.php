<?php

namespace App\Model\ORM;

use App\Model\Packages\Composer;
use App\Model\Packages\Extra;
use App\Model\Packages\Linker;
use Nette\Utils\DateTime;

/**
 * @property Package        $package        {1:1d Package::$metadata primary}
 * @property string|NULL    $owner
 * @property string|NULL    $name
 * @property string|NULL    $readme
 * @property string|NULL    $description
 * @property string|NULL    $homepage
 * @property int|NULL       $stars
 * @property int|NULL       $downloads
 * @property int|NULL       $watchers
 * @property int|NULL       $issues
 * @property int|NULL       $forks
 * @property int|NULL       $releases
 * @property array|NULL     $tags
 * @property string|NULL    $content
 * @property DateTime|NULL  $created
 * @property DateTime|NULL  $pushed
 * @property DateTime|NULL  $updated
 * @property DateTime|NULL  $cronChanged
 * @property Extra|NULL     $extra
 * @property Linker         $linker         {virtual}
 * @property Composer\Data  $composer       {virtual}
 */
class Metadata extends AbstractEntity
{

    /** @var Linker */
    private $linker;

    /** @var Composer\Data */
    private $composer;

    /**
     * @param mixed $tags
     * @return array
     */
    protected function setterTags($tags)
    {
        if (!$tags) return [];
        if (is_array($tags)) return $tags;
        return explode(',', $tags);
    }

    /**
     * VIRTUAL *****************************************************************
     */

    /**
     * @return Linker
     */
    protected function getterLinker()
    {
        if (!$this->linker) {
            $this->linker = new Linker($this->package->repository, $this->owner);
        }

        return $this->linker;
    }

    /**
     * @return Composer\Data
     */
    protected function getterComposer()
    {
        if (!$this->composer) {
            $this->composer = new Composer\Data($this->extra->get('composer', []));
        }

        return $this->composer;
    }

    /**
     * EVENTS ******************************************************************
     */

    /**
     * @param array $data
     */
    protected function onLoad(array $data)
    {
        $data['extra'] = new Extra($data['extra'] ? json_decode($data['extra'], TRUE) : []);
        parent::onLoad($data);
    }

    protected function onBeforePersist()
    {
        parent::onBeforePersist();
        if (($extra = $this->getRawProperty('extra'))) {
            $this->setRawValue('extra', $extra->export());
        } else {
            $this->setRawValue('extra', NULL);
        }
        $this->setRawValue('tags', $this->tags ? implode(',', $this->tags) : NULL);
    }

}
