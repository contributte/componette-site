<?php declare(strict_types = 1);

namespace App\Model\Database\Helpers;

use App\Model\Database\ORM\Composer\Composer;
use Contributte\Http\Url;

final class ComposerLinker
{

	private const PACKAGIST = 'https://packagist.org';

	/** @var Composer */
	private $composer;

	public function __construct(Composer $composer)
	{
		$this->composer = $composer;
	}

	public function getPackageUrl(?string $package = null): string
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

	public function getTagUrl(string $tag): string
	{
		return self::PACKAGIST . '/search/?tags=' . $tag;
	}

	public function getName(): string
	{
		return $this->composer->name;
	}

}
