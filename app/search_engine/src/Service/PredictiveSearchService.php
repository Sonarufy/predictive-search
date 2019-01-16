<?php
/**
 * Created by PhpStorm.
 * User: sami.ounalli
 * Date: 1/14/19
 * Time: 4:58 PM
 */

namespace App\Service;

use Elastica\Client;
use Elastica\Query;
use Elastica\Query\Term;
use Elastica\Query\Match;
use Elastica\Query\BoolQuery;
use Elastica\Query\Type;
use Elastica\Query\Ids;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

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
	 * @var string
	 */
	private $indexName;

	/**
	 * PredictiveSearchService constructor.
	 * @param Client $client
	 * @param ParameterBagInterface $params
	 */
	public function __construct(Client $client, ParameterBagInterface $params)
	{
		$this->client = $client;
		$this->indexName = $params->get('app_index');
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

		$foundPostalCodes = $this->client->getIndex($this->indexName)->search($elasticaQuery);

		$results = [];
		foreach ($foundPostalCodes as $postalCode) {
			$results[] = $postalCode->getSource();
		}

		return $this->formatResult($keyWord, $results);
	}

	/**
	 * @param int $idPostalCode
	 * @param int $idInseeTown
	 * @return array
	 */
	public function getPostalCodeById(int $idPostalCode, int $idInseeTown)
	{

		$queryType = new Type();
		$queryType->setType(self::POSTAL_CODE_TYPE);

		$queryId = new Ids();

		$queryId->addId( $idPostalCode);

		$boolQuery = new BoolQuery();

		$boolQuery
			->addMust($queryType)
			->addMust($queryId);

		$elasticaQuery = new Query($boolQuery);

		$foundPostalCodes = $this->client->getIndex($this->indexName)->search($elasticaQuery);

		$results = [];
		foreach ($foundPostalCodes as $postalCode) {
			$results[] = $postalCode->getSource();
		}

		return $this->formatPostalCode($idInseeTown, $results);
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

				$townId = $inseeTown['id'];
				$townName = $inseeTown['name'];

				if (is_numeric($keyWord)) {

					$townList[] = [
						'id'         => $id,
						'postalCode' => $postalCode,
						'townId'     => $townId,
						'townName'   => $townName,
						'price'      => $price['price']
					];
				} elseif (substr(strtolower($townName), 0, strlen($keyWord)) === strtolower($keyWord)) {

					$townList[] = [
						'id'         => $id,
						'postalCode' => $postalCode,
						'townId'     => $townId,
						'townName'   => $townName,
						'price'      => $price['price']
					];
				}
			}
		}

		return $townList;
	}

	/**
	 * @param int $idInseeTown
	 * @param array $results
	 * @return array
	 */
	private function formatPostalCode(int $idInseeTown, array $results): array
	{
		$townList = [];
		foreach ($results as $item) {

			$id = $item['id'];

			$postalCode = $item['postal_code'];

			$prices = $item['pricing_zone']['reference_prices'];

			foreach ($item['insees'] as $inseeTown) {

				$townId = $inseeTown['id'];
				$townName = $inseeTown['name'];

				if ($idInseeTown === $townId) {

					return [
						'id'         => $id,
						'postalCode' => $postalCode,
						'townId'     => $townId,
						'townName'   => $townName,
						'prices'      => $prices
					];
				}
			}
		}

		return $townList;
	}
}