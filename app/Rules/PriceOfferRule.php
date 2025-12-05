<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PriceOfferRule implements Rule
{
    protected $price;

    public function __construct($price)
    {
        $this->price = $price;
    }

    public function passes($attribute, $value)
    {
        if (empty($this->price) || empty($value)) {
            return true; // Let required rule handle empty values
        }
        
        return (float) $value <= (float) $this->price;
    }

    public function message()
    {
        return 'The offer price must be less than or equal to the regular price.';
    }
}



