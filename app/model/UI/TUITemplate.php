<?php

namespace App\Model\UI;

use Nette\Application\UI\ComponentReflection;

trait TUITemplate
{

	/**
	 * TEMPLATES ***************************************************************
	 * *************************************************************************
	 */

	/**
	 * @return array
	 */
	public function formatTemplateFiles()
	{
		$dir = dirname($this->getReflection()->getFileName());

		return [
			$dir . '/templates/' . $this->view . '.latte',
		];
	}

	/**
	 * @return array
	 */
	public function formatLayoutTemplateFiles()
	{
		$list = [];

		$rf1 = new ComponentReflection(get_called_class());
		$dir1 = dirname($rf1->getFileName());
		$list[] = $dir1 . '/templates/@layout.latte';

		$rf2 = new ComponentReflection(self::class);
		$dir2 = dirname($rf2->getFileName());
		$list[] = $dir2 . '/templates/@layout.latte';

		return $list;
	}

}
