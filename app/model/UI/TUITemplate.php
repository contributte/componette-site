<?php declare(strict_types = 1);

namespace App\Model\UI;

use Nette\Application\UI\ComponentReflection;

trait TUITemplate
{

	/**
	 * TEMPLATES ***************************************************************
	 * *************************************************************************
	 */

	/**
	 * @return string[]
	 */
	public function formatTemplateFiles(): array
	{
		$dir = dirname($this->getReflection()->getFileName());

		return [
			$dir . '/templates/' . $this->view . '.latte',
		];
	}

	/**
	 * @return string[]
	 */
	public function formatLayoutTemplateFiles(): array
	{
		$list = [];

		$rf1 = new ComponentReflection(static::class);
		$dir1 = dirname($rf1->getFileName());
		$list[] = $dir1 . '/templates/@layout.latte';

		$rf2 = new ComponentReflection(self::class);
		$dir2 = dirname($rf2->getFileName());
		$list[] = $dir2 . '/templates/@layout.latte';

		return $list;
	}

}
