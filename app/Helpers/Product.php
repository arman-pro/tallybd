<?php
namespace App\Helpers;

use App\StockDetail;
use App\StockHistory;
use GuzzleHttp\Psr7\Request;

class Product
{
    public function average_price($item_id)
    {
        $total_purchases_price = 0;
        $total_qty = 0;
        $stock = StockHistory::where('item_id', $item_id)
        ->whereIn('stockable_type', ['App\PurchasesAddList','App\Item', 'App\PurchasesReturnAddList'])
        ->orWhere(function($query) {
            $query->where('stockable_type','App\StockAdjustment');
        })
        ->get();
        $total_purchases_price = $stock->sum('total_average_price');
        $total_qty = $stock->sum('total_qty');
        return ($total_purchases_price / $total_qty) ;
    }


    function generateRandomString($length = 3) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString.date('y');
    }
}
