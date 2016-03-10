<?php

namespace App\Model\Tasks\Avatars;

use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Addon\AddonRepository;
use App\Model\WebImages\GithubImages;
use App\Model\WebServices\Github\Service;
use Nette\Utils\DateTime;
use Nextras\Orm\Collection\ICollection;

final class UpdateAvatarTask extends BaseAvatarTask
{

    /** @var Service */
    private $github;

    /** @var GithubImages */
    private $githubImages;

    /**
     * @param AddonRepository $addonRepository
     * @param Service $github
     * @param GithubImages $githubImages
     */
    public function __construct(AddonRepository $addonRepository, Service $github, GithubImages $githubImages)
    {
        parent::__construct($addonRepository);
        $this->github = $github;
        $this->githubImages = $githubImages;
    }

    /**
     * @param array $args
     * @return int
     */
    public function run(array $args = [])
    {
        /** @var ICollection|Addon[] $addons */
        $addons = $this->addonRepository->findActive();

        $counter = 0;
        foreach ($addons as $addon) {

            // User avatar
            $response = $this->github->avatar($addon->owner);
            list ($info, $avatar) = $response;

            // If avatar was update before less then a week, remove it from filesystem
            if (DateTime::from($info['filetime']) > DateTime::from('- 1 week')) {
                $this->githubImages->remove(['type' => 'avatar', 'owner' => $addon->owner]);

                // Increase counting
                $counter++;
            }
        }

        return $counter;
    }

}
