<?php

namespace App\Model\ORM\Github;

use App\Model\Addons\Extra;
use App\Model\Addons\ExtraComposer;
use App\Model\Addons\Linker;
use App\Model\ORM\AbstractEntity;
use App\Model\ORM\Addon\Addon;
use Nette\Utils\DateTime;

/**
 * @property int $id                        {primary}
 * @property Addon $addon                   {1:1 Addon::$github, isMain=true}
 * @property string|NULL $description
 * @property string|NULL $readme            {enum self::README_*}
 * @property string|NULL $content
 * @property string|NULL $homepage
 * @property int|NULL $stars
 * @property int|NULL $watchers
 * @property int|NULL $issues
 * @property int|NULL $forks
 * @property int|NULL $releases
 * @property int|NULL $fork
 * @property string|NULL $language
 * @property Extra|NULL $extra
 * @property DateTime|NULL $createdAt
 * @property DateTime|NULL $pushedAt
 * @property DateTime|NULL $updatedAt
 * @property DateTime $crawledAt            {default now}
 *
 * @property Linker $linker                 {virtual}
 * @property ExtraComposer $composer        {virtual}
 */
class Github extends AbstractEntity
{

    const README_MARKDOWN = 'MARKDOWN';
    const README_TEXY = 'TEXY';
    const README_RAW = 'RAW';

    /** @var Linker */
    private $linker;

    /** @var ExtraComposer */
    private $composer;

    /**
     * VIRTUAL *****************************************************************
     */

    /**
     * @return Linker
     */
    protected function getterLinker()
    {
        if (!$this->linker) {
            $this->linker = new Linker($this->addon->owner, $this->addon->name);
        }

        return $this->linker;
    }

    /**
     * @return ExtraComposer
     */
    protected function getterComposer()
    {
        if (!$this->composer) {
            $this->composer = new ExtraComposer($this->extra->get('composer', []));
        }

        return $this->composer;
    }

    /**
     * EVENTS ******************************************************************
     */

    /**
     * Called on load entity from storage
     *
     * @param array $data
     * @return void
     */
    protected function onLoad(array $data)
    {
        $data['extra'] = new Extra($data['extra'] ? json_decode($data['extra'], TRUE) : []);

        parent::onLoad($data);
    }

    /**
     * Called before persist to storage
     *
     * @return void
     */
    protected function onBeforePersist()
    {
        parent::onBeforePersist();

        if (($extra = $this->getRawProperty('extra'))) {
            $this->setRawValue('extra', $extra->export());
        } else {
            $this->setRawValue('extra', NULL);
        }

        $this->crawledAt = new DateTime();
    }

}
