<?php
namespace App\Services;

use App\Models\{
    Customer,
};
use Excel;

class CustomerService
{
    public function getAllData()
    {
        return Customer::with('customer_address')->get();
    }
    /********************************************************************/

    public function autocompCustomer($request)
    {
        $customerAuto = [];
        $customers = Customer::with('customer_address')->where('mobile', 'LIKE', "%" . $request->term . "%")->orWhere('name', 'LIKE', "%" . $request->term . "%")->paginate(5);
        // echo "<pre>"; print_r($customers->toArray()); exit;
        if ($customers) {
            foreach ($customers as $key => $value) {
                $customerAuto[] = array(
                    "value" => $value->mobile,
                    "customerID" => $value->id,
                    "customerName" => $value->name,
                    "customerMobile" => $value->mobile,
                    "vat_no" => $value->vat,
                    "address" => $value->customer_address
                );
            }
            return $customerAuto;
        }
    }
    /********************************************************************/
    public function saveCustomer($data, $id = null)
    {
        if ($id == null) {
            $create = new Customer;
        } else if ($id != null) {
            $create = Customer::find($id);
        }
        // $addressData = [];
        // foreach($data['address'] as $key => $value){
        //     $addressData[] = array(
        //         'id' => $key+1,
        //         'address' => $value
        //     );
        // }
        // $address = json_encode($addressData);
        // echo "<pre>"; print_r($address); exit;
        $create->name = $data['name'];
        $create->mobile = $data['mobile'];
        $create->vat = $data['vat'];
        // $create->address = isset($data['address']) ? $address : null;
        return $create;
    }

    public function checkExcelColumns($request)
    {
        $filePath = $request->file('file')->getRealPath();
        $reader = Excel::toArray([], $filePath);
        // echo "<pre>"; print_r($reader); exit;
        // Extract column names from the first row
        if (empty($reader)) {
            return ['status' => 'empty', 'message' => 'Excel file is empty.'];
            // return response()->json(['status' => 422, 'message' => 'Excel file is empty.']);

        }

        $sheetData = $reader[0];

        // Check if there's only one row (i.e., header row)
        if (count($sheetData) <= 1) {
            return ['status' => 422, 'message' => 'No data rows found in Excel file.'];
        }

        // Remove the header row
        $headerRow = array_shift($sheetData);

        // Check if the rest of the rows are empty
        $emptyRows = [];
        foreach ($sheetData as $rowIndex => $row) {
            // Check if all cells in the row are empty
            if (!array_filter($row)) {
                $emptyRows[] = $rowIndex + 2; // Adding 2 because Excel rows are 1-indexed and we shifted the array
            }
        }
        // echo "<pre>"; print_r($emptyRows); exit;

        if (!empty($emptyRows)) {
            return ['status' => 4222, 'message' => 'Rows ' . implode(', ', $emptyRows) . ' are empty.'];
        }

        $firstRow = reset($reader[0]);
        $columnNames = array_keys($firstRow);

        $validColumns = [
            'CustomerName',
            'Mobile',
            'Vat',
            'Address',
            'Status'
        ];

        // Check if column names are correct
        $incorrectColumns = array_diff($firstRow, $validColumns);
        // echo "<pre>"; print_r($incorrectColumns); exit;
        if (!empty($incorrectColumns)) {
            $message = 'Incorrect columns: ' . implode(', ', $incorrectColumns);
            // echo "<pre>"; print_r($message); exit;
            return ['status' => 'incorrect_columns', 'errors' => $incorrectColumns];

        }
    }
}
