<?php

namespace App\Modules\Admin\Controls\Login;

interface ILoginFactory
{

	/**
	 * @return Login
	 */
	public function create();

}
