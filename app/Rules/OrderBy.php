<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Schema;

class OrderBy implements ValidationRule
{
    private $tableName='';
    /**
     * Checks whether the column name contained in the field under validation exists on the specified table
     * @param String $table table to check against for the existance of the column
     */
    function __construct($tableName)
    {
        $this->tableName = $tableName;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(Schema::hasColumn($this->tableName,$value) == false){
            $fail('Trying to orderBy a non existing column');
        }
    }
}
