<?php

/**
 * explorer.php
 *
 * Explore the blockchain
 *
 * @author     Valentin D'Emmanuele
 * @copyright  2016 Valentin D'Emmanuele
 * @license    Mozilla Public License Version 2.0
 * @version    1.0
 */

namespace GraphenePHP;

require_once "auto.php";

class GrapheneExplorer
{
	public function __construct($Blockchain){
		$this->Blockchain = $Blockchain;
	}
}
