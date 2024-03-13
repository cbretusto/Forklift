<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Mail;
use Auth; // or use Illuminate\Support\Facades\Auth;
use DataTables;

/**
 * Import Models here
 */
use App\Models\ForkliftRequest;
use App\Models\ForkliftRequestApprover;
use App\Models\User;

class ForkliftRequestController extends Controller
{
    public function viewRequest(){
        session_start();
        $rapidx_employee_no = $_SESSION['rapidx_employee_number'];
        $requestDetails = ForkliftRequest::with([
            // 'forklift_request_approver_info', 
            // 'employee_info', 
            'employee_info.rapidx_user_info',
            'forklift_request_approver_info.requestor_approver_info', 
            'forklift_request_approver_info.department_approver_info', 
            'forklift_request_approver_info.traffic_sr_supervisor_approver_info', 
            'forklift_request_approver_info.forklift_operator_approver_info'
        ])
        ->where('logdel', 0)
        ->whereIn('approval_status', [0,1,2,3])
        ->get();
        
        return DataTables::of($requestDetails)
        ->addColumn('action', function($requestDetail) use($rapidx_employee_no){
            $result =   '<center>';
            // $result .=   $requestDetail->forklift_request_approver_info->traffic_sr_supervisor_approver;

            if($requestDetail->employee_no == $rapidx_employee_no && $requestDetail->approval_status != 4){
                $result .= '<button type="button" class="btn btn-dark btn-xs text-center actionEditForkliftRequest mr-1 w-75" forklift_request-id="' . $requestDetail->id . '" data-bs-toggle="modal" data-bs-target="#modalForkliftRequest" title="Edit Forklift Request"><i class="fa fa-xl fa-edit1"></i> Edit </button>';
                $result .= "<br>";
            }

            if($requestDetail->forklift_request_approver_info->traffic_sr_supervisor_approver == $rapidx_employee_no){
                $result .= '<button type="button" class="btn btn-secondary btn-xs text-center actionCancelForkliftRequest mr-1 w-75" forklift_request-id="' . $requestDetail->id . '" status="4" data-bs-toggle="modal" data-bs-target="#modalApproveDisapprove" title="Cancel Forklift Request"><i class="fa fa-xl fa-cancel1"></i> Cancel</button>';
                $result .= "<br>";
            }

            $result .= '<button type="button" class="btn btn-info btn-xs text-center actionViewForkliftRequest mr-1 w-75" forklift_request-id="' . $requestDetail->id . '" data-bs-toggle="modal" data-bs-target="#modalForkliftRequest" title="View Forklift Request"><i class="fa fa-xl fa-eye1"></i> View</button>';
            $result .= "<br>";

            if( $requestDetail->approval_status == 9 &&
                $requestDetail->request_status == 0 && 
                $requestDetail->forklift_request_approver_info->traffic_sr_supervisor_approver == $rapidx_employee_no ||
                $requestDetail->forklift_request_approver_info->forklift_approver == $rapidx_employee_no
            ){
                $result .= '<button type="button" class="btn btn-warning btn-xs text-center actionClosedForkliftRequest mr-1" forklift_request-id="' . $requestDetail->id . '" request_status="1" data-bs-toggle="modal" data-bs-target="#modalApproveDisapprove" title="Request Status"><i class="fa fa-xl fa-handshake"></i></button>';
                $result .= "<br>";
            }

            switch ($requestDetail->approval_status)
            {
                case 0:{
                    if($requestDetail->employee_no == $rapidx_employee_no){
                        $result .= '<button type="button" class="btn btn-success btn-xs text-center actionApproveForkliftRequest mr-1" forklift_request-id="' . $requestDetail->id . '" status="1" data-bs-toggle="modal" data-bs-target="#modalApproveDisapprove" title="Approve"><i class="fa fa-xl fa-thumbs-up"></i></button>';
                        $result .= '<button type="button" class="btn btn-danger btn-xs text-center actionDisapproveForkliftRequest mr-1" forklift_request-id="' . $requestDetail->id . '" status="5" data-bs-toggle="modal" data-bs-target="#modalApproveDisapprove" title="Disapprove"><i class="fa fa-xl fa-thumbs-down"></i></button>';
                    }
                    break;
                }

                case 1:{
                    if($requestDetail->forklift_request_approver_info->department_approver == $rapidx_employee_no){
                        $result .= '<button type="button" class="btn btn-success btn-xs text-center actionApproveForkliftRequest mr-1" forklift_request-id="' . $requestDetail->id . '" status="2" data-bs-toggle="modal" data-bs-target="#modalApproveDisapprove" title="Approve"><i class="fa fa-xl fa-thumbs-up"></i></button>';
                        $result .= '<button type="button" class="btn btn-danger btn-xs text-center actionDisapproveForkliftRequest mr-1" forklift_request-id="' . $requestDetail->id . '" status="6" data-bs-toggle="modal" data-bs-target="#modalApproveDisapprove" title="Disapprove"><i class="fa fa-xl fa-thumbs-down"></i></button>';
                    }
                    break;
                }

                case 2:{
                    if($requestDetail->forklift_request_approver_info->traffic_sr_supervisor_approver == $rapidx_employee_no){
                        $result .= '<button type="button" class="btn btn-success btn-xs text-center actionApproveForkliftRequest mr-1" forklift_request-id="' . $requestDetail->id . '" status="9" data-bs-toggle="modal" data-bs-target="#modalApproveDisapprove" title="Approve"><i class="fa fa-xl fa-thumbs-up"></i></button>';
                        $result .= '<button type="button" class="btn btn-danger btn-xs text-center actionDisapproveForkliftRequest mr-1" forklift_request-id="' . $requestDetail->id . '" status="7" data-bs-toggle="modal" data-bs-target="#modalApproveDisapprove" title="Disapprove"><i class="fa fa-xl fa-thumbs-down"></i></button>';
                    }
                    break;
                }

                case 3:{
                    if($requestDetail->forklift_request_approver_info->forklift_operator_approver == $rapidx_employee_no){
                        $result .= '<button type="button" class="btn btn-success btn-xs text-center actionApproveForkliftRequest mr-1" forklift_request-id="' . $requestDetail->id . '" status="9" data-bs-toggle="modal" data-bs-target="#modalApproveDisapprove" title="Approve"><i class="fa fa-xl fa-thumbs-up"></i></button>';
                        $result .= '<button type="button" class="btn btn-danger btn-xs text-center actionDisapproveForkliftRequest mr-1" forklift_request-id="' . $requestDetail->id . '" status="8" data-bs-toggle="modal" data-bs-target="#modalApproveDisapprove" title="Disapprove"><i class="fa fa-xl fa-thumbs-down"></i></button>';
                    }
                    break;
                }
            }

            $result .= '</center>';
            return $result;
        })

        ->addColumn('request_status', function($requestDetail){
            $result =   '<center>';
            if($requestDetail->request_status == 0){
                $result .= '<span class="badge badge-pill badge-warning">Open</span>';
            }
            else{
                $result .= '<span class="badge badge-pill badge-success">Served</span>';
            }
            $result .=  '</center>';
            return $result;
        })

        ->addColumn('approver_status', function($requestDetail){
            $get_classification = User::where('employee_no', $requestDetail->forklift_request_approver_info->department_approver)->get();
            $result =   '<center>';
            switch ($requestDetail->approval_status)
            {
                case 0:{
                    $result .= '<span class="badge badge-pill badge-warning">Approval of <br> Requestor</span><br>';
                    break;
                }

                case 1:{
                    $result .= '<span class="badge badge-pill badge-warning">Approval of <br>'.$get_classification[0]->classification.'</span><br>';
                    break;
                }

                case 2:{
                    if($requestDetail->forklift_request_approver_info->traffic_sr_supervisor_approver != ''){
                        $result .= '<span class="badge badge-pill badge-warning">Approval of <br>Traffic Sr. Supervisor</span><br>';
                    }else{
                        $result .= '<span class="badge badge-pill badge-secondary">Error! <br>Traffic Sr. Supervisor not found!</span><br>';
                    }
                    break;
                }

                case 3:{
                    if($requestDetail->forklift_request_approver_info->forklift_operator_approver_info != ''){
                        $result .= '<span class="badge badge-pill badge-warning">Approval of <br>Forklift Operator</span><br>';
                    }else{
                        $result .= '<span class="badge badge-pill badge-secondary">Error! <br>Forklift Operator not found!</span><br>';
                    }
                    break;
                }
            }
            $result .= '</center>';
            return $result;
        })

        ->addColumn('name', function($requestDetail){
            $result =  $requestDetail->employee_info->rapidx_user_info->name;
            return $result;
        })

        ->addColumn('approvers', function($requestDetail){
            $result =   '<center>';
            switch ($requestDetail->approval_status)
            {
                case 0:{
                    $result .= '<span class="badge badge-pill badge-warning">'.$requestDetail->employee_info->rapidx_user_info->name.'</span><br>';

                    $result .= '<span class="badge badge-pill badge-secondary">'.$requestDetail->forklift_request_approver_info->department_approver_info->name.'</span><br>';

                    if($requestDetail->forklift_request_approver_info->traffic_sr_supervisor_approver != ''){
                        $result .= '<span class="badge badge-pill badge-secondary">'.$requestDetail->forklift_request_approver_info->traffic_sr_supervisor_approver_info->name.'</span><br>';
                    }else{
                        $result .= '<span class="badge badge-pill badge-secondary">'.$requestDetail->forklift_request_approver_info->forklift_operator_approver_info->name.'</span><br>';
                    }
                    break;
                }

                case 1:{
                    $result .= '<span class="badge badge-pill badge-success">'.$requestDetail->employee_info->rapidx_user_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$requestDetail->forklift_request_approver_info->requestor_date_time_approved_disapproved.'<br>';

                    $result .= '<span class="badge badge-pill badge-warning">'.$requestDetail->forklift_request_approver_info->department_approver_info->name.'</span><br>';

                    if($requestDetail->forklift_request_approver_info->traffic_sr_supervisor_approver != ''){
                        $result .= '<span class="badge badge-pill badge-secondary">'.$requestDetail->forklift_request_approver_info->traffic_sr_supervisor_approver_info->name.'</span><br>';
                    }else{
                        $result .= '<span class="badge badge-pill badge-secondary">'.$requestDetail->forklift_request_approver_info->forklift_operator_approver_info->name.'</span><br>';
                    }
                    break;
                }

                case 2:{
                    $result .= '<span class="badge badge-pill badge-success">'.$requestDetail->employee_info->rapidx_user_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$requestDetail->forklift_request_approver_info->requestor_date_time_approved_disapproved.'<br>';

                    $result .= '<span class="badge badge-pill badge-success">'.$requestDetail->forklift_request_approver_info->department_approver_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$requestDetail->forklift_request_approver_info->department_date_time_approved_disapproved.'<br>';

                    if($requestDetail->forklift_request_approver_info->traffic_sr_supervisor_approver != ''){
                        $result .= '<span class="badge badge-pill badge-warning">'.$requestDetail->forklift_request_approver_info->traffic_sr_supervisor_approver_info->name.'</span><br>';
                    }   
                    break;
                }

                case 3:{
                    $result .= '<span class="badge badge-pill badge-success">'.$requestDetail->employee_info->rapidx_user_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$requestDetail->forklift_request_approver_info->requestor_date_time_approved_disapproved.'<br>';

                    $result .= '<span class="badge badge-pill badge-success">'.$requestDetail->forklift_request_approver_info->department_approver_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$requestDetail->forklift_request_approver_info->department_date_time_approved_disapproved.'<br>';

                    if($requestDetail->forklift_request_approver_info->forklift_operator_approver_info != ''){
                        $result .= '<span class="badge badge-pill badge-warning">'.$requestDetail->forklift_request_approver_info->forklift_operator_approver_info->name.'</span><br>';
                    }
                    break;
                }
            }
            $result .= '</center>';
            return $result;
        })

        ->rawColumns(['action', 'request_status', 'approver_status', 'name', 'approvers'])
        ->make(true);
    }

    public function viewApprovedRequest(){
        session_start();
        $rapidx_employee_no = $_SESSION['rapidx_employee_number'];
        $approvedRequestDetails = ForkliftRequestApprover::with(['request_info'])
            ->where('requestor', $rapidx_employee_no)
            ->orWhere('department_approver', $rapidx_employee_no)
            ->orWhere('traffic_sr_supervisor_approver', $rapidx_employee_no)
            ->orWhere('forklift_operator_approver', $rapidx_employee_no)
            ->get();

        $collect_approved_request_details = collect($approvedRequestDetails)
            ->where('logdel', 0)
            ->where('request_info.logdel', 0)
            ->whereIn('request_info.approval_status', 9)
            ->whereIn('request_info.request_status', 0);

        return DataTables::of($collect_approved_request_details)
        ->addColumn('action', function($collect_approved_request_detail) use($rapidx_employee_no){
            $result =   '<center>';

            $result .= '<button type="button" class="btn btn-info btn-xs text-center actionViewForkliftRequest mr-1 w-75" forklift_request-id="' . $collect_approved_request_detail->request_info->id . '" data-bs-toggle="modal" data-bs-target="#modalForkliftRequest" title="View Forklift Request"><i class="fa fa-xl fa-eye1"></i> View</button>';
            $result .= "<br>";

            if( $collect_approved_request_detail->request_info->approval_status == 9 &&
                $collect_approved_request_detail->request_info->request_status == 0 && 
                $collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver == $rapidx_employee_no ||
                $collect_approved_request_detail->request_info->forklift_request_approver_info->forklift_approver == $rapidx_employee_no
            ){
                $result .= '<button type="button" class="btn btn-warning btn-xs text-center actionClosedForkliftRequest mr-1" forklift_request-id="' . $collect_approved_request_detail->request_info->id . '" request_status="1" data-bs-toggle="modal" data-bs-target="#modalApproveDisapprove" title="Serve"><i class="fa fa-xl fa-handshake"></i></button>';
                $result .= "<br>";
            }

            $result .= '</center>';
            return $result;
        })

        ->addColumn('request_status', function($collect_approved_request_detail){
            $result =   '<center>';
            if($collect_approved_request_detail->request_info->request_status == 0){
                $result .= '<span class="badge badge-pill badge-warning">Open</span>';
            }
            else{
                $result .= '<span class="badge badge-pill badge-success">Served</span>';
            }
            $result .=  '</center>';
            return $result;
        })

        ->addColumn('approver_status', function($collect_approved_request_detail){
            $get_classification = User::where('employee_no', $collect_approved_request_detail->request_info->forklift_request_approver_info->department_approver)->get();
            $result =   '<center>';
            if($collect_approved_request_detail->request_info->approval_status == 9){
                $result .= '<span class="badge badge-pill badge-success">Approved</span>';
            }

            $result .= '</center>';
            return $result;
        })

        ->addColumn('name', function($collect_approved_request_detail){
            $result =  $collect_approved_request_detail->request_info->employee_info->rapidx_user_info->name;
            return $result;
        })

        ->addColumn('approvers', function($collect_approved_request_detail){
            $result =   '<center>';
            if($collect_approved_request_detail->request_info->approval_status == 9){     
                $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_approver_info->name.'</span><br>';
                $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->requestor_date_time_approved_disapproved.'<br>';
    
                $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_approver_info->name.'</span><br>';
                $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_date_time_approved_disapproved.'<br>';
    
                if($collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver != ''){
                    $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_date_time_approved_disapproved.'<br>';
                }else{
                    $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->forklift_operator_approver_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->forklift_operator_date_time_approved_disapproved.'<br>';
                }
            }

            $result .= '</center>';
            return $result;
        })

        ->rawColumns(['action', 'request_status', 'approver_status', 'name', 'approvers'])
        ->make(true);
    }

    public function viewDisapprovedRequest(){
        session_start();
        $rapidx_employee_no = $_SESSION['rapidx_employee_number'];
        $approvedRequestDetails = ForkliftRequestApprover::with(['request_info'])
            ->where('requestor', $rapidx_employee_no)
            ->orWhere('department_approver', $rapidx_employee_no)
            ->orWhere('traffic_sr_supervisor_approver', $rapidx_employee_no)
            ->orWhere('forklift_operator_approver', $rapidx_employee_no)
            ->get();

        $collect_approved_request_details = collect($approvedRequestDetails)
            ->where('logdel', 0)
            ->where('request_info.logdel', 0)
            ->whereIn('request_info.approval_status', [5,6,7,8]);

        return DataTables::of($collect_approved_request_details)
        ->addColumn('action', function($collect_approved_request_detail) use($rapidx_employee_no){
            $result =   '<center>';

            $result .= '<button type="button" class="btn btn-info btn-xs text-center actionViewForkliftRequest mr-1 w-75" forklift_request-id="' . $collect_approved_request_detail->request_info->id . '" data-bs-toggle="modal" data-bs-target="#modalForkliftRequest" title="View Forklift Request"><i class="fa fa-xl fa-eye1"></i> View</button>';
            $result .= "<br>";

            $result .= '</center>';
            return $result;
        })

        ->addColumn('request_status', function($collect_approved_request_detail){
            $result =   '<center>';
            if($collect_approved_request_detail->request_info->request_status == 0){
                $result .= '<span class="badge badge-pill badge-warning">Open</span>';
            }
            else{
                $result .= '<span class="badge badge-pill badge-success">Served</span>';
            }
            $result .=  '</center>';
            return $result;
        })

        ->addColumn('approver_status', function($collect_approved_request_detail){
            $get_classification = User::where('employee_no', $collect_approved_request_detail->request_info->forklift_request_approver_info->department_approver)->get();
            $result =   '<center>';
            switch ($collect_approved_request_detail->request_info->approval_status)
            {
                case 5:{
                    $result .= '<span class="badge badge-pill badge-danger">Disapproved By: <br> Requestor</span>';
                    break;
                }

                case 6:{
                    $result .= '<span class="badge badge-pill badge-danger">Disapproved By: <br> '.$get_classification[0]->classification.'</span>';
                    break;
                }

                case 7:{
                    if($collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver != ''){
                        $result .= '<span class="badge badge-pill badge-danger">Disapproved By: <br> Traffic Sr. Supervisor</span>';
                    }else{
                        $result .= '<span class="badge badge-pill badge-secondary">Error! <br>Traffic Sr. Supervisor not found!</span><br>';
                    }
                    break;
                }

                case 8:{
                    if($collect_approved_request_detail->request_info->forklift_request_approver_info->forklift_operator_approver_info != ''){
                        $result .= '<span class="badge badge-pill badge-danger">Disapproved By: <br> Forklift Operator</span>';
                    }else{
                        $result .= '<span class="badge badge-pill badge-secondary">Error! <br>Forklift Operator not found!</span><br>';
                    }
                    break;
                }
            }

            $result .= '</center>';
            return $result;
        })

        ->addColumn('name', function($collect_approved_request_detail){
            $result =  $collect_approved_request_detail->request_info->employee_info->rapidx_user_info->name;
            return $result;
        })

        ->addColumn('approvers', function($collect_approved_request_detail){
            $result =   '<center>';
            switch ($collect_approved_request_detail->request_info->approval_status)
            {
                case 5:{
                    $result .= '<span class="badge badge-pill badge-danger">'.$collect_approved_request_detail->request_info->employee_info->rapidx_user_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->requestor_date_time_approved_disapproved.'<br>';
                    
                    if($collect_approved_request_detail->request_info->forklift_request_approver_info->approval_remarks != null){
                        $result .= '<strong>Remark:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->approval_remarks.'<br>';
                    }

                    $result .= '<span class="badge badge-pill badge-secondary">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_approver_info->name.'</span><br>';

                    if($collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver != ''){
                        $result .= '<span class="badge badge-pill badge-secondary">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver_info->name.'</span><br>';
                    }else{
                        $result .= '<span class="badge badge-pill badge-secondary">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->forklift_operator_approver_info->name.'</span><br>';
                    }
                    break;
                }

                case 6:{
                    $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->employee_info->rapidx_user_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->requestor_date_time_approved_disapproved.'<br>';

                    $result .= '<span class="badge badge-pill badge-danger">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_approver_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_date_time_approved_disapproved.'<br>';
                    
                    if($collect_approved_request_detail->request_info->forklift_request_approver_info->approval_remarks != null){
                        $result .= '<strong>Remark:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->approval_remarks.'<br>';
                    }

                    if($collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver_info != ''){
                        $result .= '<span class="badge badge-pill badge-secondary">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver_info->name.'</span><br>';
                    }else{
                        $result .= '<span class="badge badge-pill badge-secondary">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->forklift_operator_approver_info->name.'</span><br>';
                    }

                    break;
                }

                case 7:{
                    $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->employee_info->rapidx_user_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->requestor_date_time_approved_disapproved.'<br>';

                    $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_approver_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_date_time_approved_disapproved.'<br>';

                    if($collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver_info != ''){
                        $result .= '<span class="badge badge-pill badge-danger">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver_info->name.'</span><br>';
                        $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_date_time_approved_disapproved.'<br>';
                        
                        if($collect_approved_request_detail->request_info->forklift_request_approver_info->approval_remarks != null){
                            $result .= '<strong>Remark:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->approval_remarks.'<br>';
                        }
                    }
                    break;
                }

                case 8:{
                    $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->employee_info->rapidx_user_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->requestor_date_time_approved_disapproved.'<br>';

                    $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_approver_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_date_time_approved_disapproved.'<br>';

                    if($collect_approved_request_detail->request_info->forklift_request_approver_info->forklift_operator_approver_info != ''){
                        $result .= '<span class="badge badge-pill badge-danger">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->forklift_operator_approver_info->name.'</span><br>';
                        
                        if($collect_approved_request_detail->request_info->forklift_request_approver_info->approval_remarks != null){
                            $result .= '<strong>Remark:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->approval_remarks.'<br>';
                        }
                    }
                    break;
                }
            }

            $result .= '</center>';
            return $result;
        })

        ->rawColumns(['action', 'request_status', 'approver_status', 'name', 'approvers'])
        ->make(true);
    }

    public function viewCancelledRequest(){
        session_start();
        $rapidx_employee_no = $_SESSION['rapidx_employee_number'];
        $cancelledRequestDetails = ForkliftRequestApprover::with(['request_info'])
            ->where('requestor', $rapidx_employee_no)
            ->orWhere('department_approver', $rapidx_employee_no)
            ->orWhere('traffic_sr_supervisor_approver', $rapidx_employee_no)
            ->orWhere('forklift_operator_approver', $rapidx_employee_no)
            ->get();

        $collect_cancelled_request_details = collect($cancelledRequestDetails)
            ->where('logdel', 0)
            ->where('request_info.logdel', 0)
            ->whereIn('request_info.approval_status', 4);

        return DataTables::of($collect_cancelled_request_details)
        ->addColumn('action', function($collect_cancelled_request_detail) use($rapidx_employee_no){
            $result =   '<center>';

            $result .= '<button type="button" class="btn btn-info btn-xs text-center actionViewForkliftRequest mr-1 w-75" forklift_request-id="' . $collect_cancelled_request_detail->request_info->id . '" data-bs-toggle="modal" data-bs-target="#modalForkliftRequest" title="View Forklift Request"><i class="fa fa-xl fa-eye1"></i> View</button>';
            $result .= "<br>";

            if( $collect_cancelled_request_detail->request_info->approval_status == 9 &&
                $collect_cancelled_request_detail->request_info->request_status == 0 && 
                $collect_cancelled_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver == $rapidx_employee_no ||
                $collect_cancelled_request_detail->request_info->forklift_request_approver_info->forklift_approver == $rapidx_employee_no
            ){
                $result .= '<button type="button" class="btn btn-warning btn-xs text-center actionClosedForkliftRequest mr-1" forklift_request-id="' . $collect_cancelled_request_detail->request_info->id . '" request_status="1" data-bs-toggle="modal" data-bs-target="#modalApproveDisapprove" title="Request Status"><i class="fa fa-xl fa-handshake"></i></button>';
                $result .= "<br>";
            }

            $result .= '</center>';
            return $result;
        })

        ->addColumn('request_status', function($collect_cancelled_request_detail){
            $result =   '<center>';
            if($collect_cancelled_request_detail->request_info->request_status == 0){
                $result .= '<span class="badge badge-pill badge-warning">Open</span>';
            }
            else{
                $result .= '<span class="badge badge-pill badge-success">Served</span>';
            }
            $result .=  '</center>';
            return $result;
        })

        ->addColumn('approver_status', function($collect_cancelled_request_detail){
            $get_classification = User::where('employee_no', $collect_cancelled_request_detail->request_info->forklift_request_approver_info->department_approver)->get();
            $result =   '<center>';
            if($collect_cancelled_request_detail->request_info->approval_status == 4){
                $result .= '<span class="badge badge-pill badge-danger">Cancelled</span>';
            }

            $result .= '</center>';
            return $result;
        })

        ->addColumn('name', function($collect_cancelled_request_detail){
            $result =  $collect_cancelled_request_detail->request_info->employee_info->rapidx_user_info->name;
            return $result;
        })

        ->addColumn('approvers', function($collect_cancelled_request_detail){
            $result =   '<center>';
            if($collect_cancelled_request_detail->request_info->approval_status == 4){
                $result .= '<span class="badge badge-pill badge-danger">Cancelled</span>';
            }

            $result .= '</center>';
            return $result;
        })

        ->rawColumns(['action', 'request_status', 'approver_status', 'name', 'approvers'])
        ->make(true);
    }

    public function viewServedRequest(){
        session_start();
        $rapidx_employee_no = $_SESSION['rapidx_employee_number'];
        $approvedRequestDetails = ForkliftRequestApprover::with(['request_info'])
            ->where('requestor', $rapidx_employee_no)
            ->orWhere('department_approver', $rapidx_employee_no)
            ->orWhere('traffic_sr_supervisor_approver', $rapidx_employee_no)
            ->orWhere('forklift_operator_approver', $rapidx_employee_no)
            ->get();

        $collect_approved_request_details = collect($approvedRequestDetails)
            ->where('logdel', 0)
            ->where('request_info.logdel', 0)
            ->whereIn('request_info.request_status', 1)
            ->whereIn('request_info.approval_status', 9);

        return DataTables::of($collect_approved_request_details)
        ->addColumn('action', function($collect_approved_request_detail) use($rapidx_employee_no){
            $result =   '<center>';

            $result .= '<button type="button" class="btn btn-info btn-xs text-center actionViewForkliftRequest mr-1 w-75" forklift_request-id="' . $collect_approved_request_detail->request_info->id . '" data-bs-toggle="modal" data-bs-target="#modalForkliftRequest" title="View Forklift Request"><i class="fa fa-xl fa-eye1"></i> View</button>';
            $result .= "<br>";

            $result .= '</center>';
            return $result;
        })

        ->addColumn('request_status', function($collect_approved_request_detail){
            $result =   '<center>';
            if($collect_approved_request_detail->request_info->request_status == 0){
                $result .= '<span class="badge badge-pill badge-warning">Open</span>';
            }
            else{
                $result .= '<span class="badge badge-pill badge-success">Served</span>';
            }
            $result .=  '</center>';
            return $result;
        })

        ->addColumn('approver_status', function($collect_approved_request_detail){
            $get_classification = User::where('employee_no', $collect_approved_request_detail->request_info->forklift_request_approver_info->department_approver)->get();
            $result =   '<center>';
            if($collect_approved_request_detail->request_info->approval_status == 9){
                $result .= '<span class="badge badge-pill badge-success">Approved</span>';
            }
            $result .= '</center>';
            return $result;
        })

        ->addColumn('name', function($collect_approved_request_detail){
            $result =  $collect_approved_request_detail->request_info->employee_info->rapidx_user_info->name;
            return $result;
        })

        ->addColumn('approvers', function($collect_approved_request_detail){
            $result =   '<center>';
            if($collect_approved_request_detail->request_info->approval_status == 9){     
                $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_approver_info->name.'</span><br>';
                $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->requestor_date_time_approved_disapproved.'<br>';
    
                $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_approver_info->name.'</span><br>';
                $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->department_date_time_approved_disapproved.'<br>';
    
                if($collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver != ''){
                    $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_approver_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->traffic_sr_supervisor_date_time_approved_disapproved.'<br>';
                }else{
                    $result .= '<span class="badge badge-pill badge-success">'.$collect_approved_request_detail->request_info->forklift_request_approver_info->forklift_operator_approver_info->name.'</span><br>';
                    $result .= '<strong>Date:</strong> '.$collect_approved_request_detail->request_info->forklift_request_approver_info->forklift_operator_date_time_approved_disapproved.'<br>';
                }
            }

            $result .= '</center>';
            return $result;
        })

        ->rawColumns(['action', 'request_status', 'approver_status', 'name', 'approvers'])
        ->make(true);
    }

    public function getRequestInfo(){
        date_default_timezone_set('Asia/Manila');
        session_start();
        $rapidx_employee_no = $_SESSION['rapidx_employee_number'];
        $get_request_no = ForkliftRequest::orderBy('id', 'DESC')->where('logdel', 0)->first();
        $request_no_format = "PMIFR-".NOW()->format('ymd')."-";

        if ($get_request_no == null){
            $new_request_no = $request_no_format.'1';
        }else{
            $explode_request_no = explode("-",  $get_request_no->request_no);
            $get = $explode_request_no[2]+1;
            $new_request_no = $request_no_format.$get;
        }
        $get_info = User::with(['rapidx_user_info'])->where('employee_no', $rapidx_employee_no)->where('logdel', 0)->get();
        
        $get_traffic_sr_supervisor = User::with(['rapidx_user_info'])->where('classification', 'Traffic Sr. Supervisor')->where('status', 1)->where('logdel', 0)->orderBy('updated_at', 'DESC')->first();

        return response()->json(['get_info' => $get_info, 'get_traffic_sr_supervisor' => $get_traffic_sr_supervisor, 'new_request_no' => $new_request_no]);
    }

    public function getClerkSupervisorSectionHead(){
        $collect_approver = User::with(['rapidx_user_info'])->orderBy('employee_no', 'ASC')->whereIn('classification', ['Clerk','Supervisor','Section Head'])->where('logdel', 0)->get();
        return response()->json(['collect_approver' => $collect_approver]);
    }

    public function getForkliftOperator(){
        $get_forklift_operator_approver = User::with(['rapidx_user_info'])->orderBy('employee_no', 'ASC')->where('classification', 'Forklift Operator')->where('logdel', 0)->get();
        return response()->json(['get_forklift_operator_approver' => $get_forklift_operator_approver]);
    }

    public function addEditForkliftRequest(Request $request){
        date_default_timezone_set('Asia/Manila');

        session_start();
        // return $_SESSION['rapidx_name'];

        $get_sr_traffic_supervisor_employee_no = User::with(['rapidx_user_info'])->where('classification', 'Traffic Sr. Supervisor')->where('status', 1)->where('logdel', 0)->first();

        $data = $request->all();

        if(NOW()->format('H:i:s') <= '16:30:00'){
        // if(NOW()->format('H:i:s') <= '19:30:00'){
            $required = 'traffic_sr_supervisor_approver';
            $update_traffic_sr_supervisor = $get_sr_traffic_supervisor_employee_no->employee_no;
            $update_forklift_operator = null;
        }else{
            $required = 'forklift_operator_approver';
            $update_traffic_sr_supervisor = null;
            $update_forklift_operator = $request->forklift_operator_approver;
        }

        $validator = Validator::make($data, [
            $required                               => 'required',
            'request_no'                            => 'required',
            'employee_no'                           => 'required',
            'requestor_name'                        => 'required',
            'department'                            => 'required',
            'date_needed'                           => 'required',
            'time_start'                            => 'required',
            'pickup_from'                           => 'required',
            'deliver_to'                            => 'required',
            'package_commodity'                     => 'required',
            'volume_of_trips'                       => 'required',
            'secthead_supervisor_clerk_approver'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['validationHasError' => 1, 'error' => $validator->messages()]);
        } else {
            DB::beginTransaction();
            try {
                $check_existing_record = ForkliftRequest::where('request_no', $request->request_no)->where('logdel', 0)->get();

                if( count($check_existing_record) != 1 ){
                    $forklift_id = ForkliftRequest::insertGetId([
                        'request_no'        => $request->request_no,
                        'employee_no'       => $request->employee_no,
                        'department'        => $request->department,
                        'date_needed'       => $request->date_needed,
                        'time'              => $request->time_start,
                        'pick_up_from'      => $request->pickup_from,
                        'delivery_to'       => $request->deliver_to,
                        'package_commodity' => $request->package_commodity,
                        'volume_of_trips'   => $request->volume_of_trips,
                        'created_at'        => date('Y-m-d H:i:s'),
                    ]);    
                    
                    ForkliftRequestApprover::insert([
                        'forklift_request_id'               => $forklift_id,
                        'requestor'                         => $request->employee_no,
                        'department_approver'               => $request->secthead_supervisor_clerk_approver,
                        'traffic_sr_supervisor_approver'    => $get_sr_traffic_supervisor_employee_no->employee_no,
                        'forklift_operator_approver'        => $request->forklift_operator_approver,
                        'created_at'                        => date('Y-m-d H:i:s'),
                    ]);
                }else{
                    if($request->forklift_request_id != ''){
                        ForkliftRequest::where('id', $request->forklift_request_id)->update([
                            'date_needed'       => $request->date_needed,
                            'time'              => $request->time_start,
                            'pick_up_from'      => $request->pickup_from,
                            'delivery_to'       => $request->deliver_to,
                            'package_commodity' => $request->package_commodity,
                            'volume_of_trips'   => $request->volume_of_trips,
                            'approval_status'   => 0,
                            'updated_at'        => date('Y-m-d H:i:s'),
                        ]);

                        ForkliftRequestApprover::where('forklift_request_id', $request->forklift_request_id)->update([
                            'department_approver'               => $request->secthead_supervisor_clerk_approver,
                            'traffic_sr_supervisor_approver'    => $update_traffic_sr_supervisor,
                            'forklift_operator_approver'        => $update_forklift_operator,
                            'updated_at'                        => date('Y-m-d H:i:s'),
                        ]);
                    }else{
                        return response()->json(['result' => 1]);
                    }
                }
                
                $get_info = ForkliftRequest::with([  
                    'forklift_request_approver_info.requestor_approver_info', 
                ])
                ->where('id', $request->forklift_request_id)
                ->get();
                
                if(count($get_info) > 0){
                    $get_data = ['data' => $get_info, 'status' => 0, 'count' => count($get_info)];
                }else{
                    $get_data = ['data' => $request, 'status' => 0, 'name' => $_SESSION['rapidx_name'], 'count' => count($get_info)];
                }

                $send_email_to = $_SESSION['rapidx_email'];
                $send_email_cc = $_SESSION['rapidx_email'];

                Mail::send('mail.forklift_request_mail', $get_data, function($message) use($send_email_to, $send_email_cc){
                    $message->to($send_email_to)->cc($send_email_cc)->bcc('cbretusto@pricon.ph')->subject('For Approval: Forklift Request');
                });

                DB::commit();
                return response()->json(['hasError' => 0]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['hasError' => 1, 'exceptionError' => $e->getMessage()]);
            }
        }
    }

    public function getForkliftRequestApproverInfoById(Request $request){
        $forklift_request_info = ForkliftRequest::with([
            // 'forklift_request_approver_info', 
            // 'employee_info', 
            'employee_info.rapidx_user_info',
            'forklift_request_approver_info.department_approver_info', 
            'forklift_request_approver_info.traffic_sr_supervisor_approver_info', 
            'forklift_request_approver_info.forklift_operator_approver_info'
        ])
        ->where('id', $request->ForkliftRequestId)
        ->where('logdel', 0)
        ->get();

        $traffic_sr_supervisor = User::with(['rapidx_user_info'])->where('classification', 'Traffic Sr. Supervisor')->where('status', 1)->where('logdel', 0)->first();

        return response()->json([
            'forklift_request_info'     => $forklift_request_info,
            'traffic_sr_supervisor'     => $traffic_sr_supervisor
        ]);
    }

    public function forkliftRequestApproval(Request $request){
        date_default_timezone_set('Asia/Manila');

        session_start();
        $data = $request->all();

        if($request->status != ''){
            $stat = 'status';
        }else{
            $stat = 'request_status';
        }

        $validator = Validator::make($data, [
            'approve_disapprove_id' => 'required',
            $stat                   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['validationHasError' => 1, 'error' => $validator->messages()]);
        }else{
            DB::beginTransaction();
            try {
                $get_info = ForkliftRequest::with([  
                    // 'forklift_request_approver_info', 
                    'forklift_request_approver_info.requestor_approver_info', 
                    'forklift_request_approver_info.department_approver_info', 
                    'forklift_request_approver_info.traffic_sr_supervisor_approver_info', 
                    'forklift_request_approver_info.forklift_operator_approver_info'
                ])
                ->where('id', $request->approve_disapprove_id)
                ->get();
                
                if($get_info[0]->approval_status == 0 || $get_info[0]->approval_status == 5){
                    $column_name = 'requestor_date_time_approved_disapproved';
                }else if($get_info[0]->approval_status == 1 || $get_info[0]->approval_status == 6){
                    $column_name = 'department_date_time_approved_disapproved';
                }else if($get_info[0]->approval_status == 2 || $get_info[0]->approval_status == 7){
                    $column_name = 'traffic_sr_supervisor_date_time_approved_disapproved';
                }else{
                    $column_name = 'forklift_operator_date_time_approved_disapproved';
                }

                if($request->remark == ''){
                    $remark_value = null;
                }else{
                    $remark_value = $request->remark;
                }

                if($request->status != ''){
                    $status_update = [
                        'approval_status'   => $request->status,
                        'updated_at'        => date('Y-m-d H:i:s')
                    ];

                    ForkliftRequestApprover::where('forklift_request_id', $request->approve_disapprove_id)->update([
                        $column_name        => date('Y-m-d H:i:s'),
                        'approval_remarks'  => $remark_value
                    ]);    

                    switch ($request->status)
                    {    
                        case 1:{
                            $send_email_to = $get_info[0]->forklift_request_approver_info->department_approver_info->email;
                            $send_email_cc = $_SESSION['rapidx_email'];                                        
                            $get_data = ['data' => $get_info, 'count' => 1, 'status' => $request->status];

                            Mail::send('mail.forklift_request_mail', $get_data, function($message) use($send_email_to, $send_email_cc){
                                $message->to($send_email_to)->cc($send_email_cc)->bcc('cbretusto@pricon.ph')->subject('For Approval: Forklift Request');
                            });
                            break;
                        }
        
                        case 2:{
                            $send_email_to = $get_info[0]->forklift_request_approver_info->traffic_sr_supervisor_approver_info->email;
                            $send_email_cc = $_SESSION['rapidx_email'];                                        
                            $get_data = ['data' => $get_info, 'count' => 1, 'status' => $request->status];

                            Mail::send('mail.forklift_request_mail', $get_data, function($message) use($send_email_to, $send_email_cc){
                                $message->to($send_email_to)->cc($send_email_cc)->bcc('cbretusto@pricon.ph')->subject('For Approval: Forklift Request');
                            });
                            break;
                        }
        
                        case 3:{
                            $send_email_to = $get_info[0]->forklift_request_approver_info->forklift_operator_approver_info->email;
                            $send_email_cc = $_SESSION['rapidx_email'];                                        
                            $get_data = ['data' => $get_info, 'count' => 1, 'status' => $request->status];

                            Mail::send('mail.forklift_request_mail', $get_data, function($message) use($send_email_to, $send_email_cc){
                                $message->to($send_email_to)->cc($send_email_cc)->bcc('cbretusto@pricon.ph')->subject('For Approval: Forklift Request');
                            });
                            break;
                        }
        
                        case 4:{
                            $send_email_to = $get_info[0]->forklift_request_approver_info->requestor_approver_info->email;                                  
                            $get_data = ['data' => $get_info, 'count' => 1, 'status' => $request->status];

                            Mail::send('mail.forklift_request_mail', $get_data, function($message) use($send_email_to){
                                $message->to($send_email_to)->cc($send_email_to)->bcc('cbretusto@pricon.ph')->subject('Cancelled: Forklift Request');
                            });
                            break;
                        }
        
                        case 5:{
                            $send_email_to = $get_info[0]->forklift_request_approver_info->requestor_approver_info->email;
                            $send_email_cc = $_SESSION['rapidx_email'];                                        
                            $get_data = ['data' => $get_info, 'count' => 1, 'status' => $request->status];

                            Mail::send('mail.forklift_request_mail', $get_data, function($message) use($send_email_to, $send_email_cc){
                                $message->to($send_email_to)->cc($send_email_cc)->bcc('cbretusto@pricon.ph')->subject('Disapproved: Forklift Request');
                            });
                            break;
                        }
        
                        case 6:{
                            $send_email_to = $get_info[0]->forklift_request_approver_info->department_approver_info->email;
                            $send_email_cc = $_SESSION['rapidx_email'];                                        
                            $get_data = ['data' => $get_info, 'count' => 1, 'status' => $request->status];

                            Mail::send('mail.forklift_request_mail', $get_data, function($message) use($send_email_to, $send_email_cc){
                                $message->to($send_email_to)->cc($send_email_cc)->bcc('cbretusto@pricon.ph')->subject('Disapproved: Forklift Request');
                            });
                            break;
                        }
        
                        case 7:{
                            $send_email_to = $get_info[0]->forklift_request_approver_info->traffic_sr_supervisor_approver_info->email;
                            $send_email_cc = $_SESSION['rapidx_email'];                                        
                            $get_data = ['data' => $get_info, 'count' => 1, 'status' => $request->status];

                            Mail::send('mail.forklift_request_mail', $get_data, function($message) use($send_email_to, $send_email_cc){
                                $message->to($send_email_to)->cc($send_email_cc)->bcc('cbretusto@pricon.ph')->subject('Disapproved: Forklift Request');
                            });
                            break;
                        }
        
                        case 8:{
                            $send_email_to = $get_info[0]->forklift_request_approver_info->forklift_operator_approver_info->email;
                            $send_email_cc = $_SESSION['rapidx_email'];                                        
                            $get_data = ['data' => $get_info, 'count' => 1, 'status' => $request->status];

                            Mail::send('mail.forklift_request_mail', $get_data, function($message) use($send_email_to, $send_email_cc){
                                $message->to($send_email_to)->cc($send_email_cc)->bcc('cbretusto@pricon.ph')->subject('Disapproved: Forklift Request');
                            });
                            break;
                        }
        
                        default:
                        $send_email_to = $get_info[0]->forklift_request_approver_info->requestor_approver_info->email;  
                        $get_data = ['data' => $get_info, 'count' => 1, 'status' => $request->status];

                        Mail::send('mail.forklift_request_mail', $get_data, function($message) use($send_email_to){
                            $message->to($send_email_to)->cc($send_email_to)->bcc('cbretusto@pricon.ph')->subject('Approved: Forklift Request');
                        });
                    }
                }else{
                    $status_update = [
                        'request_status'    => $request->request_status,
                        'updated_at'        => date('Y-m-d H:i:s')
                    ];
                }

                ForkliftRequest::where('id', $request->approve_disapprove_id)->update(
                    $status_update
                );

                DB::commit();
                return response()->json(['hasError' => 0]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['hasError' => 1, 'exceptionError' => $e]);
            }
        }
    }
}
