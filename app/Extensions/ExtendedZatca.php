<?php
namespace App\Extensions;

use Prgayman\Zatca\Facades\Zatca as BaseZatca;

class ExtendedZatca extends BaseZatca
{
    public function csrCommonName($value)
    {
        $this->csr_common_name = $value;
        return $this;
    }

    public function csrSerialNumber($value)
    {
        $this->csr_serial_number = $value;
        return $this;
    }


    // Define other custom methods for additional inputs...

    // Override the toBase64 method if necessary to include the new inputs
    public function toBase64()
    {
        // Custom logic to include the new inputs
        return parent::toBase64();
    }
}
