<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DifferentGln implements Rule
{
    public function __construct()
    {
        // Constructor can be left empty or used for initialization
    }

    public function passes($attribute, $value)
    {
        // Since we are validating two fields, we need to access both of them
        $request = request();
        return $request->input('gln_from') !== $request->input('gln_to');
    }

    public function message()
    {
        return 'The GLN From and GLN To fields must be different.';
    }
}
