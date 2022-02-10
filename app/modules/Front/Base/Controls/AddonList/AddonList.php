<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList;

use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\Query\LatestActivityAddonsQuery;
use App\Model\Database\Query\LatestAddedAddonsQuery;
use App\Model\Database\Query\QueryObject;
use App\Model\Database\Query\SearchAddonsQuery;
use App\Model\UI\BaseControl;
use App\Modules\Front\Base\Controls\AddonList\Avatar\AvatarComponent;
use App\Modules\Front\Base\Controls\AddonList\Description\DescriptionComponent;
use App\Modules\Front\Base\Controls\AddonList\Name\NameComponent;
use App\Modules\Front\Base\Controls\AddonList\Statistics\StatisticsComponent;
use App\Modules\Front\Base\Controls\AddonMeta\AddonMeta;
use App\Modules\Front\Base\Controls\Layout\Box\BoxComponent;
use App\Modules\Front\Base\Controls\Layout\Box\BoxProps;
use App\Modules\Front\Base\Controls\Layout\Heading\HeadingComponent;
use Nette\Utils\Html;
use Wavevision\PropsControl\Helpers\Render;

class AddonList extends BaseControl
{

	use AvatarComponent;
	use BoxComponent;
	use DescriptionComponent;
	use HeadingComponent;
	use NameComponent;
	use StatisticsComponent;

	/**
	 * @var AddonRepository
	 * @inject
	 */
	public AddonRepository $addonRepository;

	/** @var QueryObject */
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
		$addons = $this->addonRepository->fetchEntities($this->queryObject);
		return Render::toHtml(
			$this->template
				->setParameters([
					'addons' => $addons,
					'title' => $this->renderTitle(count($addons)),
				])->renderToString(__DIR__ . '/templates/list.latte')
		);
	}

	private function renderTitle(int $addonsCount): ?Html
	{
		$query = get_class($this->queryObject);
		switch ($query) {
			case LatestActivityAddonsQuery::class:
				return Html::el()->setText('Latest updated addons');

			case LatestAddedAddonsQuery::class:
				return Html::el()->setText('Latest indexed addons');

			case SearchAddonsQuery::class:
				return $this->renderSearchTitle($addonsCount);
		}

		return null;
	}

	private function renderSearchTitle(int $addonsCount): Html
	{
		/** @var SearchAddonsQuery $query */
		$query = $this->queryObject;
		if ($author = $query->getAuthor()) {
			return Html::el()
				->addHtml(Html::el()->setText('By '))
				->addHtml(Html::el('strong')->setText($author));
		}

		if ($tag = $query->getTag()) {
			return Html::el()
				->addHtml(Html::el()->setText('Tagged by #'))
				->addHtml(Html::el('strong')->setText($tag));
		}

		return Html::el()
			->addHtml(Html::el()->setText('Searched for $'))
			->addHtml(Html::el('strong')->setText($query->getQuery()))
			->addHtml(Html::el('i')
				->setAttribute('class', 'ml-2 relative inline-block px-2 text-sm font-normal text-blue-700 bg-blue-100 rounded-full align-middle')
				->setText(sprintf('%d result%s', $addonsCount, $addonsCount > 1 ? 's' : '')));
	}

}
