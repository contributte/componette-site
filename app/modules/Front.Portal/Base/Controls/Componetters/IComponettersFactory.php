<?php

namespace App\Modules\Front\Portal\Base\Controls\Componetters;

interface IComponettersFactory
{

	/**
	 * @return Componetters
	 */
	public function create(): Componetters;

}
