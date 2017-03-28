<?php

namespace App\Model\Templating\Filters;

use App\Model\Exceptions\Logical\InvalidArgumentException;

final class HelpersExecutor
{

	/** @var array */
	private $helpers = [];

	/**
	 * @param string $name
	 * @param callable $callback
	 * @return void
	 */
	public function addHelper($name, callable $callback): void
	{
		$this->helpers[$name] = $callback;
	}

	/**
	 * @param string $name
	 * @param array $args
	 * @return mixed
	 */
	public function __call($name, $args)
	{
		if (!isset($this->helpers[$name])) {
			throw new InvalidArgumentException(sprintf('Uknown helper "%s"', $name));
		}

		return call_user_func_array($this->helpers[$name], $args);
	}

}
