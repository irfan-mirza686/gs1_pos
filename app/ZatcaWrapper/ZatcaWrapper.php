<?php
namespace App\ZatcaWrapper;

use Prgayman\Zatca\Zatca as BaseZatca;

class ZatcaWrapper
{
    protected $zatca;

    public function __construct()
    {
        $this->zatca = new BaseZatca();
    }

    public function sellerName($value)
    {
        $this->zatca->sellerName($value);
        return $this;
    }

    public function vatRegistrationNumber($value)
    {
        $this->zatca->vatRegistrationNumber($value);
        return $this;
    }

    public function timestamp($value)
    {
        $this->zatca->timestamp($value);
        return $this;
    }

    public function totalWithVat($value)
    {
        $this->zatca->totalWithVat($value);
        return $this;
    }

    public function vatTotal($value)
    {
        $this->zatca->vatTotal($value);
        return $this;
    }

    // Set CSR fields
    public function csrCommonName($value)
    {
        $this->zatca->csrCommonName($value);
        return $this;
    }

    public function csrSerialNumber($value)
    {
        $this->zatca->csrSerialNumber($value);
        return $this;
    }

    public function csrOrganizationIdentifier($value)
    {
        $this->zatca->csrOrganizationIdentifier($value);
        return $this;
    }

    public function csrOrganizationUnitName($value)
    {
        $this->zatca->csrOrganizationUnitName($value);
        return $this;
    }

    public function csrOrganizationName($value)
    {
        $this->zatca->csrOrganizationName($value);
        return $this;
    }

    public function csrCountryName($value)
    {
        $this->zatca->csrCountryName($value);
        return $this;
    }

    public function csrInvoiceType($value)
    {
        $this->zatca->csrInvoiceType($value);
        return $this;
    }

    public function csrLocationAddress($value)
    {
        $this->zatca->csrLocationAddress($value);
        return $this;
    }

    public function csrIndustryBusinessCategory($value)
    {
        $this->zatca->csrIndustryBusinessCategory($value);
        return $this;
    }

    // Add other custom methods as needed

    public function toBase64()
    {
        return $this->zatca->toBase64();
    }
}
