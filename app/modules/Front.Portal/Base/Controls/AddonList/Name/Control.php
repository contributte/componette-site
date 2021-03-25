<?php declare (strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\AddonList\Name;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\GithubRelease\GithubRelease;
use App\Model\UI\BasePropsControl;
use Nextras\Orm\Collection\ICollection;
use Wavevision\DIServiceAnnotation\DIService;
use Wavevision\PropsControl\ValidProps;

/**
 * @DIService(generateComponent=true)
 */
class Control extends BasePropsControl
{

	protected function getPropsClass(): string
	{
		return NameProps::class;
	}

	protected function beforeRender(ValidProps $props): void
	{
		parent::beforeRender($props);
		/** @var Addon $addon */
		$addon = $props->get(NameProps::ADDON);
		$this->template->setParameters(['addon' => $addon]);
		if ($github = $addon->github) {
			/** @var GithubRelease|null $release */
			$release = $github->releases->get()->orderBy(['crawledAt' => ICollection::DESC])->fetch();
			$this->template->setParameters(['release' => $release]);
		}
	}

}
