<?php
namespace App\Services;

use App\Models\{
    Product,
};
use Illuminate\Support\Facades\Http;

class ProductService
{
    /********************************************************************/
    public function getAllProducts()
    {

        return Product::with(['user', 'items'])->get();
    }
    /********************************************************************/
    public function storeProduct($data, $id = null)
    {
        if ($id == null) {
            $addProduct = new Product;
        } else if ($id != null) {
            $addProduct = Product::find($id);
        }
        $addProduct->name = $data['productnameenglish'];
        $addProduct->slug = \Str::slug($data['productnameenglish']);
        // $addProduct->brand = $data['brand'];
        $addProduct->size = $data['size'];
        $addProduct->barcode = $data['product_code'];
        $addProduct->unit = $data['unit'];
        $addProduct->purchase_price = $data['purchase_price'];
        $addProduct->selling_price = $data['selling_price'];
        $addProduct->details_page = isset($data['details_page']) ? $data['details_page'] : null;
        $addProduct->status = 'active';
        return $addProduct;
    }

    /********************************************************************/
    public function autoCompProduct($request)
    {
        $productAuto = [];
        $products = Product::with('items')->where('name', 'LIKE', "%" . $request->term . "%")->get();
        if ($products) {
            foreach ($products as $key => $value) {
                $countItems = $value->items->sum('qty');
                $productAuto[] = array(
                    "value" => $value->name,
                    "productID" => $value->id,
                    "prodcutName" => $value->name,
                    "qty" => $countItems,
                );
            }
            return $productAuto;
        }
    }
    /*******************************************************************/
    public function productData()
    {
        $user_info = session('user_info');

        $brands = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user_info['token'],
        ])->get('https://gs1ksa.org:3093/api/brands', [
                    'user_id' => $user_info['memberData']['id'],
                ]);
        $brandsBody = $brands->getBody();
        $brandsData = json_decode($brandsBody, true);

        $units = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user_info['token'],
        ])->get('https://gs1ksa.org:3093/api/getAllunit');
        $unitsBody = $units->getBody();
        $unitsData = json_decode($unitsBody, true);


        $countryOfSale = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user_info['token'],
        ])->get('https://gs1ksa.org:3093/api/getAllcountryofsale');

        $countryOfSaleBody = $countryOfSale->getBody();
        $countryOfSaleData = json_decode($countryOfSaleBody, true);


        $prodLang = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user_info['token'],
        ])->get('https://gs1ksa.org:3093/api/getAllprod_desc_languages');

        $prodLangBody = $prodLang->getBody();
        $prodLangSaleData = json_decode($prodLangBody, true);


        $prodTypes = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user_info['token'],
        ])->get('https://gs1ksa.org:3093/api/productTypes');
        $prodTypesBody = $prodTypes->getBody();
        $prodTypesData = json_decode($prodTypesBody, true);

        $pkgTypes = Http::withHeaders([
            'Authorization' => 'Bearer ' . $user_info['token'],
        ])->get('https://gs1ksa.org:3093/api/getAllproductPackag');


        $pkgTypesBody = $pkgTypes->getBody();
        $pkgTypesData = json_decode($pkgTypesBody, true);

        return [
            'brandsData' => $brandsData,
            'unitsData' => $unitsData,
            'countryOfSaleData' => $countryOfSaleData,
            'prodLangSaleData' => $prodLangSaleData,
            'prodTypesData' => $prodTypesData,
            'pkgTypesData' => $pkgTypesData
        ];
    }
}
