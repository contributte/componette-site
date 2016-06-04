<?php

namespace App\Core\Latte\Filters;

use Latte\Engine;

final class FiltersExecutor
{

    /** @var Engine */
    private $latte;

    /**
     * @param Engine $latte
     */
    public function __construct(Engine $latte)
    {
        $this->latte = $latte;
    }

    /**
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call($name, $args)
    {
        return $this->latte->invokeFilter($name, $args);
    }

}
