<?php

/**
 * asset.php
 *
 * Asset class
 *
 * @author     Valentin D'Emmanuele
 * @copyright  2016 Valentin D'Emmanuele
 * @license    Mozilla Public License Version 2.0
 * @version    1.0
 */

namespace GraphenePHP;

require_once "auto.php";

/**
 * This class is containing the functions needed to describe and manage assets.
 */
class GrapheneAsset
{
		public function __construct($Blockchain){
			$this->Blockchain = $Blockchain;
		}
		public function InfoOf($asset){
			$asset2 = $this->Blockchain->API->call('get_asset', [$asset]);
			$array = Array(
				"name" => $asset,
				"asset" => $asset2,
                "id" => $asset2['id'],
                "precision" => 10 ** $asset2['precision'],
                "symbol" => $asset2['symbol'],
                "issuer" => $this->Blockchain->API->call('get_account', [$asset2['issuer']])['name']
                
			);
			return $array;
		}

}
