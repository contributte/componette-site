<?php declare (strict_types = 1);

namespace App\Modules\Front\Base\Controls\Layout\Footer;

use App\Model\UI\BaseRenderControl;
use App\Modules\Front\Base\Controls\Layout\Footer\SocialLinks\SocialLink;
use App\Modules\Front\Base\Controls\Layout\Footer\SocialLinks\SocialLinksComponent;
use App\Modules\Front\Base\Controls\Layout\Footer\SubscribeForm\SubscribeFormComponent;

class Control extends BaseRenderControl
{

	use SocialLinksComponent;
	use SubscribeFormComponent;

	public function render(): void
	{
		$this
			->template
			->setParameters(
				[
					'home' => $this->presenter->link(':Front:Home:'),
					'socialLinks' => $this->socialLinks(),
				]
			)->render();
	}

	/**
	 * @return SocialLink[]
	 */
	private function socialLinks(): array
	{
		return [
			new SocialLink('GitHub', 'https://github.com/contributte/componette-site', 'github-fill'),
			new SocialLink('Twitter', 'https://twitter.com/componette', 'twitter-fill'),
			new SocialLink('Slack', 'https://pehapkari.slack.com', 'slack-fill'),
		];
	}

}
