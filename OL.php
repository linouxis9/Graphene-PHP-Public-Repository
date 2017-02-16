<?php
require_once "src/auto.php";

class Endpoint{
	public $handler = "";
	public function __construct($endpoint){
		$this->Blockchain = new \GraphenePHP\Graphene("openledger");
		$this->version = "v1";
		$this->endpoint = explode("/", $endpoint);
		if ($this->endpoint[1] == "v1") {
			$this->handler = "api";
		} elseif ($this->endpoint[1] == "v1-steem") {
			$this->returnToUser(["ERROR" => "STEEM NOT YET SUPPORTED"]);
			exit(1);
		}
		else {
			$this->returnToUser(["ERROR" => "Unknown Version"]);
			exit(1);
		}
		if ($_GET["browser"]) {
			echo "<pre>";
		}

	}

	public function api(){
		$params = $_REQUEST;
		switch($this->endpoint[2]) {
			case "returnOrderBook":
				$this->returnToUser($this->Blockchain->DEX->returnOrderBook($params["asset_a"], $params["asset_b"], $params["limit"]));
				break;
			case "returnChartData":
				$this->returnToUser($this->Blockchain->DEX->returnChartData($params["asset_a"], $params["asset_b"], $params["time"]));
				break;
			case "returnTicker":
				$this->returnToUser($this->Blockchain->DEX->returnTicker($params["asset_a"], $params["asset_b"]));
				break;
			case "returnBalance":
				$this->returnToUSer($this->Blockchain->Wallet->returnBalance($params["account"]));
				break;
			case "returnAccountHistory":
				$this->returnToUser($this->Blockchain->Wallet->ReturnAccountHistory($params["account"], $params["limit"]));
				break;
			case "getAccountByID":
				$this->returnToUser($this->Blockchain->Wallet->GetAccountByID($params["account_id"]));
				break;
			case "infoOf":
				$this->returnToUser($this->Blockchain->Asset->InfoOf($params["asset"]));
				break;
			default:
				$this->returnToUser(["ERROR" => "REQUEST NOT KNOWN"]);
		}
	}
	public function returnToUser($result){
		if (empty($result)) {
			$this->returnToUser(["ERROR" => "One of your parameters is incorrect"]);
			exit(1);
		}
		$result = json_encode(["result" => $result], JSON_PRETTY_PRINT);
		echo $result;
	}
}

$API = new Endpoint($_GET["_url"]);
$handler = $API->handler;
$API->$handler();
