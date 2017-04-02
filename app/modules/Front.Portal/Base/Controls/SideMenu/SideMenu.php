<?php

namespace App\Modules\Front\Portal\Controls\SideMenu;

use App\Model\Database\ORM\EntityModel;
use App\Model\Database\ORM\Tag\Tag;
use Nette\Application\UI\Control;

final class SideMenu extends Control
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
	 * RENDER ******************************************************************
	 */

	/**
	 * Render siderbar
	 *
	 * @return void
	 */
	public function render(): void
	{
		$items = $this->em->getRepositoryForEntity(Tag::class)->findAll()->fetchAll();
		usort($items, function($a, $b) {
			return $a->addons->countStored() < $b->addons->countStored();
		});
		$this->template->items = $items;
		$this->template->setFile(__DIR__ . '/templates/menu.latte');
		$this->template->render();
	}

}
