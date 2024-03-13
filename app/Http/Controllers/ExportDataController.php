<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Maatwebsite\Excel\Facades\Excel;

use App\Exports\ExportData;

use App\Models\ForkliftRequest;

class ExportDataController extends Controller
{
    public function export($from,$to){
        date_default_timezone_set('Asia/Manila');

        $forkliftRequestDetails = ForkliftRequest::with([
            'forklift_request_approver_info', 
            'employee_info', 
            'employee_info.rapidx_user_info',
            'forklift_request_approver_info.requestor_approver_info', 
            'forklift_request_approver_info.department_approver_info', 
            'forklift_request_approver_info.traffic_sr_supervisor_approver_info', 
            'forklift_request_approver_info.forklift_operator_approver_info'
        ])
        ->where('logdel', 0)
        ->whereDate('created_at','>=',$from)
        ->whereDate('created_at','<=',$to)
        // ->orWhereBetween('created_at', ['like', '%' . $from, $to . '%'])
        ->get();

        // return count($forkliftRequestDetails);

        if(count($forkliftRequestDetails) > 0){
            return Excel::download(new ExportData($forkliftRequestDetails), 'Forklift Request Report.xlsx');
        }else{
            return redirect()->back()->with('message', 'There are no data for the chosen date.');
        }
    }
}
