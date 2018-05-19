<?php declare(strict_types = 1);

namespace App\Modules\Admin\Sign\Controls\Login;

interface ILoginFactory
{

	public function create(): Login;

}
