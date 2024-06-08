<?php
namespace App\Services;

use App\Models\{
    Product,
};
use App\Models\Brand;
use App\Models\Unit;
use Illuminate\Support\Facades\Http;

class ProductService
{
    /********************************************************************/
    public function getAllProducts($token, $gs1MemberID)
    {

        $apiProducts = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://gs1ksa.org:3093/api/products', [
                    'user_id' => $gs1MemberID,
                ]);


        $apiProductsBody = $apiProducts->getBody();
        $apiProductssData = json_decode($apiProductsBody, true);
        // echo "<pre>"; print_r($apiProductssData); exit;
        if (isset($apiProductssData['error']) && !empty($apiProductssData['error'])) {
            return ['error' => $apiProductssData['error']];
        }
        $apiArrayData = [];
        if ($apiProductssData) {
            foreach ($apiProductssData as $key => $apiP) {
                $url = 'https://gs1ksa.org:3093/';
                $image = ($apiP['front_image']) ? $url . $apiP['front_image'] : asset('assets/uploads/no-image.png');
                $apiArrayData[] = array(
                    'id' => $apiP['id'],
                    'user_id' => $apiP['user_id'],
                    'image' => $image,
                    'productnameenglish' => $apiP['productnameenglish'],
                    'productnamearabic' => $apiP['productnamearabic'],
                    'BrandName' => $apiP['BrandName'],
                    'barcode' => $apiP['barcode'],
                    'type' => 'gs1_product',
                );
            }
        }



        $localProducts = Product::get();

        $localArrayData = [];
        if ($localProducts) {
            foreach ($localProducts as $key => $local) {
                $image = ($local['front_image']) ? getFile('products', $local['front_image']) : asset('assets/uploads/no-image.png');
                $localArrayData[] = array(
                    'id' => $local['id'],
                    'user_id' => $local['user_id'],
                    'image' => $image,
                    'productnameenglish' => $local['productnameenglish'],
                    'productnamearabic' => $local['productnamearabic'],
                    'BrandName' => $local['BrandName'],
                    'barcode' => $local['barcode'],
                    'type' => $local['type'],
                );
            }
        }

        $mergeProducts = array_merge($apiArrayData, $localArrayData);
        return $localArrayData;
    }
    /********************************************************************/
    public function storeProduct($data, $id = null, $gcpGLNID, $barcode)
    {
        if ($id == null) {
            $addProduct = new Product;
        } else if ($id != null) {
            $addProduct = Product::find($id);
        }

        if (isset($data['front_image']) && !empty($data['front_image'])) {
            $filename = uploadImage($data['front_image'], filePath('products'), $addProduct->front_image);
            $addProduct->front_image = $filename;
        }
        if (isset($data['back_image']) && !empty($data['back_image'])) {
            $filename = uploadImage($data['back_image'], filePath('products'), $addProduct->back_image);
            $addProduct->back_image = $filename;
        }

        if (isset($data['image_1']) && !empty($data['image_1'])) {
            $filename = uploadImage($data['image_1'], filePath('products'), $addProduct->image_1);
            $addProduct->image_1 = $filename;
        }
        if (isset($data['image_2']) && !empty($data['image_2'])) {
            $filename = uploadImage($data['image_2'], filePath('products'), $addProduct->image_2);
            $addProduct->image_2 = $filename;
        }
        if (isset($data['image_3']) && !empty($data['image_3'])) {
            $filename = uploadImage($data['image_3'], filePath('products'), $addProduct->image_3);
            $addProduct->image_3 = $filename;
        }

        $addProduct->gcpGLNID = $gcpGLNID;
        $addProduct->type = $data['product_type'];
        $addProduct->productnameenglish = $data['productnameenglish'];
        $addProduct->productnamearabic = isset($data['productnamearabic']) ? $data['productnamearabic'] : '';
        $addProduct->slug = \Str::slug($data['productnameenglish']);
        $addProduct->BrandName = $data['BrandName'];
        $addProduct->BrandNameAr = isset($data['BrandNameAr']) ? $data['BrandNameAr'] : '';
        $addProduct->ProductType = isset($data['ProductType']) ? $data['ProductType'] : '';
        $addProduct->Origin = isset($data['Origin']) ? $data['Origin'] : '';
        $addProduct->PackagingType = isset($data['PackagingType']) ? $data['PackagingType'] : '';
        $addProduct->unit = $data['unit'];
        $addProduct->size = $data['size'];
        $addProduct->gpc = isset($data['gpc']) ? $data['gpc'] : '';
        $addProduct->gpc_code = isset($data['gpc_code']) ? $data['gpc_code'] : '';
        $addProduct->countrySale = isset($data['countrySale']) ? $data['countrySale'] : '';
        $addProduct->HSCODES = '1234.56.78';
        $addProduct->HsDescription = isset($data['HsDescription']) ? $data['HsDescription'] : '';
        $addProduct->gcp_type = 1;
        $addProduct->prod_lang = isset($data['prod_lang']) ? $data['prod_lang'] : '';
        $addProduct->barcode = $barcode;

        $addProduct->quantity = $data['quantity'];
        $addProduct->purchase_price = $data['purchase_price'];
        $addProduct->selling_price = $data['selling_price'];
        $addProduct->details_page = isset($data['details_page']) ? $data['details_page'] : null;
        $addProduct->details_page_ar = isset($data['details_page_ar']) ? $data['details_page_ar'] : null;
        $addProduct->product_url = isset($data['product_url']) ? $data['product_url'] : null;
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
    public function localProductData()
    {
        $unitsData = Unit::get();
        $brandsData = Brand::get();
        return [
            'brandsData' => $brandsData,
            'unitsData' => $unitsData,
        ];
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
