<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ZatcaWrapper\ZatcaWrapper;

class ZatcaController extends Controller
{
    public function postZatcaData(Request $request)
    {
        date_default_timezone_set((config('app.timezone')));
        $currentDate = date('Y-m-d h:i:s a');
        // echo "<pre>"; print_r($currentDate); exit;
        $base64 = (new ZatcaWrapper())
            ->sellerName($request->sellerName)
            ->vatRegistrationNumber($request->vatRegistrationNumber)
            ->timestamp($currentDate)
            ->totalWithVat($request->totalWithVat)
            ->vatTotal($request->vatTotal)
            ->csrCommonName($request->csrCommonName)
            ->csrSerialNumber($request->csrSerialNumber)
            ->csrOrganizationIdentifier($request->csrOrganizationIdentifier)
            ->csrOrganizationUnitName($request->csrOrganizationUnitName)
            ->csrOrganizationName($request->csrOrganizationName)
            ->csrCountryName($request->csrCountryName)
            ->csrInvoiceType($request->csrInvoiceType)
            ->csrLocationAddress($request->csrLocationAddress)
            ->csrIndustryBusinessCategory($request->csrIndustryBusinessCategory)
            ->toBase64();
        return response()->json($base64, 200);

    }
}
