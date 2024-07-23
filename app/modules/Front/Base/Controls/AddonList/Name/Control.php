<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\AddonList\Name;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use App\Model\UI\BaseRenderControl;
use Nextras\Orm\Collection\ICollection;

class Control extends BaseRenderControl
{

	public function render(Addon $addon, bool $linkToGitHub = false, bool $inverseTag = false): void
	{
		$this->template->setParameters(['addon' => $addon, 'linkToGitHub' => $linkToGitHub, 'inverseTag' => $inverseTag]);
		if ($github = $addon->github) {
			/** @var GithubRelease|null $release */
			$release = $github->releases->get()->orderBy(['crawledAt' => ICollection::DESC])->fetch();
			$this->template->setParameters(['release' => $release]);
		}
		$this->template->render();
	}

}
