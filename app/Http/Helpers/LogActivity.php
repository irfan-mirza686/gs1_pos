<?php


namespace App\Http\Helpers;

use Request;
use Auth;
use Session;
use App\Models\User;
use App\Models\LogActivity as LogActivityModel;

class LogActivity
{


    public static function addToLog($subject,$url)
    {
        // echo "<pre>"; print_r($url);exit;
        date_default_timezone_set((config('app.timezone')));
        $currentDate = date('Y-m-d h:i:s');
        $log = [];
        $log['ip'] = Request::ip();
        $log['subject'] = $subject;
        $log['agent'] = Request::header('user-agent');
        $log['url'] = $url;
        $log['user_id'] = isset(Auth::user()->id)?Auth::user()->id:0;
        $log['username'] = auth()->check() ? auth()->user()->name : '';
        $log['date'] = $currentDate;
        LogActivityModel::create($log);
    }

    public static function logActivityLists()
    {
        return LogActivityModel::with('user')->latest()->paginate();
    }

}
