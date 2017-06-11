<?php

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
	 *
	 * @return void
	 */
	public function renderDefault()
	{
		$this->template->addons = $this->addonFacade
			->findAll()
			->orderBy('author', 'ASC');
	}

	/**
	 * @param int $id
	 * @return void
	 */
	public function actionDetail($id)
	{
		$this->addon = $addon = $this->addonFacade->getById($id);
		if (!$this->addon) {
			$this->error('No addon');
		}

		$this['addonForm']->setDefaults([
			'author' => $addon->author,
			'name' => $addon->name,
			'tags' => (array) array_keys($this->addon->tags->get()->fetchPairs('id', 'id')),
		]);
	}

	/**
	 * @return void
	 */
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

	/**
	 * @param Form $form
	 * @return void
	 */
	public function processAddonForm(Form $form)
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

}
