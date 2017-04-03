<?php

namespace App\Model\Templating\Macros;

use App\Model\Templating\GithubAvatar;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

final class Macros extends MacroSet
{

	/**
	 * @param Compiler $compiler
	 * @return void
	 */
	public static function install(Compiler $compiler)
	{
		$self = new self($compiler);
		$self->addMacro('avatar', [$self, 'macroAvatar']);
	}

	/**
	 * @param MacroNode $node
	 * @param PhpWriter $writer
	 * @return string
	 */
	public function macroAvatar(MacroNode $node, PhpWriter $writer): string
	{
		return $writer->write(sprintf('echo %s::generate(%%node.args)', GithubAvatar::class));
	}

}
