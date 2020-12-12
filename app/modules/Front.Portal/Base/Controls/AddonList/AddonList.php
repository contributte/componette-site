<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonList;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\UI\BaseControl;
use App\Modules\Front\Portal\Base\Controls\AddonMeta\AddonMeta;
use Nextras\Orm\Collection\ICollection;

class AddonList extends BaseControl
{

	/**
	 * @var ICollection|Addon[]
	 * @phpstan-var ICollection<Addon>
	 */
	protected $addons;

	/**
	 * @phpstan-param ICollection<Addon> $addons
	 */
	public function __construct(ICollection $addons)
	{
		$this->addons = $addons;
	}

	protected function createComponentMeta(): AddonMeta
	{
		return new AddonMeta();
	}

	public function render(): void
	{
		$this->template->addons = $this->addons;

		$this->template->setFile(__DIR__ . '/templates/list.latte');
		$this->template->render();
	}

}
