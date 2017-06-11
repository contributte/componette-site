<?php

namespace App\Modules\Front\Portal\Base\Controls\AddonList;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\EntityModel;
use App\Model\Database\ORM\Tag\Tag;
use App\Model\UI\BaseControl;
use App\Modules\Front\Portal\Base\Controls\AddonMeta\AddonMeta;
use Nextras\Orm\Collection\ICollection;

final class CategorizedAddonList extends BaseControl
{

	/** @var EntityModel */
	private $em;

	/**
	 * @param EntityModel $em
	 */
	public function __construct(EntityModel $em)
	{
		parent::__construct();
		$this->em = $em;
	}

	/**
	 * CONTROLS ****************************************************************
	 */

	/**
	 * @return AddonMeta
	 */
	protected function createComponentMeta()
	{
		return new AddonMeta();
	}

	/**
	 * DATA ********************************************************************
	 */

	/**
	 * @return Tag[]
	 */
	protected function getTags()
	{
		return $this->em->getRepositoryForEntity(Tag::class)
			->findAll()
			->orderBy(['name' => 'ASC'])
			->fetchPairs('id');
	}

	/**
	 * @return ICollection|Addon[]
	 */
	protected function getAddons()
	{
		return $this->em->getRepositoryForEntity(Addon::class)
			->findBy(['state' => Addon::STATE_ACTIVE])
			->orderBy(['author' => 'ASC', 'name' => 'ASC']);
	}

	/**
	 * RENDER ******************************************************************
	 */

	/**
	 * Render component
	 *
	 * @return void
	 */
	public function render()
	{
		$categories = $this->getTags();
		$addons = $this->getAddons();

		// Arrange addons
		$tmplist = [];
		foreach ($addons as $addon) {
			if ($addon->tags->countStored() > 0) {
				foreach ($addon->tags as $tag) {
					if (!isset($tmplist[$tag->id])) $tmplist[$tag->id] = [];
					$tmplist[$tag->id][] = $addon;
				}
			} else {
				if (!isset($tmplist[-1])) $tmplist[-1] = [];
				$tmplist[-1][] = $addon;
			}
		}

		// Sort addons by categories priority
		$list = [];
		foreach ($categories as $category) {
			if (isset($tmplist[$category->id])) {
				$list[$category->id] = $tmplist[$category->id];
			}
		}

		// Append no tags addons
		if (isset($tmplist[-1])) {
			$list[-1] = $tmplist[-1];
			$other = new Tag();
			$other->id = -1;
			$other->name = 'other';
			$categories[-1] = $other;
		}

		// Fill template
		$this->template->categories = $categories;
		$this->template->list = $list;

		// Render
		$this->template->setFile(__DIR__ . '/templates/categorized.list.latte');
		$this->template->render();
	}

}
