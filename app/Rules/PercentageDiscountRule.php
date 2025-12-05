<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PercentageDiscountRule implements Rule
{
    protected $price;
    protected $priceOffer;

    public function __construct($price, $priceOffer)
    {
        $this->price = $price;
        $this->priceOffer = $priceOffer;
    }

    public function passes($attribute, $value)
    {
        if (empty($this->price) || empty($this->priceOffer) || empty($value)) {
            return true; // Let required rule handle empty values
        }
        
        // Calculate expected discount percentage
        $expectedDiscount = (($this->price - $this->priceOffer) / $this->price) * 100;
        $tolerance = 0.01; // Allow small rounding differences
        
        return abs((float) $value - $expectedDiscount) <= $tolerance;
    }

    public function message()
    {
        return 'The percentage discount does not match the price and offer price.';
    }
}


