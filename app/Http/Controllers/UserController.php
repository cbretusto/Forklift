<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth; // or use Illuminate\Support\Facades\Auth;
use DataTables;

/**
 * Import Models here
 */
use App\Models\User;
use App\Models\RapidXUser;
use App\Models\SystemOneHRIS;
use App\Models\SystemOneSubcon;
use App\Models\SystemOneDepartment;
use App\Models\SystemOneSection;

class UserController extends Controller
{
    public function viewUsers(){
        $userDetails = User::with(['rapidx_user_info'])->orderBy('employee_no', 'ASC')->where('logdel', 0)->get();
        
        return DataTables::of($userDetails)
        ->addColumn('action', function($userDetail){
            $result =   '<center>';
            $result .= '<button type="button" class="btn btn-dark btn-xs text-center actionEditUser mr-1" user-id="' . $userDetail->id . '" data-bs-toggle="modal" data-bs-target="#modalUser" title="Edit User Details"><i class="fa fa-xl fa-edit"></i></button>';
            
            if($userDetail->classification != 'Traffic Sr. Supervisor'){
                if($userDetail->status == 1){
                    $result .= '<button type="button" class="btn btn-danger btn-xs text-center actionChangeUserStat mr-1" user-id="' . $userDetail->id . '" status="2" data-bs-toggle="modal" data-bs-target="#modalChangeUserStat" title="Deactivate User"><i class="fa-solid fa-xl fa-ban"></i></button>';
                }else{
                    $result .= '<button type="button" class="btn btn-warning btn-xs text-center actionChangeUserStat mr-1" user-id="' . $userDetail->id . '" status="1" data-bs-toggle="modal" data-bs-target="#modalChangeUserStat" title="Activate User"><i class="fa-solid fa-xl fa-arrow-rotate-right"></i></button>';
                }
            }
            $result .= '</center>';
            return $result;
        })


        ->addColumn('status', function($userDetail){
            $result = "";
            if($userDetail->status == 1){
                $result .= '<center><span class="badge badge-pill badge-success">Active</span></center>';
            }
            else{
                $result .= '<center><span class="badge badge-pill badge-danger">Inactive</span></center>';
            }
            return $result;
        })

        ->addColumn('name', function($userDetail){
            $result = "";
            $result .= $userDetail->rapidx_user_info->name;
            
            return $result;
        })

        ->rawColumns(['action','status', 'name'])
        ->make(true);
    }

    public function getEmployeeID(){
        $get_employee_no = RapidXUser::where('user_stat', 1)->orderBy('employee_number', 'DESC')->whereNotNull('employee_number')->get();
        return response()->json(['get_employee_no' => $get_employee_no]);
    }

    public function getEmployeeInfo(Request $request){
        $rapidx_employee_no = RapidXUser::where('employee_number', $request->emp_no)->get();
        $pmi_employee = SystemOneHRIS::with(['department_info', 'section_info'])->where('EmpNo', $request->emp_no)->where('EmpStatus', 1)->get();
        $subcon_employee = SystemOneSubcon::with(['department_info', 'section_info'])->where('EmpNo', $request->emp_no)->where('EmpStatus', 1)->where('logdel', 0)->get();
        $result = $pmi_employee->toBase()->merge($subcon_employee);

        if(count($result) > 0){
            return response()->json(['rapidx_employee_no' => $rapidx_employee_no, 'result' => $result]);
        }else{
            return response()->json(['result' => 'no_data']);
        }
    }

    public function addEditUser(Request $request){
        date_default_timezone_set('Asia/Manila');

        session_start();
        // $rapidx_user_id = $_SESSION['rapidx_user_id'];

        $data = $request->all();
        $validator = Validator::make($data, [
            'employee_no'   => 'required',
            'employee_name' => 'required',
            'username'      => 'required',
            'department'    => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['validationHasError' => 1, 'error' => $validator->messages()]);
        } else {
            DB::beginTransaction();
            try {
                $check_existing_record = User::where('employee_no', $request->employee_no)->get();
                if($request->user_id != ''){
                    if( count($check_existing_record) != 1){
                        User::where('id', $request->user_id)->update([
                            'employee_no'   => $request->employee_no,
                            'username'      => $request->username,
                            'email'         => $request->email_address,
                            'department'    => $request->department,
                            'updated_at'    => date('Y-m-d H:i:s'),
                        ]);
                    }else{
                        return response()->json(['result' => 1]);
                    }
                }else{
                    if( count($check_existing_record) != 1){
                        User::insert([
                            'employee_no'   => $request->employee_no,
                            'username'      => $request->username,
                            'email'         => $request->email_address,
                            'department'    => $request->department,
                            'created_at'    => date('Y-m-d H:i:s'),
                        ]);    
                    }else{
                        return response()->json(['result' => 1]);
                    }
                }
                
                DB::commit();
                return response()->json(['hasError' => 0]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['hasError' => 1, 'exceptionError' => $e]);
            }
        }
    }

    public function getUserInfoById(Request $request){
        $user_info = User::where('id', $request->UserId)->get();

        return response()->json([
            'user_info' => $user_info, 
        ]);
    }

    //============================== CHANGE USER STAT ==============================
    public function changeUserStat(Request $request){        
        date_default_timezone_set('Asia/Manila');

        $data = $request->all(); // collect all input fields

        $validator = Validator::make($data, [
            'user_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            User::where('id', $request->user_id)
            ->update([
                'status' => $request->status,
                'classification' => null,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return response()->json(['result' => "1"]);
        }
        else{
            return response()->json(['validation' => "hasError", 'error' => $validator->messages()]);
        }
    }

    public function viewApproverList(){
        $approverDetails = User::with(['rapidx_user_info'])->whereNotNull('classification')->orderBy('classification', 'ASC')->where('logdel', 0)->get();
        
        return DataTables::of($approverDetails)
        ->addColumn('action', function($approverDetail){
            $result =   '<center>';
            $result .= '<button type="button" class="btn btn-dark btn-xs text-center actionEditApprover mr-1" user-id="' . $approverDetail->id . '" data-bs-toggle="modal" data-bs-target="#modalApprover" title="Edit Approver Details"><i class="fa fa-xl fa-edit"></i></button>';
            if($approverDetail->classification != 'Traffic Sr. Supervisor'){
                $result .= '<button type="button" class="btn btn-danger btn-xs text-center actionChangeUserStat mr-1" user-id="' . $approverDetail->id . '" status="2" data-bs-toggle="modal" data-bs-target="#modalChangeUserStat" title="Delete Approver"><i class="fa-solid fa-xl fa-ban"></i></button>';
            }
            $result .= '</center>';
            return $result;
        })
        ->addColumn('status', function($approverDetail){
            $result = "";
            if($approverDetail->status == 1){
                $result .= '<center><span class="badge badge-pill badge-success">Active</span></center>';
            }
            else{
                $result .= '<center><span class="badge badge-pill badge-danger">Inactive</span></center>';
            }
            return $result;
        })

        ->addColumn('name', function($approverDetail){
            $result =   "";
            $result .=  $approverDetail->rapidx_user_info->name;
            return $result;
        })

        ->rawColumns(['action','status', 'name'])
        ->make(true);
    }

    public function getApproverName(){
        $get_approver_name = User::with(['rapidx_user_info'])->orderBy('employee_no', 'ASC')->where('status', 1)->where('logdel', 0)->get();
        return response()->json(['get_approver_name' => $get_approver_name]);
    }

    public function addEditApprover(Request $request){
        date_default_timezone_set('Asia/Manila');

        session_start();
        // $rapidx_user_id = $_SESSION['rapidx_user_id'];
        $data = $request->all();
        $validator = Validator::make($data, [
            'approver_employee_no'  => 'required',
            'classification'        => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['validationHasError' => 1, 'error' => $validator->messages()]);
        } else {
            DB::beginTransaction();
            try {
                $update = User::where('id', $request->approver_id)->where('logdel', 0)->where('status', 1)->get();
                $for_traffic_sr_supervisor_only = User::where('employee_no', $request->approver_employee_no)->where('classification', 'Traffic Sr. Supervisor')->where('logdel', 0)->where('status', 1)->get();

                if(count($update) > 0){
                    if($update[0]->classification != 'Traffic Sr. Supervisor' || $update[0]->employee_no != $request->approver_employee_no){
                        User::where('id', $request->approver_id)->update([
                            'classification'    => null,
                            'updated_at'        => date('Y-m-d H:i:s'),
                        ]);
    
                        User::where('employee_no', $request->approver_employee_no)->update([
                            'classification'    => $request->classification,
                            'updated_at'        => date('Y-m-d H:i:s'),
                        ]);
                        $result = 1;
                    }else{
                        $result = 2;
                    }
                }else{
                    if(count($for_traffic_sr_supervisor_only) == 0){
                        User::where('employee_no', $request->approver_employee_no)->update([
                            'classification'    => $request->classification,
                            'updated_at'        => date('Y-m-d H:i:s'),
                        ]);
                        $result = 1;
                    }else{
                        $result = 2;
                    }
                }

                DB::commit();
                return response()->json(['hasError' => $result]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json(['hasError' => 0, 'exceptionError' => $e]);
            }
        }
    }

    public function getUserApproverInfoById(Request $request){
        $user_approver_info = User::with(['rapidx_user_info'])->where('id', $request->approverId)->get();

        return response()->json([
            'user_approver_info' => $user_approver_info, 
        ]);
    }

    public function getUserLog(Request $request){
        $user_log = User::with(['rapidx_user_info'])->where('employee_no', $request->loginEmployeeNumber)
        ->where('logdel', 0)
        ->get();
        return response()->json(['result' => $user_log]);
    }
}
