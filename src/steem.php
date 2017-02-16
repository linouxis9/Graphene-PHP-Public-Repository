<?php

/**
 * steem.php
 *
 * Interact with Steem, because you esteem steam!
 *
 * @author     Valentin D'Emmanuele
 * @copyright  2016 Valentin D'Emmanuele
 * @license    Mozilla Public License Version 2.0
 * @version    1.0
 */

namespace GraphenePHP;

require_once "auto.php";

/**
 * This class is responsible of the functions related to the STEEM social network.
 */
class GrapheneSteem
{
	public function __construct($Blockchain){
		$this->Blockchain = $Blockchain;
	}
}
