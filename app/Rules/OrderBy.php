<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Schema;

class OrderBy implements ValidationRule
{
    private $table='';
    function __construct($table)
    {
        $this->table = $table;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(Schema::hasColumn($this->table,$value) == false){
            $fail('Trying to orderBy a non existing column');
        }
    }
}
