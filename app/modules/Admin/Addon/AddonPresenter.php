<?php declare(strict_types = 1);

namespace App\Modules\Admin\Addon;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Facade\Admin\AddonFacade;
use App\Modules\Admin\Secured\SecuredPresenter;
use Nette\Application\UI\Form;
use PDOException;

final class AddonPresenter extends SecuredPresenter
{

	/** @var AddonFacade @inject */
	public $addonFacade;

	/** @var Addon */
	private $addon;

	/**
	 * List all addons
	 */
	public function renderDefault(): void
	{
		$this->template->addons = $this->addonFacade
			->findAll()
			->orderBy('author', 'ASC');
	}

	public function actionDetail(int $id): void
	{
		$this->addon = $this->getAddon($id);

		$this['addonForm']->setDefaults([
			'author' => $this->addon->author,
			'name' => $this->addon->name,
			'tags' => array_keys($this->addon->tags->get()->fetchPairs('id', 'id')),
		]);
	}

	public function renderDetail(): void
	{
		$this->template->addon = $this->addon;
	}

	protected function createComponentAddonForm(): Form
	{
		$form = new Form();

		$form->addText('author', 'Owner')
			->setRequired();

		$form->addText('name', 'Repository')
			->setRequired();

		$form->addMultiSelect('tags', 'Tags')
			->setItems($this->addonFacade->findTags()->fetchPairs('id', 'name'))
			->setRequired('At least one tag is required');

		$form->addSubmit('save', 'Update');

		$form->onSuccess[] = [$this, 'processAddonForm'];

		return $form;
	}

	public function processAddonForm(Form $form): void
	{
		$values = $form->getValues();

		// Update basic data
		$this->addon->author = $values->author;
		$this->addon->name = $values->name;

		// Update tags
		$this->addon->tags->set($values->tags);

		try {
			// Save addon
			$this->addonFacade->update($this->addon);
			$this->flashMessage('Addon sucessful updated.', 'success');
			$this->redirect('this');
		} catch (PDOException $e) {
			$this->flashMessage(sprintf('Updating addon failed (%s).', $e->getMessage()), 'danger');
		}
	}

	private function getAddon(int $id): Addon
	{
		if (!($addon = $this->addonFacade->getById($id))) {
			$this->error('Addon not found');
		}

		return $addon;
	}

}
