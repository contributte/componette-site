<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonModal;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\EntityModel;
use App\Model\Database\ORM\Tag\Tag;
use App\Model\UI\BaseControl;
use Nette\Application\UI\Form;
use Nette\Utils\Strings;
use Nextras\Dbal\UniqueConstraintViolationException;
use Nextras\Dbal\Utils\DateTimeImmutable;
use PDOException;

final class AddonModal extends BaseControl
{

	/** @var EntityModel */
	private $em;

	public function __construct(EntityModel $em)
	{
		parent::__construct();
		$this->em = $em;
	}

	/**
	 * FORMS *******************************************************************
	 */

	protected function createComponentForm(): Form
	{
		$form = new Form();
		$form->addText('addon', 'Component URL')
			->addRule($form::REQUIRED, 'URL is required')
			->addRule($form::URL, 'URL is not valid')
			->addRule($form::PATTERN, 'Only GitHub urls are allowed', Addon::GITHUB_REGEX);

		$tags = $this->em->getRepositoryForEntity(Tag::class)
			->findAll()
			->fetchPairs('id', 'name');

		$form->addMultiSelect('tags', 'Tags', $tags);

		$form->addSubmit('add', 'Add addon');

		$form->onSuccess[] = function (Form $form): void {
			$matches = Strings::match($form->values->addon, '#' . Addon::GITHUB_REGEX . '#');
			if (!$matches) {
				$this->presenter->flashMessage('Invalid addon name.', 'warning');
				$this->presenter->redirect('this');

				return;
			}

			[$all, $owner, $name] = $matches;

			$addonRepository = $this->em->getRepositoryForEntity(Addon::class);
			$addon = new Addon();
			$addonRepository->attach($addon);
			$addon->state = Addon::STATE_QUEUED;
			$addon->createdAt = new DateTimeImmutable();
			$addon->author = $owner;
			$addon->name = $name;
			if ($form->values->tags) {
				$addon->tags->add($form->values->tags);
			}

			try {
				$this->em->persistAndFlush($addon);
				$this->presenter->flashMessage('Addon successful added to the cron process queue. Thank you.', 'info');
			} catch (UniqueConstraintViolationException $e) {
				$this->presenter->flashMessage(sprintf('There is already addon %s/%s in our database.', $owner, $name), 'warning');
			} catch (PDOException $e) {
				$this->presenter->flashMessage('Database error has occurred.', 'danger');
			}

			$this->presenter->redirect('this');
		};

		return $form;
	}

	/**
	 * RENDER ******************************************************************
	 */

	/**
	 * Render component
	 */
	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/templates/modal.latte');
		$this->template->render();
	}

}
