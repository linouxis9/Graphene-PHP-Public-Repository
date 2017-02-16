<?php

/**
 * api.php
 *
 * API manager
 *
 * @author     Valentin D'Emmanuele
 * @copyright  2016 Valentin D'Emmanuele
 * @license    Mozilla Public License Version 2.0
 * @version    1.0
 */

namespace GraphenePHP;

require_once "auto.php";

/**
 * This class is responsible of the calls to cli_wallet. This class should not be used by an user.
 *
 * The serialization will take place here when we will drop the use of the cli_wallet.
 */
class GrapheneAPI
{
	public function __construct($address){
		$this->curl = new OctoJsonRPC($address);
		$this->allowed_methods = array("get_block", "unlock", "get_limit_orders", "get_account_history", "get_market_history", "get_account", "get_account_test", "register_account", "list_my_accounts", "list_account_balances", "get_asset", "suggest_brain_key");
	}
	
	public function call($call, $params=array()) {
		if (in_array($call, $this->allowed_methods))
			{
			$json_object = $this->curl->execute($call, $params);
			return json_decode($json_object, JSON_PRETTY_PRINT)['result'];
			}
        return 0;
	}
	public function call2($call, $params=array()) {
		if (in_array($call, $this->allowed_methods))
		{
			$json_object = $this->curl->execute($call, $params);
			return json_decode($json_object, JSON_PRETTY_PRINT);
		}
		return 0;
	}
}

/**
 * This class contains some functions that could simplify the rest of the code.
 */
class GrapheneTools
{
	public function __construct($Blockchain){
		$this->Blockchain = $Blockchain;
	}
	public function is_not_premium($account_to_check) {
        return strcspn($account_to_check, '0123456789-') != strlen($account_to_check);
    }
	public function AssetAssign($key, $array){
		if (!array_key_exists($key, $array)){
			$info = $this->Blockchain->Asset->InfoOf($key);
			$array[$info["id"]] = $info;
			return $array;
		} else {
			return $array;
		}
	}
	public function VariableAssign($key, $key2, $data, $asset, $array){
		$array[$data["op"]["id"]]["base"]["amount"] = $data["op"]["op"][1][$key]["amount"] / $asset[$data["op"]["op"][1][$key]["asset_id"]]["precision"];
		$array[$data["op"]["id"]]["base"]["asset"] = $asset[$data["op"]["op"][1][$key]["asset_id"]]["symbol"];
		$array[$data["op"]["id"]]["quote"]["amount"] = $data["op"]["op"][1][$key2]["amount"] / $asset[$data["op"]["op"][1][$key2]["asset_id"]]["precision"];
		$array[$data["op"]["id"]]["quote"]["asset"] = $asset[$data["op"]["op"][1][$key2]["asset_id"]]["symbol"];
		return $array;
	}
	public function Average($asset_a, $asset_b){
		$test = $this->Blockchain->DEX->returnOrderBook($asset_a, $asset_b, 1);
		$array[$asset_a] = ($test[0]["price"]["base"]/$test[0]["price"]["quote"]);
		$array[$asset_b] = ($test[1]["price"]["quote"]/$test[1]["price"]["base"]);
		return $array;
	}
}