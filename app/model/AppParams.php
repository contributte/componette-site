<?php declare(strict_types = 1);

namespace App\Model;

use Nette\DI\Helpers;
use Nette\Utils\Arrays;

final class AppParams
{

	/** @var mixed[] */
	private $parameters = [];

	/**
	 * @param mixed[] $parameters
	 */
	public function __construct(array $parameters = [])
	{
		$this->parameters = $parameters;
	}

	public function isDebug(): bool
	{
		return $this->parameters['debugMode'] === true;
	}

	/**
	 * @param mixed|null $default
	 * @return mixed
	 */
	public function get(string $name, $default = null)
	{
		if (func_num_args() > 1) {
			return Arrays::get($this->parameters, $name, $default);
		} else {
			return Arrays::get($this->parameters, $name);
		}
	}

	/**
	 * @return mixed
	 */
	public function expand(string $name, bool $recursive = false)
	{
		return Helpers::expand('%' . $name . '%', $this->parameters, $recursive);
	}

}
