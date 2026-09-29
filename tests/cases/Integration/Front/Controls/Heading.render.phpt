<?php declare(strict_types = 1);

/**
 * Test: Heading control rendering
 */

require_once __DIR__ . '/../../../../bootstrap.php';

use App\Model\Database\Query\SearchAddonsQuery;
use App\Modules\Front\Base\Controls\AddonList\AddonList;
use App\Modules\Front\Base\Controls\Layout\Heading\Control;
use Latte\Engine;
use Nette\Bridges\ApplicationLatte\LatteFactory;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Utils\Html;
use Tester\Assert;

function renderHeading(string|Html $content): string
{
	$latteFactory = new class implements LatteFactory {

		public function create(): Engine
		{
			return new Engine();
		}

	};

	$control = new Control();
	$control->setTemplateFactory(new TemplateFactory($latteFactory));

	ob_start();
	$control->render($content);

	return (string) ob_get_clean();
}

// Plain string is escaped
test(function (): void {
	Assert::contains('&lt;b&gt;Addons&lt;/b&gt;', renderHeading('<b>Addons</b>'));
});

// Html object is rendered as HTML
test(function (): void {
	$output = renderHeading(Html::el('strong')->setText('Addons'));

	Assert::contains('<strong>Addons</strong>', $output);
	Assert::notContains('&lt;strong&gt;', $output);
});

// Search title renders as HTML with escaped query
test(function (): void {
	$query = new SearchAddonsQuery();
	$query->byQuery('<script>alert(1)</script>');

	$addonList = new AddonList($query);
	$renderSearchTitle = Closure::bind(fn (int $count): Html => $this->renderSearchTitle($count), $addonList, AddonList::class);

	$output = renderHeading($renderSearchTitle(3));

	Assert::contains('Searched for <strong>&lt;script&gt;alert(1)&lt;/script&gt;</strong>', $output);
	Assert::contains('>3 results</i>', $output);
	Assert::notContains('<script>', $output);
	Assert::notContains('$', $output);
});
