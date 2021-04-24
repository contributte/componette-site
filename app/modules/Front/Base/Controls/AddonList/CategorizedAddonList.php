<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\EntityModel;
use App\Model\Database\ORM\Tag\Tag;
use App\Model\UI\BaseControl;
use App\Modules\Front\Base\Controls\AddonList\Avatar\AvatarComponent;
use App\Modules\Front\Base\Controls\AddonList\Description\DescriptionComponent;
use App\Modules\Front\Base\Controls\AddonList\Name\NameComponent;
use App\Modules\Front\Base\Controls\AddonList\Statistics\StatisticsComponent;
use App\Modules\Front\Base\Controls\AddonMeta\AddonMeta;
use App\Modules\Front\Base\Controls\Layout\Box\BoxComponent;
use App\Modules\Front\Base\Controls\Layout\Box\BoxProps;
use Nette\Utils\Html;
use Nextras\Orm\Collection\ICollection;
use Wavevision\PropsControl\Helpers\Render;

final class CategorizedAddonList extends BaseControl
{

	use AvatarComponent;
	use BoxComponent;
	use DescriptionComponent;
	use NameComponent;
	use StatisticsComponent;

	/** @var EntityModel */
	private $em;

	public function __construct(EntityModel $em)
	{
		$this->em = $em;
	}

	/**
	 * CONTROLS ****************************************************************
	 */

	/**
	 * Creates meta component
	 */
	protected function createComponentMeta(): AddonMeta
	{
		return new AddonMeta();
	}

	/**
	 * DATA ********************************************************************
	 */

	/**
	 * @return Tag[]
	 */
	protected function getTags(): array
	{
		return $this->em->getRepositoryForEntity(Tag::class)
			->findAll()
			->orderBy(['name' => 'ASC'])
			->fetchPairs('id');
	}

	/**
	 * @return ICollection|Addon[]
	 */
	protected function getAddons(): ICollection
	{
		return $this->em->getRepositoryForEntity(Addon::class)
			->findBy(['state' => Addon::STATE_ACTIVE])
			->orderBy(['author' => 'ASC', 'name' => 'ASC']);
	}

	public function render(): void
	{
		$this->getBoxComponent()->render(new BoxProps([BoxProps::CONTENT => $this->renderContent()]));
	}

	private function renderContent(): Html
	{
		$categories = $this->getTags();
		$addons = $this->getAddons();

		// Arrange addons
		$tmplist = [];
		foreach ($addons as $addon) {
			if ($addon->tags->countStored() > 0) {
				foreach ($addon->tags as $tag) {
					if (!isset($tmplist[$tag->id])) {
						$tmplist[$tag->id] = [];
					}

					$tmplist[$tag->id][] = $addon;
				}
			} else {
				if (!isset($tmplist[-1])) {
					$tmplist[-1] = [];
				}

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
		$this->template->title = null;
		// Render
		return Render::toHtml($this->template->renderToString(__DIR__ . '/templates/categorized.list.latte'));
	}

}
