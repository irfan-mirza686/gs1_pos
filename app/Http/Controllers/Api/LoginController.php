<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Http;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\RequestException;
use Session;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        try {
            // echo "<pre>"; print_r($request->all()); exit;
            $response = Http::post('https://gs1ksa.org:3093/api/users/memberLogin', [
                'email' => $request->Gtrack_Email,
                'password' => $request->Gtrack_password,
                'activity' => $request->Gtrack_activity
            ]);

            $body = $response->getBody();
            $data = json_decode($body, true);
            // echo "<pre>"; print_r($data); exit;
            Session::put('user_info', $data);
            if ($data) {
                return response()->json(['data' => $data, 'message' => 'Login Successfully'],200);
            }


        } catch (RequestException $e) {
            // Handle Guzzle HTTP request exceptions
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
            return response()->json(['error' => $errorMessage], 404);
        } catch (\Throwable $th) {

            \Log::error('An unexpected error occurred: ' . $th->getMessage());

            // Return an error response
            return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
        }
    }
}
