<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;
use App\ZatcaWrapper\ZatcaWrapper;

class SaleController extends Controller
{
    private $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }
    /********************************************************************/
    public function findProduct(Request $request)
    {
        $token = $request->header('Authorization');
        $barcode = $request->barcode;
        $product = $this->saleService->findProductData($token, $barcode);
        if ($product['status'] === 404) {
            return response()->json(['message' => $product['message']], 404);
        } else {
            return response()->json(['prodArray' => $product['prodArray']], 200);
        }
    }
    /********************************************************************/
    public function saveSale(Request $request)
    {
        try {
            $data = $request->all();

            $items = $this->saleService->makeItemsArr($data);
            $pos = $this->saleService->saveSale($data, $id = "");
            \DB::beginTransaction();

            $pos->items = $items;
            // echo "<pre>";
            // print_r($pos);
            // exit();
            $totalVat = 0;
            // echo "<pre>"; print_r($getInvoiceData->items); exit;
            // Loop through each object in the array
            foreach ($pos->items as $product) {
                // Add vat_total of each product to the totalVat
                $totalVat += $product['vat_total'];
            }
            if ($pos->save()) {
                $base64 = (new ZatcaWrapper())
                    ->sellerName('Saudi Leather Industries Factory Company Ltd')
                    ->vatRegistrationNumber("300456416500003")
                    ->timestamp("2021-12-01T14:00:09Z")
                    ->totalWithVat($pos->total)
                    ->vatTotal($totalVat)
                    ->csrCommonName('Saudi Leather Industries Factory Company Ltd')
                    ->csrSerialNumber('2050011041')
                    ->csrOrganizationIdentifier('3844')
                    ->csrOrganizationUnitName('1')
                    ->csrOrganizationName('OutSeller')
                    ->csrCountryName('KSA')
                    ->csrInvoiceType('zatca')
                    ->csrLocationAddress('Dammam')
                    ->csrIndustryBusinessCategory('Manufacturing')
                    ->toBase64();
                \LogActivity::addToLog(strtoupper($data['company_name_eng']) . ' Add a new Sale Order (' . $data['order_no'] . ')', route('sale.view', $pos->order_no));
                \DB::commit();
                return response()->json(['message' => 'Data has been saved successfully', 'invoice_no' => time(), 'qr_code' => $base64], 200);
            } else {
                return response()->json(['message' => 'Data has not been saved'], 401);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    public function allInvoices(Request $request)
    {
        try {
            $invoices = Sale::select('id','order_no','total','date','time')->get();
            return response()->json(['invoice' => $invoices], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
    public function singleInvoice(Request $request,$id=null)
    {
        try {
            $invoices = Sale::find($id);
            if ($invoices) {
                return response()->json(['invoices' => $invoices], 200);
            }else{
                return response()->json(['message' => 'No Record Found'], 404);
            }

        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 500);
        }
    }
}
