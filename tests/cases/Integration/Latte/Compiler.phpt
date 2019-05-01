<?php declare(strict_types = 1);

/**
 * Test: Latte Template Compiler
 */

use App\Model\Templating\TemplateFactory;
use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\DI\Container;
use Nette\Utils\Finder;
use Tester\Assert;

/** @var Container $container */
$container = require_once __DIR__ . '/../../../bootstrap.container.php';

test(function () use ($container): void {
	try {
		/** @var ITemplateFactory $templateFactory */
		$templateFactory = $container->getByType(ITemplateFactory::class);
		Assert::type(TemplateFactory::class, $templateFactory);

		/** @var Template $template */
		$template = $templateFactory->createTemplate();

		$finder = Finder::findFiles('*.latte')->from(APP_DIR);
		foreach ($finder as $file) {
			$template->getLatte()->warmupCache((string) $file);
		}
	} catch (Throwable $e) {
		Assert::fail(sprintf('Template compilation failed (%s)', $e->getMessage()));
	}
});
