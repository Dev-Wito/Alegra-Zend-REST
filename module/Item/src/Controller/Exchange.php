<?php
/**
 * Created by PhpStorm.
 * User: wito
 * Date: 4/07/18
 * Time: 22:52
 */

namespace Item\Controller;

use Couchbase\Exception;
use Zend\Http\Client;
use Zend\Http\Response;
use Zend\Router\Http\Method;

class Exchange {
	const API_EXCHANGE      = 'https://openexchangerates.org/api/latest.json';
	const API_EXCHANGE_AUTH = 'Authorization: Token 7967534ae1104d27848f64232878128e';

	private $latest;
	private $response;
	private $rates;

	public function __construct() {

		$this->latest = new Client();
		$this->latest->setUri(Exchange::API_EXCHANGE)->setHeaders([Exchange::API_EXCHANGE_AUTH]);
		$this->response = $this->latest->send();

		if ($this->response->getStatusCode() == Response::STATUS_CODE_200) {
			$this->rates = json_decode($this->response->getBody())->rates;
		} else {
			throw new Exception("currency not available", 400);
		}
	}

	public function toUSD(&$value) {
		$value = $value / $this->rates->COP;
	}

	public function toCOP(&$value) {
		$value = $value * $this->rates->COP;
	}
}