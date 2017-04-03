<?php

namespace App\Model\Database\Helpers;

use App\Model\Database\ORM\Composer\Composer;
use Contributte\Http\Url;

final class ComposerLinker
{

	private const PACKAGIST = 'https://packagist.org';

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
	public function getPackageUrl(string $package = NULL): string
	{
		$url = new Url(self::PACKAGIST . '/packages');
		$url->appendPath('/');

		if ($package) {
			$url->appendPath($package);
		} else {
			$url->appendPath($this->composer->name);
		}

		return (string) $url;
	}

	/**
	 * @param string $tag
	 * @return string
	 */
	public function getTagUrl(string $tag): string
	{
		return self::PACKAGIST . '/search/?tags=' . $tag;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->composer->name;
	}

}
