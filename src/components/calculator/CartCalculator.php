<?php


namespace DotPlant\Store\components\calculator;

use DotPlant\Store\helpers\ExtendedPriceHelper;
use DotPlant\Store\interfaces\DeliveryTermCalculatorInterface;
use DotPlant\Store\interfaces\NoGoodsCalculatorInterface;
use DotPlant\Store\models\order\Cart;
use DotPlant\Store\Module;
use yii\base\InvalidParamException;

class CartCalculator implements NoGoodsCalculatorInterface, DeliveryTermCalculatorInterface
{

    /**
     * Calculates object price
     *
     * @param Cart $cart
     *
     * @return array
     */
    public static function getPrice($cart)
    {
        if ($cart instanceof Cart === false) {
            throw new InvalidParamException;
        }
        $price = [
            'totalPriceWithoutDiscount' => 0,
            'totalPriceWithDiscount' => 0,
            'items' => 0,
            'extendedPrice' => [],
            'isoCode' => $cart->currency_iso_code,
        ];
        $extendedPrices = ExtendedPriceHelper::getForObject($cart);

        foreach ($cart->items as $item) {
            $item->calculate();
            $price['totalPriceWithDiscount'] += $item->total_price_with_discount;
            $price['totalPriceWithoutDiscount'] += $item->total_price_without_discount;
            $price['items'] += Module::module()->countUniqueItemsOnly == 1 ? 1 : $item->quantity;
        }

        $extPricesApplied = ExtendedPriceHelper::applyExtendedPrices(
            $extendedPrices,
            $price['totalPriceWithDiscount'],
            $cart->currency_iso_code
        );

        $price['totalPriceWithDiscount'] = $extPricesApplied['priceAfter'];
        $price['extendedPrice'] = $extPricesApplied['extendedPrice'];

        return $price;
    }


    /**
     * @param Cart $object
     * @return int
     */
    public static function getDeliveryTerm($object)
    {
        $itemsDeliveryTerm = [];
        if (Module::getInstance()->deliveryFromWarehouse == false) {
            foreach ($object->items as $item) {
                if ($itemDeliveryTerm = $item->getDeliveryTerm()) {
                    $itemsDeliveryTerm[] = $itemDeliveryTerm;
                }
            }
        }
        /** @todo get customer delivery term */
        $customerDeliveryTerm = 0;
        return (empty($itemsDeliveryTerm) === true ? 0 : max($itemsDeliveryTerm)) + $customerDeliveryTerm;
    }
}
