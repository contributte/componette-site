<?php

namespace App\Model\Database\Helpers;

use App\Model\Database\ORM\Composer\Composer;
use Contributte\Http\Url;

final class ComposerLinker
{

	/** @var Composer */
	private $composer;

	/**
	 * @param Composer $composer
	 */
	public function __construct(Composer $composer)
	{
		$this->composer = $composer;
	}

	/**
	 * @param string|NULL $package
	 * @return string
	 */
	public function getPackageUrl($package = NULL)
	{
		$url = new Url('https://packagist.org/packages');
		$url->appendPath('/');

		if ($package) {
			$url->appendPath($package);
		} else {
			$url->appendPath($this->composer->name);
		}

		return $url;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->composer->name;
	}


}
