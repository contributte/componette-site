<?php declare(strict_types = 1);

namespace App\Modules\Front\Portal\Base\Controls\News;

use Nette\SmartObject;
use Nette\Utils\DateTime;

final class Article
{

	use SmartObject;

	private string $title;

	private string $description;

	private string $link;

	private ?DateTime $date;

	public function __construct(string $title, string $description, string $link, ?DateTime $date)
	{
		$this->title = $title;
		$this->description = $description;
		$this->link = $link;
		$this->date = $date;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getDescription(): string
	{
		return $this->description;
	}

	public function getLink(): string
	{
		return $this->link;
	}

	public function getDate(): ?DateTime
	{
		return $this->date;
	}

}
