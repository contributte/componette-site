<?php

namespace App\Model\Tasks\Addons;

use App\Model\ORM\Addon\Addon;
use App\Model\ORM\Github\Github;
use Nette\Utils\Strings;
use Nextras\Orm\Collection\ICollection;

final class GenerateContentTask extends BaseAddonTask
{

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
            // Skip packages with bad data
            if (($extra = $addon->github->extra)) {
                if (($url = $extra->get(['github', 'readme', 'download_url'], NULL))) {
                    $content = @file_get_contents($url);

                    if ($content) {
                        // Content
                        $addon->github->content = $content;

                        // Readme type
                        if ($addon->github->readme === NULL) {
                            $url = strtolower($url);
                            if (Strings::endsWith($url, 'md')) {
                                $addon->github->readme = Github::README_MARKDOWN;
                            } else if (Strings::endsWith($url, 'texy')) {
                                $addon->github->readme = Github::README_TEXY;
                            } else {
                                $addon->github->readme = Github::README_RAW;
                            }
                        }
                        // Persist
                        $this->addonRepository->persistAndFlush($addon);

                        // Increase counting
                        $counter++;
                    } else {
                        $this->log('Skip (content) [failed download content]: ' . $addon->fullname);
                    }
                } else {
                    $this->log('Skip (content) [no github readme data]: ' . $addon->fullname);
                }
            } else {
                $this->log('Skip (content) [no extra data]: ' . $addon->fullname);
            }
        }

        return $counter;
    }
}
