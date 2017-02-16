<?php

/**
 * JsonCall.php
 *
 * JSON-RPC formatting
 *
 * @author     Valentin D'Emmanuele
 * @copyright  2016 Valentin D'Emmanuele
 * @license    Mozilla Public License Version 2.0
 * @version    1.0
 */

namespace GraphenePHP;

require_once "auto.php";

/**
 * This class is handling the communication with the cli_wallet through curl.
 */
class OctoJsonRPC
{
	public $array = [
		"jsonrpc" => "2.0",
		"method" => "",
		"params" => [],
		"id" => 1
		];
	public function __construct($url) {
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_USERAGENT, 'GraphenePHP/1.0');
		curl_setopt($this->curl, CURLOPT_URL,$url);
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
	}
	
	public function __destruct() {
		curl_close($this->curl);
	}
	
	public function execute($method, $params) {
		$this->array["method"] = $method;
		$this->array["params"] = $params;
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, json_encode($this->array));
		return curl_exec($this->curl);
	}
}