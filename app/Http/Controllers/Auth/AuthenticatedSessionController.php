<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Services\UserService;
use Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\RequestException;
use Session;
use Illuminate\Support\Facades\Crypt;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /********************************************************************/
    public function create(Request $request)
    {




        // if ($request->isMethod('post')) {
        // echo "<pre>";
        // print_r($request->all());
        // exit;
        if ($request->Gtrack_Email) {
            $response = Http::post('https://gs1ksa.org:3093/api/users/memberLogin', [
                'email' => $request->Gtrack_Email,
                'password' => $request->Gtrack_password,
                'activity' => $request->Gtrack_activity
            ]);

            $body = $response->getBody();
            $data = json_decode($body, true);
            // echo "<pre>"; print_r($data); exit;
            Session::put('user_info', $data);
            if (isset($data['error']) && empty($data['error'])) {
                return redirect()->route('login');
            }

            if ($data) {
                return redirect()->route('dashboard');
            }
        }


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
    // public function checkEmail(Request $request)
    // {
    //     if ($request->ajax()) {


    //         // echo "<pre>"; print_r($request->all()); exit;
    //         try {

    //             $response = Http::get('https://gs1ksa.org:3093/api/users/getCrInfoByEmail', [
    //                 'email' => $request->email,
    //             ]);

    //             $body = $response->getBody();
    //             $data = json_decode($body, true);
    //             // echo "<pre>"; print_r($data); exit;
    //             if (@$data['error']) {
    //                 return response()->json(['status' => 404, 'error' => @$data['error']]);
    //             }
    //             if ($data) {
    //                 Session::put(['activtyData' => $data, 'email' => $request->email]);
    //                 return response()->json(['status' => 200, 'data' => $data]);
    //             }else{
    //                 return response()->json(['status' => 404, 'error' => 'No Record Found']);
    //             }


    //         } catch (RequestException $e) {
    //             // Handle Guzzle HTTP request exceptions
    //             if ($e->hasResponse()) {
    //                 // Extract the error message from the response body
    //                 $responseBody = $e->getResponse()->getBody()->getContents();
    //                 $responseData = json_decode($responseBody, true);
    //                 // echo "<pre>"; print_r($responseData); exit;
    //                 $errorMessage = isset($responseData['error']) ? $responseData['error'] : 'An unexpected error occurred.';
    //             } else {
    //                 // If the response is not available, use a default error message
    //                 $errorMessage = 'An unexpected error occurred.';
    //             }

    //             // You can log the error message
    //             \Log::error('Guzzle HTTP request failed: ' . $errorMessage);

    //             // Return an error response with the extracted error message
    //             return response()->json(['status' => 404, 'error' => $errorMessage], 404);
    //         } catch (\Throwable $th) {

    //             \Log::error('An unexpected error occurred: ' . $th->getMessage());

    //             // Return an error response
    //             return response()->json(['error' => 'An unexpected error occurred. Please try again later.'], 500);
    //         }
    //     }
    // }

    public function checkEmail(Request $request)
    {
        if ($request->ajax()) {


            // echo "<pre>"; print_r($request->all()); exit;
            try {
                $user = checkMemberByEmail($request->email);
                //    echo "<pre>"; print_r($user); exit;
                if ($user['userType'] == 'parent') {
                    $response = Http::get('https://gs1ksa.org:3093/api/users/getCrInfoByEmail', [
                        'email' => $request->email,
                    ]);

                    $body = $response->getBody();
                    $data = json_decode($body, true);
                    // echo "<pre>"; print_r($data); exit;
                    if (@$data['error']) {
                        return response()->json(['status' => 404, 'error' => @$data['error']]);
                    } else if ($data) {
                        Session::put(['activtyData' => $data, 'email' => $request->email]);
                        return response()->json(['status' => 200, 'data' => $data]);
                    } else {
                        return response()->json(['status' => 404, 'error' => 'No Record Found']);
                    }
                } else if ($user['userType'] == 'child') {
                    $data = User::where('email', $request->email)->first();
                    // echo "<pre>"; print_r($data); exit;
                    $activityData[] = array(
                        'cr_activity' => $data->cr_activity,
                        'cr_number' => $data->cr_number
                    );
                    // echo "<pre>"; print_r($activityData); exit;
                    Session::put(['activtyData' => $activityData, 'email' => $request->email]);
                    return response()->json(['status' => 200, 'data' => $data]);
                } else {
                    return response()->json(['status' => 404, 'error' => 'No Record Found']);
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
                return response()->json(['error' => $th->getMessage()], 500);
            }
        }
    }

    /************************************************************************/
    public function memberActivity(Request $request)
    {
        $pageTitle = "Member Activity";
        $activtyData = session('activtyData');
        $email = session('email');
        return view('user.auth.activity', compact('activtyData', 'email'));
    }
    /************************************************************************/
    public function loginMember(Request $request)
    {
        if ($request->ajax()) {

            try {
                $checkUser = checkMemberByEmail($request->email);
                if ($checkUser['userType'] == 'parent') {
                    $user = User::query()
                        ->where('email', $request->email)
                        ->orWhere('cr_activity', $request->activity)
                        ->first();
                    $response = Http::post('https://gs1ksa.org:3093/api/users/memberLogin', [
                        'email' => $request->email,
                        'password' => $request->password,
                        'activity' => $request->activity
                    ]);

                    $body = $response->getBody();
                    $data = json_decode($body, true);

                    // Session::put('user_info', $data);
                    if ($user === null) {

                        $create = $this->userService->migrateGs1Member($data);
                        $create->save();
                    }
                    $gs1UserData = $data['memberData'];
                    User::where('id', $user->id)->update(['v2_token' => $data['token'], 'gcp_expiry' => date('Y-m-d h:i:s', strtotime($gs1UserData['gcp_expiry']))]);


                    if (Auth::attempt(['email' => $request->email, 'password' => $user->code, 'cr_activity' => $request->activity])) {

                        $userData = session('user_info');
                        return response()->json(['status' => 200, 'data' => $userData, 'message' => 'Login Successfully']);
                    }
                }else{
                    if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'cr_activity' => $request->activity])) {

                        $userData = session('user_info');
                        return response()->json(['status' => 200, 'data' => $userData, 'message' => 'Login Successfully']);
                    }
                }



                // echo "<pre>";
                // print_r("not loggedin");
                // exit;
                // Session::put('user_info', $data);
                // if ($data) {
                //     return response()->json(['status' => 200, 'data' => $data, 'message' => 'Login Successfully']);
                // }


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
