<?php

namespace App\Modules\Front\Portal\Base\Controls\Componetters;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\EntityModel;
use App\Model\UI\BaseControl;

final class Componetters extends BaseControl
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

	public function render()
	{
		$this->template->componetters = $this->em->getRepositoryForEntity(Addon::class)
			->findBy(['state' => Addon::STATE_ACTIVE])
			->orderBy(['id' => 'DESC'])
			->fetchPairs('author');

		$this->template->setFile(__DIR__ . '/templates/componetters.latte');
		$this->template->render();
	}

}
