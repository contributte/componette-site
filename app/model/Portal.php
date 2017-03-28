<?php

namespace App\Model;

use Nette\DI\Helpers;
use Nette\Utils\Arrays;

final class Portal
{

	/** @var array */
	private $parameters = [];

	/**
	 * @param array $parameters
	 */
	public function __construct(array $parameters = [])
	{
		$this->parameters = $parameters;
	}

	/*
     * @return int
     */
	public function isDebug()
	{
		return $this->parameters['debugMode'] === TRUE;
	}

	/**
	 * @param string $name
	 * @param mixed $default
	 * @return mixed
	 */
	public function get($name, $default = NULL)
	{
		if (func_num_args() > 1) {
			return Arrays::get($this->parameters, $name, $default);
		} else {
			return Arrays::get($this->parameters, $name);
		}
	}

	/**
	 * @param string $name
	 * @param boolean $recursive
	 * @return mixed
	 */
	public function expand($name, $recursive = FALSE)
	{
		return Helpers::expand("%$name%", $this->parameters, $recursive);
	}

}
