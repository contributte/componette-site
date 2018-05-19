<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\Componetters;

interface IComponettersFactory
{

	public function create(): Componetters;

}
