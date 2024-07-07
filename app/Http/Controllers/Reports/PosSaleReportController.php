<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Sale;
use DB;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\RequestException;
use Session;

class PosSaleReportController extends Controller
{
    public function index()
    {
        $pageTitle = "Sales Report";
        $user_info = session('user_info');
        $customers = Customer::get();
        return view('user.reports.sales.index', compact('pageTitle', 'user_info', 'customers'));
    }
    /********************************************************************/
    public function getGPCData(Request $request)
    {
        if ($request->ajax()) {
            try {

                Session::put('page', 'addProduct');
                $user_info = session('user_info');
                $search = $request->input('q');
                $gpc = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $user_info['token'],
                ])->get('https://gs1ksa.org:4044/api/findSimilarRecords', [
                            'text' => trim($search),
                            'tableName' => 'gpc_bricks',
                        ]);

                $gpcBody = $gpc->getBody();
                $gpcData = json_decode($gpcBody, true);
                // echo "<pre>";
                // print_r($gpcData);
                // exit;
                if ($gpcData) {
                    return response()->json(['status' => 200, 'results' => $gpcData]);
                }
            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    // Extract the error message from the response body
                    $responseBody = $e->getResponse()->getBody()->getContents();
                    $responseData = json_decode($responseBody, true);
                    // echo "<pre>"; print_r($responseData['error']); exit;
                    $errorMessage = isset($responseData['error']) ? $responseData['error'] : 'An unexpected error occurred.';
                } else {
                    // If the response is not available, use a default error message
                    $errorMessage = 'An unexpected error occurred.';
                }

                // You can log the error message
                \Log::error('Guzzle HTTP request failed: ' . $errorMessage);

                // Return an error response with the extracted error message
                return response()->json(['status' => 404, 'error' => $errorMessage], 404);
            } catch (\Throwable $th) {
                \Log::error('An unexpected error occurred: ' . $th->getMessage());

                // Return an error response
                return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
            }

        }
    }
    /********************************************************************/
    public function getSaleData(Request $request)
    {
        if ($request->ajax()) {
            // echo "<pre>"; print_r(Sale::get()->toArray()); exit;
            $year = $request->input('year', date('Y'));
            // $year = date('Y-m-d',strtotime($year));
            $type = $request->input('type');

            $gpcz = $request->input('gpc', []);

            $data = DB::table('sales')
            ->select(DB::raw('MONTH(date) as month'), DB::raw('COUNT(*) as total_sales'))
            ->where('customer_id', $type)
            ->whereYear('date', $year)
            ->where(function($query) use ($gpcz) {
                foreach ($gpcz as $gpc) {
                    $query->orWhereJsonContains('items', [['gpc' => $gpc]]);
                }
            })
            ->groupBy(DB::raw('MONTH(date)'))
            ->get();
            // echo "<pre>";
            // print_r($data);
            // exit;
            return response()->json($data);

        }
    }
    /********************************************************************/
    public function List(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();

            // echo"<pre>"; print_r($data); exit();
            $startDate = date('Y-m-d', strtotime($data['startDate']));
            $endDate = date('Y-m-d', strtotime($data['endDate']));
            // Customer Wise Else Start

            $customerPayment = Sale::with(['customer'])->where('status', 'confirmed')->whereBetween('date', [$startDate, $endDate])->get()->toArray();


            // Customer Wise Else Ends
            if ($customerPayment) {
                $html['thsource'] = '<th>#</th>';
                $html['thsource'] .= '<th>Date</th>';
                $html['thsource'] .= '<th>Invoice#</th>';
                $html['thsource'] .= '<th>Customer Name</th>';
                $html['thsource'] .= '<th>Total Amount</th>';

                $html['tdsource'] = null;
                $totalAmount = 0;
                $returnTotalAmount = 0;

                foreach ($customerPayment as $key => $value) {
                    // echo"<pre>"; print_r($value['amount']); exit();
                    $totalAmount = $value['total'] + $totalAmount;


                    $html[$key]['tdsource'] = '<td>' . ($key + 1) . '</td>';
                    $html[$key]['tdsource'] .= '<td>' . date('d M Y', strtotime($value['date'])) . '</td>';
                    $html[$key]['tdsource'] .= '<td><a target="_blank" href="javascripti:void(0);">' . $value['order_no'] . '</a></td>';
                    $html[$key]['tdsource'] .= '<td>' . $value['customer']['name'] . '</td>';
                    $html[$key]['tdsource'] .= '<td style="text-align: right;">' . number_format($value['total'], 2) . '</td>';


                }
                $returnTotalAmount = $totalAmount;
                $html['tfootsource'] = '<tr style="background: gray; font-weight: bold; color:white;"><td colspan="5">Total</td><td style="text-align: right; font-weight: bold; color:white;">' . number_format($returnTotalAmount, 2) . '</td></tr>';

                return response(@$html);
                // return response()->json(@$html);
            } else {
                return "false";
            }
        }
    }
}
