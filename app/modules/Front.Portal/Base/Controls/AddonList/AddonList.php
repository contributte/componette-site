<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonList;

use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\Query\LatestActivityAddonsQuery;
use App\Model\Database\Query\LatestAddedAddonsQuery;
use App\Model\Database\Query\QueryObject;
use App\Model\UI\BaseControl;
use App\Modules\Front\Portal\Base\Controls\AddonMeta\AddonMeta;
use App\Modules\Front\Portal\Base\Controls\Layout\Box\BoxComponent;
use App\Modules\Front\Portal\Base\Controls\Layout\Box\BoxProps;
use Nette\Utils\Html;
use Wavevision\PropsControl\Helpers\Render;

class AddonList extends BaseControl
{

	use BoxComponent;

	/**
	 * @var AddonRepository
	 * @inject
	 */
	public AddonRepository $addonRepository;

	/**
	 * @var QueryObject
	 */
	private QueryObject $queryObject;

	public function __construct(QueryObject $queryObject)
	{
		$this->queryObject = $queryObject;
	}

	protected function createComponentMeta(): AddonMeta
	{
		return new AddonMeta();
	}

	public function render(): void
	{
		$this->getBoxComponent()->render(new BoxProps([BoxProps::CONTENT => $this->renderContent()]));
	}

	private function renderContent(): Html
	{
		return Render::toHtml(
			$this->template
				->setParameters([
					'addons' => $this->addonRepository->fetchEntities($this->queryObject),
					'title' => $this->renderTitle(),
				])->renderToString(__DIR__ . '/templates/list.latte')
		);
	}

	private function renderTitle(): ?string
	{
		$query = get_class($this->queryObject);
		switch ($query) {
			case LatestActivityAddonsQuery::class:
				return 'Latest updated addons';
			case LatestAddedAddonsQuery::class:
				return 'Latest indexed addons';
		}
		return null;
	}

}
