<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Model\Database\ORM\Tag\Tag;
use App\Model\Database\ORM\Tag\TagRepository;
use App\Model\UI\BaseControl;
use App\Modules\Front\Base\Controls\AddonList\Avatar\AvatarComponent;
use App\Modules\Front\Base\Controls\AddonList\Description\DescriptionComponent;
use App\Modules\Front\Base\Controls\AddonList\Name\NameComponent;
use App\Modules\Front\Base\Controls\AddonList\Statistics\StatisticsComponent;
use App\Modules\Front\Base\Controls\AddonMeta\AddonMeta;
use App\Modules\Front\Base\Controls\Layout\Box\BoxComponent;
use Nette\Utils\Html;

final class CategorizedAddonList extends BaseControl
{

	use AvatarComponent;
	use BoxComponent;
	use DescriptionComponent;
	use NameComponent;
	use StatisticsComponent;

	private TagRepository $tagRepository;

	private AddonRepository $addonRepository;

	public function __construct(TagRepository $tagRepository, AddonRepository $addonRepository)
	{
		$this->tagRepository = $tagRepository;
		$this->addonRepository = $addonRepository;
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
	 * @return array<int, Tag>
	 */
	protected function getTags(): array
	{
		$tags = $this->tagRepository->findBy([], ['name' => 'ASC']);
		$result = [];
		foreach ($tags as $tag) {
			$result[$tag->getId()] = $tag;
		}
		return $result;
	}

	/**
	 * @return Addon[]
	 */
	protected function getAddons(): array
	{
		return $this->addonRepository->findBy(
			['state' => Addon::STATE_ACTIVE],
			['author' => 'ASC', 'name' => 'ASC']
		);
	}

	public function render(): void
	{
		$this->getBoxComponent()->render($this->renderContent());
	}

	private function renderContent(): Html
	{
		$categories = $this->getTags();
		$addons = $this->getAddons();

		// Arrange addons
		$tmplist = [];
		foreach ($addons as $addon) {
			if ($addon->getTags()->count() > 0) {
				foreach ($addon->getTags() as $tag) {
					if (!isset($tmplist[$tag->getId()])) {
						$tmplist[$tag->getId()] = [];
					}

					$tmplist[$tag->getId()][] = $addon;
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
			if (isset($tmplist[$category->getId()])) {
				$list[$category->getId()] = $tmplist[$category->getId()];
			}
		}

		// Append no tags addons
		if (isset($tmplist[-1])) {
			$list[-1] = $tmplist[-1];
			$other = new Tag('other');
			$categories[-1] = $other;
		}

		// Fill template
		$this->template->categories = $categories;
		$this->template->list = $list;
		$this->template->title = null;
		// Render
		return Html::el()->setHtml($this->template->renderToString(__DIR__ . '/templates/categorized.list.latte'));
	}

}
