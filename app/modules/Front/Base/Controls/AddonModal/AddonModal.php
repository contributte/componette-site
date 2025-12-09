<?php declare(strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonModal;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Tag\Tag;
use App\Model\Database\ORM\Tag\TagRepository;
use App\Model\UI\BaseControl;
use App\Modules\Front\Base\Controls\Svg\SvgComponent;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;
use PDOException;

final class AddonModal extends BaseControl
{

	use SvgComponent;

	/** @var callable[] */
	public $onSuccess = [];

	private EntityManagerInterface $em;

	private TagRepository $tagRepository;

	public function __construct(EntityManagerInterface $em, TagRepository $tagRepository)
	{
		$this->em = $em;
		$this->tagRepository = $tagRepository;
	}

	protected function createComponentForm(): Form
	{
		$form = new Form();
		$form->addText('addon', 'Component URL')
			->addRule($form::REQUIRED, 'URL is required')
			->addRule($form::URL, 'URL is not valid')
			->addRule($form::PATTERN, 'Only GitHub urls are allowed', Addon::GITHUB_REGEX);

		$tags = [];
		foreach ($this->tagRepository->findAll() as $tag) {
			$tags[$tag->getId()] = $tag->getName();
		}

		$form->addMultiSelect('tags', 'Tags', $tags);

		$form->addSubmit('add', 'Add addon');

		$form->onSuccess[] = function (Form $form): void {
			$matches = Strings::match($form->values->addon, '#' . Addon::GITHUB_REGEX . '#');
			if (!$matches) {
				$this->presenter->flashMessage('Invalid addon name.', 'warning');
				$this->presenter->redirect('this');
			}

			[, $owner, $name] = $matches;

			$addon = new Addon($owner, $name);
			$addon->setState(Addon::STATE_QUEUED);

			if ($form->values->tags) {
				foreach ($form->values->tags as $tagId) {
					$tag = $this->tagRepository->find($tagId);
					if ($tag instanceof Tag) {
						$addon->addTag($tag);
					}
				}
			}

			try {
				$this->em->persist($addon);
				$this->em->flush();
				$this->presenter->flashMessage('Addon successful added to the cron process queue. Thank you.', 'info');
			} catch (UniqueConstraintViolationException $e) {
				$this->presenter->flashMessage(sprintf('There is already addon %s/%s in our database.', $owner, $name), 'warning');
			} catch (PDOException $e) {
				$this->presenter->flashMessage('Database error has occurred.', 'danger');
			}

			$this->onSuccess($this);
		};

		return $form;
	}

	/**
	 * Render component
	 */
	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/templates/modal.latte');
		$this->template->render();
	}

}
