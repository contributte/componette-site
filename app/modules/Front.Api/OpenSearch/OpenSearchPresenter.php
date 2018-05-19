<?php declare(strict_types = 1);

namespace App\Modules\Front\Api\OpenSearch;

use App\Model\Database\ORM\Addon\Addon;
use App\Model\Database\ORM\EntityModel;
use App\Model\Database\Query\OpenSearchQuery;
use App\Modules\Front\Api\Base\BasePresenter;
use Contributte\Nextras\Orm\QueryObject\Queryable;

final class OpenSearchPresenter extends BasePresenter
{

	/** @var EntityModel @inject */
	public $em;

	public function actionSuggest(string $q): void
	{
		$query = new OpenSearchQuery();
		$query->byQuery($q);
		$addons = $this->em->getRepositoryForEntity(Addon::class)->fetch($query, Queryable::HYDRATION_ENTITY);

		$output = [];
		$terms = [];
		foreach ($addons as $addon) {
			$terms[] = [
				'completion' => $addon->fullname,
				'description' => $addon->github->description,
				'link' => $this->link(':Front:Portal:Addon:detail', $addon->id),
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
