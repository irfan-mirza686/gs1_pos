<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;

class PosSaleReportController extends Controller
{
    public function index()
    {
        $pageTitle = "Sales Report";
        return view('user.reports.sales.index',compact('pageTitle'));
    }
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

        // echo"<pre>"; print_r($data); exit();
        $startDate = date('Y-m-d',strtotime($data['startDate']));
        $endDate = date('Y-m-d',strtotime($data['endDate']));
       // Customer Wise Else Start

                $customerPayment = Sale::with(['customer','users'])->where('status','confirmed')->whereBetween('date',[$startDate,$endDate])->get()->toArray();


         // Customer Wise Else Ends
        if ($customerPayment) {
                $html['thsource'] =  '<th>#</th>';
                $html['thsource'] .= '<th>Date</th>';
                $html['thsource'] .= '<th>Invoice#</th>';
                $html['thsource'] .= '<th>Customer Name</th>';
                $html['thsource'] .= '<th>Sale By</th>';
                $html['thsource'] .= '<th>Total Amount</th>';

                $html['tdsource'] = null;
                $totalAmount = 0;
                $returnTotalAmount = 0;

                foreach ($customerPayment as $key => $value) {
                    // echo"<pre>"; print_r($value['amount']); exit();
                    $totalAmount = $value['total'] + $totalAmount;


                    $html[$key]['tdsource'] = '<td>'.($key+1).'</td>';
                    $html[$key]['tdsource'] .= '<td>'.date('d M Y',strtotime($value['date'])).'</td>';
                    $html[$key]['tdsource'] .= '<td><a target="_blank" href="javascripti:void(0);">'.$value['order_no'].'</a></td>';
                    $html[$key]['tdsource'] .= '<td>'.$value['customer']['name'].'</td>';
                    $html[$key]['tdsource'] .= '<td>'.$value['users']['name'].'</td>';
                    $html[$key]['tdsource'] .= '<td style="text-align: right;">'.number_format($value['total'],2).'</td>';


                }
                $returnTotalAmount = $totalAmount;
                $html['tfootsource'] = '<tr style="background: gray; font-weight: bold; color:white;"><td colspan="5">Total</td><td style="text-align: right; font-weight: bold; color:white;">'.number_format($returnTotalAmount,2).'</td></tr>';

                return response(@$html);
                // return response()->json(@$html);
            }else{
                return "false";
            }
        }
    }
}
