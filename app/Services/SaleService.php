<?php 

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use App\Helpers\SalesHelper;

class SaleService
{
    public function createSale(array $saleData, array $itemsData): Sale
    {
        return DB::transaction(function () use ($saleData, $itemsData) {

            $totals = SalesHelper::calculateSaleTotal($itemsData);

            $saleData = array_merge($saleData, [
                'subtotal' => $totals['subtotal'],
                'discount_amount' => $totals['discount_amount'],
                'total_amount' => $totals['total_amount'],
            ]);

            $sale = Sale::create($saleData);

            foreach ($itemsData as $item) {
                $lineTotal = SalesHelper::calculateLineItemTotal(
                    quantity: (int) $item['quantity'],
                    unitPrice: (float) $item['unit_price'],
                    discountPercentage: (float) ($item['discount_percentage'] ?? 0)
                );

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'discount_amount' => $lineTotal['discount_amount'],
                    'total_price' => $lineTotal['total_price'],
                ]);
            }

            return $sale;
        });
    }
}
