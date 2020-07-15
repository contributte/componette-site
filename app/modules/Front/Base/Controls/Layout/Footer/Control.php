<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer;

use App\Model\UI\BaseRenderControl;
use App\Modules\Front\Base\Controls\Layout\Footer\SocialLinks\SocialLink;
use App\Modules\Front\Base\Controls\Layout\Footer\SocialLinks\SocialLinksComponent;
use App\Modules\Front\Base\Controls\Layout\Footer\SocialLinks\SocialLinksProps;
use App\Modules\Front\Base\Controls\Layout\Footer\SubscribeForm\SubscribeFormComponent;
use Wavevision\DIServiceAnnotation\DIService;
use Wavevision\NetteWebpack\InjectFormatAssetName;

/**
 * @DIService(generateComponent=true)
 */
class Control extends BaseRenderControl
{

	use InjectFormatAssetName;
	use SocialLinksComponent;
	use SubscribeFormComponent;

	protected function beforeRender(): void
	{
		parent::beforeRender();
		$this
			->template
			->setParameters(
				[
					'home' => $this->presenter->link(':Front:Portal:Home:'),
					'logo' => $this->formatAssetName->process('logo-small.svg'),
					'socialLinks' => new SocialLinksProps([SocialLinksProps::LINKS => $this->socialLinks()]),
				]
			);
	}

	/**
	 * @return SocialLink[]
	 */
	private function socialLinks(): array
	{
		return [
			new SocialLink('GitHub', '', 'github-fill'),
			new SocialLink('Twitter', '', 'twitter-fill'),
			new SocialLink('Slack', '', 'slack-fill'),
		];
	}

}
