<?php

namespace App\Traits;

use App\Models\Inventory\GoodsReceiptNote;
use App\Models\Inventory\MaterialIssueNote;
use App\Models\Inventory\MaterialReturnNote;
use App\Models\Inventory\Product;
use App\Models\Inventory\Stock as InventoryStock;

trait Stock
{
    public function updateStock($product_id = null, $type = null, $goods_receipt_note_delete_id = null){
        if($type == 'opening_stock'){
            $product = Product::find($product_id);
            if($product){
                $stock = InventoryStock::whereProductId($product_id);
                if($stock->exists()){
                    $stock->update([
                        'opening_stock' => $product->opening_stock
                    ]);
                }
                else{
                    InventoryStock::create([
                        'product_id' => $product->id,
                        'opening_stock' => $product->opening_stock
                    ]);
                }
            }
        }
       else if($type == 'min_qty'){
            $product = Product::find($product_id);
            if($product){
                $stock = InventoryStock::whereProductId($product_id);
                if($stock->exists()){
                    $stock->update([
                        'min_qty' => $product->min_qty
                    ]);
                }
                else{
                    InventoryStock::create([
                        'product_id' => $product->id,
                        'min_qty' => $product->min_qty
                    ]);
                }
            }
        }
        else if($type == 'total_qty'){
            if($goods_receipt_note_delete_id){
                $goods_receipts = GoodsReceiptNote::find($goods_receipt_note_delete_id);
                foreach($goods_receipts?->inwardGatepass?->detail as $detail){
                    $qty = InventoryStock::whereProductId($detail->product_id)->value('total_qty');
                    InventoryStock::whereProductId($detail->product_id)->update([ 'total_qty' => $qty - $detail->qty]);
                }
            }
            else{
                $goods_receipts = GoodsReceiptNote::get();
                $product_ids = $total_qty = [];

                foreach($goods_receipts as $goods_receipt){
                    foreach($goods_receipt?->inwardGatepass?->detail as $detail){
                        if(in_array($detail->product_id, $product_ids)){
                            $index = array_search($detail->product_id, $product_ids);
                            $total_qty[$index] += $detail->qty;
                        }
                        else{
                            $product_ids[] = $detail->product_id;
                            $total_qty[] = $detail->qty;
                        }
                    }
                }

                foreach($product_ids as $index => $product_id){
                    $stock = InventoryStock::whereProductId($product_id);
                    if($stock->exists()){
                        $stock->update([
                            'product_id' => $product_id,
                            'total_qty' => $total_qty[$index]
                        ]);
                    }
                    else{
                        InventoryStock::create([
                            'product_id' => $product_id,
                            'total_qty' => $total_qty[$index]
                        ]);
                    }
                }
            }
        }
        else if($type == 'store_stock'){
            $issue_goods = MaterialIssueNote::get();
            $product_ids = $total_qty = [];

            foreach($issue_goods as $issue_good){
                foreach($issue_good?->detail as $detail){
                    if(in_array($detail->product_id, $product_ids)){
                        $index = array_search($detail->product_id, $product_ids);
                        $total_qty[$index] += $detail->qty;
                    }
                    else{
                        $product_ids[] = $detail->product_id;
                        $total_qty[] = $detail->qty;
                    }
                }
            }

            foreach($product_ids as $index => $product_id){
                $stock = InventoryStock::whereProductId($product_id);
                if($stock->exists()){
                    $stock->update([
                        'product_id' => $product_id,
                        'used_stock' => $total_qty[$index]
                    ]);
                }
                else{
                    InventoryStock::create([
                        'product_id' => $product_id,
                        'used_stock' => $total_qty[$index]
                    ]);
                }
            }
            InventoryStock::whereNotIn('product_id',$product_ids)->update(['used_stock' => 0]);
        }
        else if($type == 'return_stock'){
            $issue_goods = MaterialReturnNote::get();
            $product_ids = $total_qty = [];

            foreach($issue_goods as $issue_good){
                foreach($issue_good?->detail as $detail){
                    if(in_array($detail->product_id, $product_ids)){
                        $index = array_search($detail->product_id, $product_ids);
                        $total_qty[$index] += $detail->qty;
                    }
                    else{
                        $product_ids[] = $detail->product_id;
                        $total_qty[] = $detail->qty;
                    }
                }
            }

            foreach($product_ids as $index => $product_id){
                $stock = InventoryStock::whereProductId($product_id);
                if($stock->exists()){
                    $stock->update([
                        'product_id' => $product_id,
                        'return_stock' => $total_qty[$index]
                    ]);
                }
                else{
                    InventoryStock::create([
                        'product_id' => $product_id,
                        'return_stock' => $total_qty[$index]
                    ]);
                }
            }
            InventoryStock::whereNotIn('product_id',$product_ids)->update(['return_stock' => 0]);
        }
    }
}
