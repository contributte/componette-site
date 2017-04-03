<?php

namespace App\Modules\Admin\Sign\Controls\Login;

interface ILoginFactory
{

	/**
	 * @return Login
	 */
	public function create();

}
