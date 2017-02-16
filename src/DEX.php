<?php
/**
 * DEX.php
 *
 * DEX class
 *
 * @author     Valentin D'Emmanuele
 * @copyright  2016 Valentin D'Emmanuele
 * @license    Mozilla Public License Version 2.0
 * @version    1.0
 */

namespace GraphenePHP;

require_once "auto.php";

/**
 * This class is containing the functions needed to interface with the Bitshares' DEX.
 */
class GrapheneDEX
{

    public function __construct($Blockchain){
        $this->Blockchain = $Blockchain;
    }
    /**
     * Returns the order book of a [$asset_a : $asset_b] market.
     *
     * @param string $asset_a The base asset of the market.
     * @param string $asset_b The quote asset of the market.
     * @param int $limit The number of order you want from the order book. If $limit is set to 25, you will have the 25 highest bids and the 25 lowest asks.
     *
     * @return array Returns a multidimensional array containing the order book.
     */
    public function returnOrderBook($asset_a, $asset_b, $limit){
        $orderbook = $asset = Array();
        $asset = $this->Blockchain->Tools->AssetAssign($asset_a, $asset);
        $asset = $this->Blockchain->Tools->AssetAssign($asset_b, $asset);
        $order_book = $this->Blockchain->API->call('get_limit_orders', [$asset_a, $asset_b, $limit]);
        foreach($order_book as $key=>$data) {
            $orderbook[$key] = Array(
                "id" => $data["id"],
                "base" => $asset[$data["sell_price"]["base"]['asset_id']]["symbol"],
                "quote" => $asset[$data["sell_price"]["quote"]['asset_id']]["symbol"],
                "price" => Array(
                    "base" => $data["sell_price"]["base"]['amount'] / $asset[$data["sell_price"]["base"]['asset_id']]["precision"],
                    "quote" => $data["sell_price"]["quote"]['amount'] / $asset[$data["sell_price"]["quote"]['asset_id']]["precision"]
                )
            );
        }
        return $orderbook;
    }

    /**
     * Returns the ticker of an asset.
     *
     * @param string $asset_a The base asset of the market.
     * @param string $asset_b The quote asset of the market.
     *
     * @return array Returns a multidimensional array containing a ticker of the OPEN assets.
     */
    public function returnTicker($asset_a, $asset_b){
        $ticker = Array();
        $ticker[$asset_a]["today_candle"] = end($this->returnChartData($asset_a, $asset_b, 24 * 60 * 60));
        $result = $this->Blockchain->Tools->Average($asset_a, $asset_b);
        $ticker[$asset_a]["average_price"] = Array(
            "base" => $asset_a,
            "highest_bid" => $result[$asset_a],
            "lowest_ask" => $result[$asset_b]
        );
        return $ticker;
    }

    /**
     * Returns the chart data of a [$asset_a : $asset_b] market.
     *
     * @param string $asset_a The base asset of the market.
     * @param string $asset_b The quote asset of the market.
     * @param int $time That's the timeframe you want. For one day, it should be 24*60*60.
     *
     * @return array Returns a multidimensional array containing the order book.
     */
    public function returnChartData($asset_a, $asset_b, $time){
        $assets_a = $this->Blockchain->Asset->InfoOf($asset_a); // To replace with $this->Blockchain->Tools->AssetAssign()
        $assets_b = $this->Blockchain->Asset->InfoOf($asset_b);
        $market_history = $this->Blockchain->API->call('get_market_history', [$assets_a['symbol'], $assets_b['symbol'], ($time)]);
        $markethistory = Array();
        foreach($market_history as $key=>$data) {
            $markethistory[$key] = Array(
                "id" => $data["id"],
                "open_time" => $data["key"]["open"],
                "base" => $asset_a,
                "quote" => $asset_b,
                "data" => Array(
                    "high_base" => $data["high_base"] / $assets_a["precision"], // To be replaced by a second foreach
                    "high_quote" => $data["high_quote"] / $assets_b["precision"],
                    "low_base" => $data["low_base"] / $assets_a["precision"],
                    "low_quote" => $data["low_quote"] / $assets_b["precision"],
                    "open_base" => $data["open_base"] / $assets_a["precision"],
                    "open_quote" => $data["open_quote"] / $assets_b["precision"],
                    "close_base" => $data["close_base"] / $assets_a["precision"],
                    "close_quote" =>$data["close_quote"] / $assets_b["precision"]
                )
            );

        }
        return $markethistory;
    }
}
