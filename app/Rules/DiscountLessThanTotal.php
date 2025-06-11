<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DiscountLessThanTotal implements Rule
{
    protected $totalAmount;

    public function __construct($totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }

    public function passes($attribute, $value)
    {
        return $value <= $this->totalAmount;
    }

    public function message()
    {
        return 'The discount value must be less than or equal to the total amount.';
    }
}
