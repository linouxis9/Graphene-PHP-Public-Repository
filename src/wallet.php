<?php

/**
 * wallet.php
 *
 * Wallet manager
 *
 * @author     Valentin D'Emmanuele
 * @copyright  2016 Valentin D'Emmanuele
 * @license    Mozilla Public License Version 2.0
 * @version    1.0
 */

namespace GraphenePHP;

require_once "auto.php";

/**
 * This class is responsible of the management of the user's wallet and provides a way to explore the Bitshares' accounts.
 */
class GrapheneWallet
{
	public function __construct($Blockchain){
		$this->Blockchain = $Blockchain;
		$this->name = $this->Blockchain->name;
		$this->account = $this->Blockchain->API->call('get_account',[$this->name]);
		if (null == $this->account) {
			$this->status = 0;
		} else {
			$this->status = 1;
		}
		
		
	}

	/**
	 * Returns the balance of each asset owned by $account.
	 *
	 * @param string $account
	 *
	 * @return array Returns a multidimensional array of the balances.
	 */
	public function returnBalance($account) {
        $array = $asset = array();
		foreach($this->Blockchain->API->call('list_account_balances', [$account]) as $data) {
			if($data['amount'] > 0) {
				$asset = $this->Blockchain->Tools->AssetAssign($data['asset_id'], $asset);
				$balance = $data['amount'] / $asset[$data['asset_id']]["precision"];
				$array[$asset[$data['asset_id']]["symbol"]] = $balance;
			}
		}
		return $array;
	}

	/**
	 * Returns the $account history for $limit transactions.
	 *
	 * @param string $account
	 * @param int $limit The number of transactions you want from the history.
	 *
	 * @return array Returns a multidimensional array of the transactions information.
	 */
	public function ReturnAccountHistory($account, $limit = 25) {
		$array = $assets = $asset = array();
		foreach($this->Blockchain->API->call('get_account_history', [$account, $limit]) as $data) {
			$array[$data["op"]["id"]]["id"] = $data["op"]["id"];
			$array[$data["op"]["id"]]["type"] = $this->Blockchain->Operations[$data["op"]["op"][0]];
			switch ($data["op"]["op"][0]) {
				case 0:
					$array[$data["op"]["id"]]["from"] = $this->GetAccountByID($data["op"]["op"][1]["from"]);
					$array[$data["op"]["id"]]["to"] = $this->GetAccountByID($data["op"]["op"][1]["to"]);
					$array[$data["op"]["id"]]["amount"] = Array(); // $data["op"]["op"][1]["amount"];
					$asset = $this->Blockchain->Asset->InfoOf($data["op"]["op"][1]["amount"]["asset_id"]);
					$array[$data["op"]["id"]]["amount"]["amount"] = $data["op"]["op"][1]["amount"]["amount"] / $asset["precision"];
					$array[$data["op"]["id"]]["amount"]["asset"] = $asset["symbol"];
					break;
				case 1:
					$asset = $this->Blockchain->Tools->AssetAssign($data["op"]["op"][1]["amount_to_sell"]["asset_id"], $asset);
					$asset = $this->Blockchain->Tools->AssetAssign($data["op"]["op"][1]["min_to_receive"]["asset_id"], $asset);
					$array = $this->Blockchain->Tools->VariableAssign("amount_to_sell", "min_to_receive", $data, $asset, $array);
					break;
				case 4:
					$array[$data["op"]["id"]]["order_id"] = $data["op"]["op"][1]["order_id"];
					$asset = $this->Blockchain->Tools->AssetAssign($data["op"]["op"][1]["pays"]["asset_id"], $asset);
					$asset = $this->Blockchain->Tools->AssetAssign($data["op"]["op"][1]["receives"]["asset_id"], $asset);
					$array[$data["op"]["id"]]["order_id"] = $data["op"]["op"][1]["order_id"];
					$array = $this->Blockchain->Tools->VariableAssign("pays", "receives", $data, $asset, $array);
					break;
				case 5:
					$array[$data["op"]["id"]]["name"] = $data["op"]["op"][1]["name"];
					$array[$data["op"]["id"]]["referrer"] = $this->GetAccountByID($data["op"]["op"][1]["referrer"]);
					$array[$data["op"]["id"]]["registrar"] = $this->GetAccountByID($data["op"]["op"][1]["registrar"]);
					break;
				default:
					$array[$data["op"]["id"]]["status"] = "Not implemented";
			}
		}
		return $array;
	}

	/**
	 * Register an account
	 * The cli_wallet needs to be unlocked.
	 *
	 * @param array $account $account["name"] is the account to register, $account['owner_key'] is the owner key of this account and $account['active_key'] is the active key of this account.
	 * @param string $registrar This is the account you want to pay the fees to register the account, the account's keys must be in the cli_wallet.
	 *
	 * @return string Returns the result of the register_account call.
	 */
	public function RegisterAccount($account, $registrar) {
	    return $this->Blockchain->API->call2('register_account',[$account['name'], $account['owner_key'], $account['active_key'], $registrar, $registrar, 0, 1]);
	}

	/**
	 * Unlock the cli_wallet.
	 * Do not left unlock a cli_wallet reachable from the outside.
	 *
	 * @param array $password This is the cli_wallet password needed to unlock it.
	 *
	 * @return string Returns the result of the register_account call.
	 */
	public function Unlock($password) {
		return $this->Blockchain->API->call2('unlock',[$password]);
	}

	/**
	 * Returns the name of an $account_id.
	 *
	 * @param string $account_id The account you want to find the name by the account id.
	 *
	 * @return string Returns the account name.
	 */
	public function GetAccountByID($account_id) {
		return $this->Blockchain->API->call('get_account',[$account_id])["name"];
	}

}
