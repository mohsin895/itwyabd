<?php


namespace App\Helpers;

class SalesHelper
{
    public static function calculateSaleTotal(array $items): array
    {
        $subtotal = 0;
        $totalDiscount = 0;

        foreach ($items as $item) {
            $quantity = (int) $item['quantity'];
            $unitPrice = (float) $item['unit_price'];
            $discountPercentage = (float) ($item['discount_percentage'] ?? 0);

            $lineTotal = $quantity * $unitPrice;
            $discountAmount = ($lineTotal * $discountPercentage) / 100;
            $lineTotalAfterDiscount = $lineTotal - $discountAmount;

            $subtotal += $lineTotalAfterDiscount;
            $totalDiscount += $discountAmount;
        }

        $grandTotal = $subtotal;

        return [
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($totalDiscount, 2),
            'total_amount' => round($grandTotal, 2),
        ];
    }

    public static function calculateLineItemTotal(
        int $quantity,
        float $unitPrice,
        float $discountPercentage = 0
    ): array {
        $lineTotal = $quantity * $unitPrice;
        $discountAmount = ($lineTotal * $discountPercentage) / 100;
        $totalAfterDiscount = $lineTotal - $discountAmount;

        return [
            'line_total' => round($lineTotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'total_price' => round($totalAfterDiscount, 2),
        ];
    }
}
