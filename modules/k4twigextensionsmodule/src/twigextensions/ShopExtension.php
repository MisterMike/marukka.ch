<?php

namespace modules\k4twigextensionsmodule\twigextensions;

use Craft;
use craft\commerce\elements\Order;
use craft\commerce\helpers\Currency;
use craft\commerce\models\LineItem;
use craft\commerce\models\OrderAdjustment;
use Kint\Kint;
use Twig\Extension\AbstractExtension;
use modules\k4twigextensionsmodule\traits\K4TwigExtensionsTrait;

class ShopExtension extends AbstractExtension
{
    use K4TwigExtensionsTrait;

    /**
     * @return string
     */
    public function getName()
    {
        return Craft::t('k4twigextensionsmodule', 'Shop Extension');
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters()
    {
        return [
            $this->addFilter('cumulateAll')

        ];
    }

    private $adjustments;

    /**
     * Dump and die
     */
    public function cumulateAll(Order $order)
    {
        $this->adjustments = array(
            'total'=>['amount'=>0,'amountAsCurrency'=>''],
            'discounts'=>[],
            'shipping'=>[],
            'taxes'=>[]
        );

        $lineItems = $order->getLineItems();

        foreach($lineItems as $item){
            $this->addToTotal($item,$order->currency);
            foreach ($item->getAdjustments() as $adjustment){
                switch ($adjustment->type) {
                    case 'tax':
                        $this->addTax($adjustment,$order->currency);
                        break;
                    case 'shipping':
                        $this->addShipping($adjustment,$order->currency);
                        break;
                    case 'discount':
                        $this->addDiscount($adjustment,$order->currency);
                        break;
                }
            }
        }

        foreach ($order->getOrderAdjustments() as $adjustment){
            switch ($adjustment->type) {
                case 'tax':
                    $this->addTax($adjustment,$order->currency);
                    break;
                case 'shipping':
                    $this->addShipping($adjustment,$order->currency);
                    break;
                case 'discount':
                    $this->addDiscount($adjustment,$order->currency);
                    break;
            }
        }

        return $this->adjustments;
    }

    private function asCurrency($amount,$currency){

        $convert = false;
        $format = true;
        $stripZeros = false;

        return Currency::formatAsCurrency($amount, $currency, $convert, $format, $stripZeros);
    }

    private function addTax(OrderAdjustment $adjustment,$currency){
        //Kint::dump($adjustment);
        if (!array_key_exists($adjustment->sourceSnapshot["id"],$this->adjustments["taxes"]))
        {
            $this->adjustments["taxes"][$adjustment->sourceSnapshot["id"]] = array(
                "name" => $adjustment->name,
                "description" => $adjustment->description,
                "amount" => 0,
                "amountAsCurrency" => 0,
                "rate" => (string) round(($adjustment->sourceSnapshot["rate"] * 100),2) . "%",
                "included" => $adjustment->sourceSnapshot["include"]
            );
        }
        $this->adjustments["taxes"][$adjustment->sourceSnapshot["id"]]["amount"] += $adjustment->amount;
        $this->adjustments["taxes"][$adjustment->sourceSnapshot["id"]]["amountAsCurrency"] = $this->asCurrency($this->adjustments["taxes"][$adjustment->sourceSnapshot["id"]]["amount"],$currency);

    }

    private function addShipping(OrderAdjustment $adjustment,$currency){

        if (!array_key_exists($adjustment->sourceSnapshot["id"], $this->adjustments["shipping"])) {
            $this->adjustments["shipping"][$adjustment->sourceSnapshot["id"]] = array(
                "name" => $adjustment->name,
                "description" => $adjustment->description,
                "amount" => 0,
                "amountAsCurrency" => 0,
                "included" => false
            );
        }
        $this->adjustments["shipping"][$adjustment->sourceSnapshot["id"]]["amount"] += $adjustment->amount;
        $this->adjustments["shipping"][$adjustment->sourceSnapshot["id"]]["amountAsCurrency"] = $this->asCurrency($this->adjustments["shipping"][$adjustment->sourceSnapshot["id"]]["amount"], $currency);

    }

    private function addDiscount(OrderAdjustment $adjustment,$currency){

        if (!array_key_exists($adjustment->sourceSnapshot["id"],$this->adjustments["discounts"]))
        {
            $this->adjustments["discounts"][$adjustment->sourceSnapshot["id"]] = array(
                "name" => $adjustment->name,
                "description" => $adjustment->description,
                "amount" => 0,
                "amountAsCurrency" => 0,
                "couponCode" => $order->couponCode??'',
                "included" => 1,

            );
        }
        $this->adjustments["discounts"][$adjustment->sourceSnapshot["id"]]["amount"] += $adjustment->amount;
        $this->adjustments["discounts"][$adjustment->sourceSnapshot["id"]]["amountAsCurrency"] = $this->asCurrency($this->adjustments["discounts"][$adjustment->sourceSnapshot["id"]]["amount"],$currency);

    }
    private function addToTotal(LineItem $lineItem,$currency)
    {
        $this->adjustments["total"]["amount"] += $lineItem->subtotal;
        $this->adjustments["total"]["amountAsCurrency"] = $this->asCurrency($this->adjustments["total"]["amount"],$currency);
    }

}
