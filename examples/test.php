<?php

namespace GraphenePHP;
error_reporting(-1);
ini_set("display_errors", 1);
require_once "../src/auto.php";


$Blockchain = new \GraphenePHP\Graphene("openledger");
echo "<pr e>".json_encode($Blockchain->Wallet->ReturnAccountHistory("openledger", 25), JSON_PRETTY_PRINT)."</pre>"; # 25 is the limit