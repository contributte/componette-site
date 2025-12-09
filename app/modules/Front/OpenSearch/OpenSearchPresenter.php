<?php declare(strict_types = 1);

namespace App\Modules\Front\OpenSearch;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\Addon\AddonRepository;
use App\Modules\Front\Base\BasePresenter;
use Nette\Utils\Strings;

final class OpenSearchPresenter extends BasePresenter
{

	/** @var AddonRepository @inject */
	public AddonRepository $addonRepository;

	public function actionSuggest(?string $q): void
	{
		if (!$q) {
			$this->sendJson([]);
		}

		$token = Strings::replace($q, '#[.\-]#', ' ');

		$addons = $this->addonRepository->createQueryBuilder('a')
			->leftJoin('a.github', 'g')
			->where('a.state = :state')
			->andWhere('a.author LIKE :token OR a.name LIKE :token')
			->setParameter('state', Addon::STATE_ACTIVE)
			->setParameter('token', '%' . $token . '%')
			->orderBy('a.rating', 'DESC')
			->addOrderBy('a.createdAt', 'DESC')
			->getQuery()
			->getResult();

		$output = [];
		$terms = [];
		foreach ($addons as $addon) {
			$terms[] = [
				'completion' => $addon->getFullname(),
				'description' => $addon->getGithub() !== null ? $addon->getGithub()->getDescription() : '',
				'link' => $this->link(':Front:Addon:detail', $addon->getId()),
			];
		}

		// 1st -> query string
		// 2nd -> completions
		// 3rd -> descriptions
		// 4th -> links
		$output[0] = $q;
		$output[1] = [];
		$output[2] = [];
		$output[3] = [];
		foreach ($terms as $term) {
			$output[1][] = $term['completion'];
			$output[2][] = $term['description'];
			$output[3][] = $term['link'];
		}

		$this->sendJson($output);
	}

}
