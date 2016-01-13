<?php

namespace App\Model\Tasks\Addons;

use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\WebServices\Github\Service;
use Nette\Utils\DateTime;
use Nextras\Orm\Collection\ICollection;

final class UpdateGithubFilesTask extends BaseAddonTask
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
     * @return int
     */
    public function run(array $args = [])
    {
        /** @var ICollection|Addon[] $addons */
        $addons = $this->addonRepository->findActive();

        // FILTER ADDONS =============================================

        if (isset($args['rest']) && $args['rest'] === TRUE) {
            $addons = $addons->findBy(['this->github->extra' => NULL]);
        } else if (isset($args['type'])) {
            switch ($args['type']) {
                case 'composer':
                    $addons = $addons->findBy(['type' => Addon::TYPE_COMPOSER]);
                    break;
                case 'bower':
                    $addons = $addons->findBy(['type' => Addon::TYPE_BOWER]);
                    break;
                case 'unknown':
                    $addons = $addons->findBy(['type' => Addon::TYPE_UNKNOWN]);
                    break;
            }
        }

        // DO YOUR JOB ===============================================

        $counter = 0;
        foreach ($addons as $addon) {
            // Readme
            if (($response = $this->github->readme($addon->owner, $addon->name))) {
                $addon->github->extra->append('github', ['readme' => $response]);
            } else {
                $this->log('Skip (readme): ' . $addon->fullname);
            }

            // Composer
            if (in_array($addon->type, [NULL, Addon::TYPE_UNKNOWN, Addon::TYPE_COMPOSER])) {
                if (($response = $this->github->composer($addon->owner, $addon->name))) {
                    if ($addon->type !== Addon::TYPE_COMPOSER) {
                        $addon->type = Addon::TYPE_COMPOSER;
                    }

                    $addon->github->extra->append('github', ['composer' => $response]);

                    if (($url = $addon->github->extra->get(['github', 'composer', 'download_url'], NULL))) {
                        if (($content = @file_get_contents($url))) {
                            $composer = @json_decode($content, TRUE);
                            $addon->github->extra->set('composer', $composer);
                        } else {
                            $this->log('Skip (composer) [invalid composer.json]: ' . $addon->fullname);
                        }
                    } else {
                        $this->log('Skip (composer) [can not download composer.json]: ' . $addon->fullname);
                    }
                } else {
                    $this->log('Skip (composer): ' . $addon->fullname);
                }
            }

            // Bower
            if (in_array($addon->type, [NULL, Addon::TYPE_UNKNOWN, Addon::TYPE_BOWER])) {
                if (($response = $this->github->bower($addon->owner, $addon->name))) {
                    if ($addon->type !== Addon::TYPE_BOWER) {
                        $addon->type = Addon::TYPE_BOWER;
                    }

                    $addon->github->extra->append('github', ['bower' => $response]);

                    if (($url = $addon->github->extra->get(['github', 'bower', 'download_url'], NULL))) {
                        if (($content = @file_get_contents($url))) {
                            $composer = @json_decode($content, TRUE);
                            $addon->github->extra->set('bower', $composer);
                        } else {
                            $this->log('Skip (bower) [invalid bower.json]: ' . $addon->fullname);
                        }
                    } else {
                        $this->log('Skip (bower) [can not download bower.json]: ' . $addon->fullname);
                    }
                } else {
                    $this->log('Skip (bower): ' . $addon->fullname);
                }
            }

            // Persist
            $addon->github->crawledAt = new DateTime();
            $this->addonRepository->persistAndFlush($addon);

            // Increase counting
            $counter++;
        }

        return $counter;
    }

}
