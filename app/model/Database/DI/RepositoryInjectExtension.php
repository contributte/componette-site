<?php declare(strict_types = 1);

namespace App\Model\Database\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Extensions\InjectExtension;
use Nextras\Orm\Mapper\Mapper;
use Nextras\Orm\Repository\Repository;

final class RepositoryInjectExtension extends CompilerExtension
{

    public function beforeCompile(): void
    {
        $builder = $this->getContainerBuilder();

        foreach ($builder->findByType(Repository::class) as $def) {
            $def->addTag(InjectExtension::TAG_INJECT);
        }

        foreach ($builder->findByType(Mapper::class) as $def) {
            $def->addTag(InjectExtension::TAG_INJECT);
        }
    }

}
