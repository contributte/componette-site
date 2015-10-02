<?php

namespace App\Model\ORM;

use App\Model\Packages\Composer;
use App\Model\Packages\Content\Content;
use App\Model\Packages\Content\ContentManager;
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
 * @property string|NULL    $tags
 * @property DateTime|NULL  $created
 * @property DateTime|NULL  $changed
 * @property DateTime|NULL  $pushed
 * @property DateTime|NULL  $updated
 * @property string|NULL    $content
 * @property string|NULL    $extra
 * @property Linker         $linker         {virtual}
 * @property Composer\Data  $composer       {virtual}
 * @property array          $tagList        {virtual}
 */
class Metadata extends AbstractEntity
{

    /** @var Linker */
    private $linker;

    /** @var Composer\Data */
    private $composer;

    /** @var Content */
    private $content;

    /**
     * @param ContentManager $manager
     */
    public function injectPrimary(ContentManager $manager)
    {
        $this->content = new Content($manager);
    }

    /**
     * @param mixed $tags
     * @return array
     */
    protected function getterTagList()
    {
        if ($this->tags) {
            return explode(',', $this->tags);
        }
        return [];
    }

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
            $this->composer = new Composer\Data($this->extra);
        }

        return $this->composer;
    }

}
