<?php

namespace App\Modules\Front\Controls\PackageModal;

use App\Core\UI\BaseControl;
use App\Model\ORM\Metadata;
use App\Model\ORM\Package;
use App\Model\ORM\PackagesRepository;
use App\Model\ORM\TagsRepository;
use Nette\Application\UI\Form;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;
use Nextras\Dbal\UniqueConstraintViolationException;

final class PackageModal extends BaseControl
{
    const GITHUB_REGEX = '^(?:https?:\/\/)?(?:www\.)?github\.com\/([\w\d-]+)\/([\w\d-]+)$';

    /** @var PackagesRepository */
    private $packagesRepository;

    /** @var TagsRepository */
    private $tagsRepository;


    /**
     * @param PackagesRepository $packagesRepository
     * @param TagsRepository $tagsRepository
     */
    function __construct(PackagesRepository $packagesRepository, TagsRepository $tagsRepository)
    {
        parent::__construct();
        $this->packagesRepository = $packagesRepository;
        $this->tagsRepository = $tagsRepository;
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
        $form->addText('package', 'Package URL')
            ->addRule($form::REQUIRED, 'URL is required')
            ->addRule($form::URL, 'URL is not valid')
            ->addRule($form::PATTERN, 'Only GitHub urls are allowed', self::GITHUB_REGEX);

        $tags = $this->tagsRepository->fetchPairs();
        $form->addMultiSelect('tags', 'Tags', $tags);

        $form->addSubmit('add', 'Add package');

        $form->onSuccess[] = function (Form $form) {
            $matches = Strings::match($form->values->package, '#' . self::GITHUB_REGEX . '#');

            if (!$matches) {
                $this->presenter->flashMessage('Invalid package name.', 'warning');
                $this->presenter->redirect('this');
                return;
            }

            $packageName = $matches[1] . '/' . $matches[2];

            $package = new Package();
            $package->state = Package::STATE_QUEUED;
            $package->created = new DateTime();
            $package->repository = $packageName;
            $package->metadata = new Metadata();
            foreach ($this->tagsRepository->findById($form->values->tags) as $tag) {
                $package->tags->add($tag);
            }

            try {
                $this->packagesRepository->persistAndFlush($package);
                $this->presenter->flashMessage('Package successful added to the process queue. Thank you.', 'info');
            } catch (UniqueConstraintViolationException $e) {
                $this->presenter->flashMessage('There is already package "' . $packageName . '" in our database.', 'warning');
            } catch (\PDOException $e) {
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
