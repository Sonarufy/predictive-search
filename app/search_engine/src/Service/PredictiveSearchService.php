<?php
/**
 * Created by PhpStorm.
 * User: sami.ounalli
 * Date: 1/14/19
 * Time: 4:58 PM
 */

namespace App\Service;

use Elastica\Client;
use Elastica\Query\Match;
use Elastica\Query\BoolQuery;
use Elastica\Query;
use Elastica\Query\Term;

/**
 * Class PredictiveSearchService
 * @package App\Service
 */
class PredictiveSearchService
{
	const MAX_RESULT_LIMIT = 10;

	const POSTAL_CODE_TYPE = 'postal_code';


	/**
	 * @var Client
	 */
	private $client;

	/**
	 * PredictiveSearchService constructor.
	 * @param Client $client
	 */
	public function __construct(Client $client)
	{
		$this->client = $client;
	}

	/**
	 * @param string $keyWord
	 * @param bool $isAutoComplete
	 * @return array
	 */
	public function search(string $keyWord, bool $isAutoComplete = true): array
	{
		$queryTownName = new Match();

		$queryTownName->setFieldQuery('insees.name.autocomplete', $keyWord);

		$queryPostalCode = new Match();

		$queryPostalCode->setFieldQuery('postal_code.autocomplete', $keyWord);

		$queryTownPostalCode = new Match();

		$queryTownPostalCode->setFieldQuery('insees.code.autocomplete', $keyWord);

		$boolShouldQuery = new BoolQuery();

		$boolShouldQuery
			->addShould($queryTownName)
			->addShould($queryPostalCode);

		$queryTownEnabled = new Term();

		$queryTownEnabled->setTerm('insees.enabled', true);

		$boolQuery = new BoolQuery();

		$boolQuery
			->addMust($queryTownEnabled)
			->addMust($boolShouldQuery);

		$elasticaQuery = new Query($boolQuery);

		if ($isAutoComplete) {
			$elasticaQuery->setSize(self::MAX_RESULT_LIMIT);
		}

		$foundPostalCodes = $this->client->getIndex('app_dev')->search($elasticaQuery);

		$results = [];
		foreach ($foundPostalCodes as $postalCode) {
			$results[] = $postalCode->getSource();
		}

		return $this->formatResult($keyWord, $results);
	}

	/**
	 * @param string $keyWord
	 * @param array $results
	 * @return array
	 */
	private function formatResult(string $keyWord, array $results): array
	{
		$townList = [];
		foreach ($results as $item) {

			$id = $item['id'];

			$postalCode = $item['postal_code'];

			$price = end($item['pricing_zone']['reference_prices']);

			foreach ($item['insees'] as $inseeTown) {

				$townName = $inseeTown['name'];

				if (is_numeric($keyWord)) {

					$townList[] = [
						'id'         => $id,
						'postalCode' => $postalCode,
						'townName'   => $townName,
						'price'      => $price['price']
					];
				} elseif (substr(strtolower($townName), 0, strlen($keyWord)) === strtolower($keyWord)) {

					$townList[] = [
						'id'         => $id,
						'postalCode' => $postalCode,
						'townName'   => $townName,
						'price'      => $price['price']
					];
				}
			}
		}

		return $townList;
	}
}