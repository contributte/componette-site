<?php

namespace App\Modules\Front\Portal\Controls\AddonModal;

use App\Core\UI\BaseControl;
use App\Model\ORM\Addon\Addon;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Nextras\Dbal\UniqueConstraintViolationException;
use PDOException;

final class AddonModal extends BaseControl
{

    /** @var AddonModalModel */
    private $model;

    /**
     * @param AddonModalModel $model
     */
    public function __construct(AddonModalModel $model)
    {
        parent::__construct();
        $this->model = $model;
    }

    /**
     * FORMS *******************************************************************
     */

    /**
     * @return Form
     */
    protected function createComponentForm()
    {
        $form = new Form();
        $form->addText('addon', 'Addon URL')
            ->addRule($form::REQUIRED, 'URL is required')
            ->addRule($form::URL, 'URL is not valid')
            ->addRule($form::PATTERN, 'Only GitHub urls are allowed', Addon::GITHUB_REGEX);

        $tags = $this->model->getTags();
        $form->addMultiSelect('tags', 'Tags', $tags);

        $form->addSubmit('add', 'Add addon');

        $form->onSuccess[] = function (Form $form) {
            $matches = Strings::match($form->values->addon, '#' . Addon::GITHUB_REGEX . '#');
            if (!$matches) {
                $this->presenter->flashMessage('Invalid addon name.', 'warning');
                $this->presenter->redirect('this');

                return;
            }

            list ($all, $owner, $name) = $matches;

            $addon = $this->model->createAddon();
            $addon->state = Addon::STATE_QUEUED;
            $addon->createdAt = new DateTime();
            $addon->owner = $owner;
            $addon->name = $name;
            if ($form->values->tags) {
                $addon->tags->add($form->values->tags);
            }

            try {
                $this->model->persist($addon);
                $this->presenter->flashMessage('Addon successful added to the cron process queue. Thank you.', 'info');
            } catch (UniqueConstraintViolationException $e) {
                $this->presenter->flashMessage('There is already addon "' . ($owner . '/' . $name) . '" in our database.', 'warning');
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

    public function render()
    {
        $this->template->setFile(__DIR__ . '/templates/modal.latte');
        $this->template->render();
    }

}
