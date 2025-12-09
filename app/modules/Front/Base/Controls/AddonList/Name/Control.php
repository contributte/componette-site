<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Name;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use App\Model\UI\BaseRenderControl;

class Control extends BaseRenderControl
{

	public function render(Addon $addon, bool $linkToGitHub = false, bool $inverseTag = false): void
	{
		$this->template->setParameters(['addon' => $addon, 'linkToGitHub' => $linkToGitHub, 'inverseTag' => $inverseTag]);
		if ($github = $addon->getGithub()) {
			$releases = $github->getReleases()->toArray();
			usort($releases, fn(GithubRelease $a, GithubRelease $b) => $b->getCrawledAt() <=> $a->getCrawledAt());
			$release = $releases[0] ?? null;
			$this->template->setParameters(['release' => $release]);
		} else {
			$this->template->setParameters(['release' => null]);
		}
		$this->template->render();
	}

}
