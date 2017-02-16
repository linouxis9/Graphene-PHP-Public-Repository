<?php

/**
 * Blockchain.php
 *
 * Blockchain modules manager
 *
 * @author     Valentin D'Emmanuele
 * @copyright  2016 Valentin D'Emmanuele
 * @license    Mozilla Public License Version 2.0
 * @version    1.0
 */

namespace GraphenePHP;

require_once "src/auto.php";


/**
 * This class initialize each component of Graphene-PHP and should pass by this class to use one of the Graphene-PHP functions.
 */
class Graphene
{
	public function __construct($account){
		$this->name = $account;
		$this->API = new GrapheneAPI("http://127.0.0.1:8091/rpc");
		$this->Wallet = new GrapheneWallet($this);
		$this->DEX = new GrapheneDEX($this);
		$this->Asset = new GrapheneAsset($this);
		$this->Tools = new GrapheneTools($this);
		$this->Steem = new GrapheneSteem($this);
		$this->Operations = ["transfer_operation", "limit_order_create_operation", "limit_order_cancel_operation", "call_order_update_operation", "fill_order_operation", "account_create_operation", "account_update_operation", "account_whitelist_operation", "account_upgrade_operation", "account_transfer_operation", "asset_create_operation", "asset_update_operation", "asset_update_bitasset_operation", "asset_update_feed_producers_operation", "asset_issue_operation", "asset_reserve_operation", "asset_fund_fee_pool_operation", "asset_settle_operation", "asset_global_settle_operation", "asset_publish_feed_operation", "witness_create_operation", "witness_update_operation", "proposal_create_operation", "proposal_update_operation", "proposal_delete_operation", "withdraw_permission_create_operation", "withdraw_permission_update_operation", "withdraw_permission_claim_operation", "withdraw_permission_delete_operation", "committee_member_create_operation", "committee_member_update_operation", "committee_member_update_global_parameters_operation", "vesting_balance_create_operation", "vesting_balance_withdraw_operation", "worker_create_operation", "custom_operation", "assert_operation", "balance_claim_operation", "override_transfer_operation", "transfer_to_blind_operation", "blind_transfer_operation", "transfer_from_blind_operation", "asset_settle_cancel_operation", "asset_claim_fees_operation", "fba_distribute_operation"];
	}


}
