<?php

namespace App\Model\Tasks\Addons;

use App\Core\Utils\Validators;
use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\ORM\Github\Github;
use App\Model\WebServices\Github\Service;
use Nette\Utils\Strings;
use Nextras\Orm\Collection\ICollection;

final class GenerateContentTask extends BaseAddonTask
{

    /** @var Service */
    private $github;

    /**
     * @param AddonRepository $addonRepository
     * @param Service $github
     */
    public function __construct(AddonRepository $addonRepository, Service $github)
    {
        parent::__construct($addonRepository);
        $this->github = $github;
    }

    /**
     * @param array $args
     * @return bool
     */
    public function run(array $args = [])
    {
        /** @var ICollection|Addon[] $addons */
        $addons = $this->addonRepository->findActive();

        // FILTER PACKAGES ===========================================

        if (isset($args['rest']) && $args['rest'] === TRUE) {
            $addons = $addons->findBy(['this->github->content' => NULL]);
        }

        // DO YOUR JOB ===============================================

        $counter = 0;
        foreach ($addons as $addon) {
            // Raw
            $content = $this->github->readme($addon->owner, $addon->name, 'raw');
            if ($content) {
                // Content
                $addon->github->contentRaw = $content;
            } else {
                $addon->github->contentRaw = '';
                $this->log('Skip (content) [failed download raw content]: ' . $addon->fullname);
            }

            // HTML
            $content = $this->github->readme($addon->owner, $addon->name, 'html');
            if ($content) {
                // Content
                $addon->github->contentHtml = $content;
                $this->reformatLinks($addon->github);
            } else {
                $addon->github->contentHtml = '';
                $this->log('Skip (content) [failed download html content]: ' . $addon->fullname);
            }

            // Persist
            if ($addon->github->isModified()) {
                $this->addonRepository->persistAndFlush($addon);
            }

            // Increase counting
            $counter++;
        }

        return $counter;
    }

    /**
     * @param Github $github
     * @return void
     */
    protected function reformatLinks(Github $github)
    {
        $github->contentHtml = Strings::replace($github->contentHtml, '#href=\"(.*)\"#iU', function ($matches) use ($github) {
            list ($all, $url) = $matches;

            if (!Validators::isUrl($url)) {
                if (Validators::isUrlFragment($url)) {
                    $url = $github->linker->getFileUrl(NULL, $url);
                } else {
                    $url = $github->linker->getBlobUrl($url);
                }
            }

            return sprintf('href="%s"', $url);
        });
    }

}
