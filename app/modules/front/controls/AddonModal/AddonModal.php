<?php

namespace App\Modules\Front\Controls\AddonModal;

use App\Core\UI\BaseControl;
use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\ORM\Github\Github;
use App\Model\ORM\Tag\TagRepository;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Nextras\Dbal\UniqueConstraintViolationException;
use PDOException;

final class AddonModal extends BaseControl
{

    const GITHUB_REGEX = '^(?:https?:\/\/)?(?:www\.)?github\.com\/([\w\d-]+)\/([\w\d-]+)$';

    /** @var AddonRepository */
    private $addonRepository;

    /** @var TagRepository */
    private $tagRepository;

    /**
     * @param AddonRepository $addonRepository
     * @param TagRepository $tagRepository
     */
    public function __construct(AddonRepository $addonRepository, TagRepository $tagRepository)
    {
        parent::__construct();
        $this->addonRepository = $addonRepository;
        $this->tagRepository = $tagRepository;
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
            ->addRule($form::PATTERN, 'Only GitHub urls are allowed', self::GITHUB_REGEX);

        $tags = $this->tagRepository->fetchPairs();
        $form->addMultiSelect('tags', 'Tags', $tags);

        $form->addSubmit('add', 'Add addon');

        $form->onSuccess[] = function (Form $form) {
            $matches = Strings::match($form->values->addon, '#' . self::GITHUB_REGEX . '#');
            if (!$matches) {
                $this->presenter->flashMessage('Invalid addon name.', 'warning');
                $this->presenter->redirect('this');
                return;
            }

            list ($all, $owner, $name) = $matches;

            $addon = new Addon();
            $this->addonRepository->attach($addon);

            $addon->state = Addon::STATE_QUEUED;
            $addon->createdAt = new DateTime();
            $addon->owner = $owner;
            $addon->name = $name;
            $addon->github = new Github();
            if ($form->values->tags) {
                $addon->tags->add($form->values->tags);
            }

            try {
                $this->addonRepository->persistAndFlush($addon);
                $this->presenter->flashMessage('Addon successful added to the process queue. Thank you.', 'info');
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
