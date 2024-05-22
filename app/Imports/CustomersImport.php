<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\CustomerAddress;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Validation\Rule;

class CustomersImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $rowsArray = $rows->toArray();
        $filteredData = array_filter($rowsArray, function ($item) {
            return !empty (array_filter($item));
        });
        // echo "<pre>"; print_r($filteredData); exit;
        foreach ($filteredData as $key => $value) {

            $customer = new Customer;
            $address = explode('|', $value['address']);
            $customer->name = $value['customername'];
            $customer->mobile = $value['mobile'];
            $customer->vat = $value['vat'];
            $customer->status = $value['status'];
            $customer->user_id = 0;
            if ($customer->save()) {
                foreach ($address as $add) {
                    $customerAddress = new CustomerAddress;
                    $customerAddress->customer_id = $customer->id;
                    $customerAddress->address = $add;
                    $customerAddress->save();
                }
            }

        }
    }

    // public function rules(): array
    // {

    //     return [
    //         '*.customername' => 'required|string|unique:customers,name',
    //         // Table name, field in your db
    //         '*.mobile' => 'required|string|unique:customers,mobile',
    //         '*.vat' => 'required|string|unique:customers,vat',
    //         // Table name, field in your db

    //     ];
    // }

    // public function customValidationMessages()
    // {
    //     return [
    //         'customername.unique' => 'Customer Name is Duplicate',
    //         'customername.string' => 'Customer Name Must be Valid',
    //         'mobile.unique' => 'Mobile # is Duplicate',
    //         'vat.unique' => 'Vat # is Duplicate'
    //     ];
    // }
    public function chunkSize(): int
    {
        return 1000;
    }
}
