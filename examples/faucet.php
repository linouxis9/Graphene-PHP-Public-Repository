<?php
# Quick 'n dirty faucet

namespace GraphenePHP;


require_once "../src/auto.php";
$account_to_register["name"] = json_decode($_POST['account'], true);
$account_registrar = "openledger"; // It needs to be a LTM account
$account_to_register["owner_key"] = $account_to_register["active_key"] = $_GET['public_key'];
$Blockchain = new \GraphenePHP\Graphene($account_registrar);



if ($Blockchain->Tools->is_not_premium($account_to_register)) {
    $Blockchain->Wallet->RegisterAccount($account_to_register, $account_registrar);
    http_response_code(201); // I need to investigate the result of OL's faucet, in order to determiner the right response code
    echo "OK";
} else {
    http_response_code(401);
    echo $account_to_register." is a premium account, therefore it cannot be registered.";
}