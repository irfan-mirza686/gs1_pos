<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\RequestException;
use Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        // if ($request->isMethod('post')) {
        //     echo "<pre>"; print_r($request->all()); exit;
        // }
        
        return view('user.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {

        // dd($request->all());
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    /*****************************************************************/
    public function checkEmail(Request $request)
    {
        if ($request->ajax()) {


            // echo "<pre>"; print_r($request->all()); exit;
            try {

                $response = Http::get('https://gs1ksa.org:3093/api/users/getCrInfoByEmail', [
                    'email' => $request->email,
                ]);

                $body = $response->getBody();
                $data = json_decode($body, true);
                // echo "<pre>"; print_r($data); exit;
                if (@$data['error']) {
                    return response()->json(['status' => 404, 'error' => @$data['error']]);
                }
                if ($data) {
                    Session::put(['activtyData'=>$data,'email'=>$request->email]);
                    return response()->json(['status' => 200, 'data' => $data]);
                }


            } catch (RequestException $e) {
                // Handle Guzzle HTTP request exceptions
                if ($e->hasResponse()) {
                    // Extract the error message from the response body
                    $responseBody = $e->getResponse()->getBody()->getContents();
                    $responseData = json_decode($responseBody, true);
                    // echo "<pre>"; print_r($responseData); exit;
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
    /************************************************************************/
    public function memberActivity(Request $request)
    {
        $pageTitle = "Member Activity";
        $activtyData = session('activtyData');
        $email = session('email');
        return view('user.auth.activity',compact('activtyData','email'));
    }
    /************************************************************************/
    public function loginMember(Request $request)
    {
        if ($request->ajax()) {

            try {
                // echo "<pre>"; print_r($request->all()); exit;
                $response = Http::post('https://gs1ksa.org:3093/api/users/memberLogin', [
                    'email' => $request->email,
                    'password' => $request->password,
                    'activity' => $request->activity
                ]);

                $body = $response->getBody();
                $data = json_decode($body, true);
                // echo "<pre>"; print_r($data); exit;
                Session::put('user_info', $data);
                if ($data) {
                    return response()->json(['status' => 200, 'data' => $data, 'message' => 'Login Successfully']);
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
                return response()->json(['status' => 404, 'error' => $errorMessage], 404);
            } catch (\Throwable $th) {

                \Log::error('An unexpected error occurred: ' . $th->getMessage());

                // Return an error response
                return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
            }
        }
    }

    public function destroy(Request $request)
    {
        // Auth::guard('web')->logout();
        // Session::flush();
        Auth::logout();
        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        return redirect(route('login'));
    }
}
