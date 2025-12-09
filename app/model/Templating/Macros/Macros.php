<?php declare(strict_types = 1);

namespace App\Model\Templating\Macros;

use App\Model\Templating\GithubAvatar;
use Latte\Extension;

final class Macros extends Extension
{

    /**
     * @return array<string, callable>
     */
    public function getFunctions(): array
    {
        return [
        'avatar' => [GithubAvatar::class, 'generate'],
        ];
    }

}
