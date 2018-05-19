<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\Componetters;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\EntityModel;
use App\Model\Database\Query\ComponettersQuery;
use App\Model\UI\BaseControl;
use Contributte\Nextras\Orm\QueryObject\Queryable;

final class Componetters extends BaseControl
{

	/** @var EntityModel */
	private $em;

	public function __construct(EntityModel $em)
	{
		parent::__construct();
		$this->em = $em;
	}

	/**
	 * RENDER ******************************************************************
	 */

	/**
	 * Render component
	 */
	public function render(): void
	{
		$this->template->componetters = function () {
			return $this->em->getRepositoryForEntity(Addon::class)->fetch(new ComponettersQuery(), Queryable::HYDRATION_ENTITY);
		};

		$this->template->setFile(__DIR__ . '/templates/componetters.latte');
		$this->template->render();
	}

}
