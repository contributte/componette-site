<?php

namespace App\Modules\Admin;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Facade\Admin\AddonFacade;
use Nette\Application\UI\Form;

final class AddonPresenter extends SecuredPresenter
{

	/** @var AddonFacade @inject */
	public $addonFacade;

	/** @var Addon */
	private $addon;

	/**
	 * List all addons
	 */
	public function renderDefault()
	{
		$this->template->addons = $this->addonFacade
			->findAll()
			->orderBy('owner', 'ASC');
	}

	/**
	 * @param int $id
	 */
	public function actionDetail($id)
	{
		$this->addon = $addon = $this->addonFacade->getById($id);
		if (!$this->addon) {
			$this->error('No addon');
		}

		$this['addonForm']->setDefaults([
			'owner' => $addon->owner,
			'name' => $addon->name,
			'tags' => (array) array_keys($this->addon->tags->get()->fetchPairs('id', 'id')),
		]);
	}

	public function renderDetail()
	{
		$this->template->addon = $this->addon;
	}

	/**
	 * @return Form
	 */
	protected function createComponentAddonForm()
	{
		$form = new Form();

		$form->addText('owner', 'Owner')
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

	/**
	 * @param Form $form
	 */
	public function processAddonForm(Form $form)
	{
		$values = $form->getValues();

		// Update basic data
		$this->addon->owner = $values->owner;
		$this->addon->name = $values->name;

		// Update tags
		$this->addon->tags->set($values->tags);

		try {
			// Save addon
			$this->addonFacade->update($this->addon);
			$this->flashMessage('Addon sucessful updated.', 'success');
			$this->redirect('this');
		} catch (\PDOException $e) {
			$this->flashMessage(sprintf('Updating addon failed (%s).', $e->getMessage()), 'danger');
		}
	}

}
