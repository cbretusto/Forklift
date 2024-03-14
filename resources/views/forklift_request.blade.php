@extends('layouts.admin_layout')

@section('title', 'Dashboard')

@section('content_page')
    <style type="text/css">
        table.table tbody td{
            padding: 4px 4px;
            margin: 1px 1px;
            font-size: 16px;
            /* text-align: center; */
            vertical-align: middle;
        }

        table.table thead th{
            padding: 4px 4px;
            margin: 1px 1px;
            font-size: 15px;
            text-align: center;
            vertical-align: middle;
        }
    </style>
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Forklift Request</h1>
                    </div>
                    <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Forklift Request</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            @php
                date_default_timezone_set('Asia/Manila');
            @endphp
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title" style="margin-top: 8px;">Forklift Request</h3>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#forkliftRequest" type="button" role="tab">Request</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#approvedRequest" type="button" role="tab">Approved</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#disapprovedRequest" type="button" role="tab">Disapproved</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#cancelledRequest" type="button" role="tab">Cancelled</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#servedRequest" type="button" role="tab">Served</button>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade show active" id="forkliftRequest" role="tabpanel">
                                        <div class="text-right mt-4">                   
                                            <button type="button" class="btn btn-dark mb-3" id="buttonAddForkliftRequest"><i class="fa fa-plus fa-md"></i> New Request</button>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="tableForkliftRequest" class="table table-bordered table-hover nowrap" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th>Request Status</th>
                                                        <th>Approver Status</th>
                                                        <th>Request No.</th>
                                                        <th>Requestor Name</th>
                                                        <th>Department</th>
                                                        <th>Date Needed</th>
                                                        <th>Time</th>
                                                        <th>Approvers</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="approvedRequest" role="tabpanel">
                                        <div class="table-responsive mt-3">
                                            <table id="tableApprovedRequest" class="table table-bordered table-hover nowrap" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th>Request Status</th>
                                                        <th>Approver Status</th>
                                                        <th>Request No.</th>
                                                        <th>Requestor Name</th>
                                                        <th>Department</th>
                                                        <th>Date Needed</th>
                                                        <th>Time</th>
                                                        <th>Approvers</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="disapprovedRequest" role="tabpanel">
                                        <div class="table-responsive mt-3">
                                            <table id="tableDisapprovedRequest" class="table table-bordered table-hover nowrap" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th>Request Status</th>
                                                        <th>Approver Status</th>
                                                        <th>Request No.</th>
                                                        <th>Requestor Name</th>
                                                        <th>Department</th>
                                                        <th>Date Needed</th>
                                                        <th>Time</th>
                                                        <th>Approvers</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="cancelledRequest" role="tabpanel">
                                        <div class="table-responsive mt-3">
                                            <table id="tableCancelledRequest" class="table table-bordered table-hover nowrap" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th>Request Status</th>
                                                        <th>Approver Status</th>
                                                        <th>Request No.</th>
                                                        <th>Requestor Name</th>
                                                        <th>Department</th>
                                                        <th>Date Needed</th>
                                                        <th>Time</th>
                                                        <th>Approvers</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="servedRequest" role="tabpanel">
                                        <div class="table-responsive mt-3">
                                            <table id="tableServedRequest" class="table table-bordered table-hover nowrap" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th>Request Status</th>
                                                        <th>Approver Status</th>
                                                        <th>Request No.</th>
                                                        <th>Requestor Name</th>
                                                        <th>Department</th>
                                                        <th>Date Needed</th>
                                                        <th>Time</th>
                                                        <th>Approvers</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <!-- Forklift Request Modal Start -->
    <div class="modal fade" id="modalForkliftRequest" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-2">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="fas fa-info-circle"></i>&nbsp;Request Information</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="formForkliftRequest" autocomplete="off">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="forklift_request_id" placeholder="Forklift Request ID" id="textForkliftRequestId">

                            <div class="card">
                                <div class="card-body">
                                    <div class="row justify-content-between text-left">
                                        <div class="form-group col-sm-4"> 
                                            <label class="form-control-label">Request No.</label> 
                                            <input type="text" id="txtRequestNo" name="request_no" class="form-control" placeholder="Auto Generate" readonly> 
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-4"> 
                                            <label for="employee_no" class="form-label">Employee No:<span class="text-danger" title="Required"></span></label>
                                            <input type="text" class="form-control" name="employee_no" id="txtEmployeeNo" placeholder="Employee No." readonly>
                                        </div>
                                        <div class="form-group col-sm-4 flex-column d-flex"> 
                                            <label for="requestor_name" class="form-label">Requestor Name:<span class="text-danger" title="Required"></span></label>
                                            <input type="text" class="form-control" name="requestor_name" id="textRequestorName" placeholder="Employee Name" readonly>
                                        </div>
                                        <div class="form-group col-sm-4 flex-column d-flex">
                                            <label for="department" class="form-label">Department:<span class="text-danger" title="Required"></span></label>
                                            <input type="text" class="form-control" name="department" id="textDepartment" placeholder="Department" readonly>
                                        </div>
                                    </div>
                                    
                                    <hr>

                                    <div class="row">
                                        <div class="form-group col-sm-6"> 
                                            <label for="date_needed" class="form-label">Date Needed:<span class="text-danger" title="Required"></span></label>
                                            <input type="date" class="form-control request" name="date_needed" id="dateNeeded" placeholder="Date Needed">
                                        </div>
                                        <div class="form-group col-sm-6 flex-column d-flex"> 
                                            <label for="time_start" class="form-label">Time Start:<span class="text-danger" title="Required"></span></label>
                                            <input type="time" class="form-control request" name="time_start" id="timeStart" placeholder="Time Start">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-6"> 
                                            <label for="pickup_from" class="form-label">Pick-up From:<span class="text-danger" title="Required"></span></label>
                                            <input type="text" class="form-control request" name="pickup_from" id="txtPickupFrom" placeholder="Pick-up From">
                                        </div>
                                        <div class="form-group col-sm-6 flex-column d-flex"> 
                                            <label for="deliver_to" class="form-label">Deliver To:<span class="text-danger" title="Required"></span></label>
                                            <input type="text" class="form-control request" name="deliver_to" id="textDeliverTo" placeholder="Deliver To">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-sm-6"> 
                                            <label for="package_commodity" class="form-label">Number of Package / Commodity:<span class="text-danger" title="Required"></span></label>
                                            <input type="number" class="form-control request" name="package_commodity" id="nmbrPackageCommodity" placeholder="Number of Package / Commodity">
                                        </div>
                                        <div class="form-group col-sm-6 flex-column d-flex"> 
                                            <label for="volume_of_trips" class="form-label">Volume of Trips:<span class="text-danger" title="Required"></span></label>
                                            <input type="number" class="form-control request" name="volume_of_trips" id="nmbrVolumeOfTrips" placeholder="Volume of Trips">
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="row">
                                        <div class="form-group col-sm-6"> 
                                            <label for="secthead_supervisor_clerk_approver" class="form-label">Sect. Head / Supervisor / Clerk:<span class="text-danger" title="Required"></span></label>
                                            <select class="form-control select2bs4 slctClerkSupervisorSectHead request" name="secthead_supervisor_clerk_approver" id="selectSectheadSupervisorClerk" placeholder="Sect. Head / Supervisor / Clerk"></select>
                                        </div>
                                        
                                        @if ( \Carbon\Carbon::now()->format('H:i:s') <= '16:30:00' )
                                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                                <label for="traffic_sr_supervisor_approver" class="form-label">Traffic Sr. Supervisor:<span class="text-danger" title="Required"></span></label>
                                                <input type="text" class="form-control slctEmployeeName" name="traffic_sr_supervisor_approver" id="textTrafficSrSupervisorApprover" placeholder="Traffic Sr. Supervisor" value="" readonly>
                                            </div>
                                        @else
                                            <div class="form-group col-sm-6 flex-column d-flex"> 
                                                <label for="forklift_operator_approver" class="form-label">Forklift Operator:<span class="text-danger" title="Required"></span></label>
                                                <select class="form-control select2bs4 slctForkliftOperator" name="forklift_operator_approver" id="selectForkliftOperatorApprover" placeholder="Forklift Operator"></select>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between" id="footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="btnForkliftRequest" class="btn btn-dark"><i id="iBtnForkliftRequestIcon" class="fa fa-check"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- Forklift Request Modal End -->
    
    <!-- Approve / Disapprove Modal Start -->
    <div class="modal fade" id="modalApproveDisapprove" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header" id="statusHeader">
                    <h4 class="modal-title"><i class="fas fa-info-circle"></i>&nbsp;System Confirmation</h4>
                    <button type="button" class="btn-close closeModal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" id="formApproveDisapprove" autocomplete="off">
                    @csrf
                    <div class="modal-body remark">
                        <input type="hidden" name="approve_disapprove_id" id="textApproveDisapproveId" required readonly>
                        <input type="hidden" name="status" placeholder="Status" id="textApproveDisapproveStatus" readonly required>
                        <input type="hidden" name="request_status" placeholder="Request Status" id="textRequestStatus" readonly required>
                        <label id="lblChangeLabel"></label>
                    </div>
                    
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default closeModal" data-bs-dismiss="modal">Exit</button>
                        <button type="submit" id="buttonApproveDisapproveStatus" class="btn btn-default"><i id="iBtnApproveDisapproveIcon" class=""></i>&nbsp; </button>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- Approve / Disapprove Modal End -->

@endsection

<!-- JS CONTENT --}} -->
@section('js_content')
    <script type="text/javascript">
    let dataTablesRequest;
    let dataTablesRequestApproved;
    let dataTablesRequestDisapproved;
    let dataTablesRequestCancelled;
    let dataTablesRequestServed;

        $(document).ready(function () {

            $('.select2bs4').select2({
                theme: 'bootstrap-5'
            });

            GetClerkSupervisorSectHead($('.slctClerkSupervisorSectHead'));
            GetForkliftOperator($('.slctForkliftOperator'));

            dataTablesRequest = $("#tableForkliftRequest").DataTable({
                "processing" : false,
                "serverSide" : true,
                "responsive": true,
                // "order": [[ 0, "desc" ],[ 4, "desc" ]],
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ Forklift Request",
                    "lengthMenu": "Show _MENU_ Forklift Request",
                },
                "ajax" : {
                    url: "view_forklift_request",
                },
                "columns":[
                    { "data" : "action", orderable:false, searchable:false},
                    { "data" : "request_status"},
                    { "data" : "approver_status"},
                    { "data" : "request_no"},
                    { "data" : "name"},
                    { "data" : "department"},
                    { "data" : "date_needed"},
                    { "data" : "time"},
                    { "data" : "approvers"},
                ],
            });

            dataTablesRequestApproved = $("#tableApprovedRequest").DataTable({
                "processing" : false,
                "serverSide" : true,
                "responsive": true,
                // "order": [[ 0, "desc" ],[ 4, "desc" ]],
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ Approved Forklift Request",
                    "lengthMenu": "Show _MENU_ Approved Forklift Request",
                },
                "ajax" : {
                    url: "view_approved_forklift_request",
                },
                "columns":[
                    { "data" : "action", orderable:false, searchable:false},
                    { "data" : "request_status"},
                    { "data" : "approver_status"},
                    { "data" : "request_info.request_no"},
                    { "data" : "name"},
                    { "data" : "request_info.department"},
                    { "data" : "request_info.date_needed"},
                    { "data" : "request_info.time"},
                    { "data" : "approvers"},
                ],
            });

            dataTablesRequestDisapproved = $("#tableDisapprovedRequest").DataTable({
                "processing" : false,
                "serverSide" : true,
                "responsive": true,
                // "order": [[ 0, "desc" ],[ 4, "desc" ]],
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ Disapproved Forklift Request",
                    "lengthMenu": "Show _MENU_ Disapproved Forklift Request",
                },
                "ajax" : {
                    url: "view_disapproved_forklift_request",
                },
                "columns":[
                    { "data" : "action", orderable:false, searchable:false},
                    { "data" : "request_status"},
                    { "data" : "approver_status"},
                    { "data" : "request_info.request_no"},
                    { "data" : "name"},
                    { "data" : "request_info.department"},
                    { "data" : "request_info.date_needed"},
                    { "data" : "request_info.time"},
                    { "data" : "approvers"},
                ],
            });

            dataTablesRequestCancelled = $("#tableCancelledRequest").DataTable({
                "processing" : false,
                "serverSide" : true,
                "responsive": true,
                // "order": [[ 0, "desc" ],[ 4, "desc" ]],
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ Cancelled Forklift Request",
                    "lengthMenu": "Show _MENU_ Cancelled Forklift Request",
                },
                "ajax" : {
                    url: "view_cancelled_forklift_request",
                },
                "columns":[
                    { "data" : "action", orderable:false, searchable:false},
                    { "data" : "request_status"},
                    { "data" : "approver_status"},
                    { "data" : "request_info.request_no"},
                    { "data" : "name"},
                    { "data" : "request_info.department"},
                    { "data" : "request_info.date_needed"},
                    { "data" : "request_info.time"},
                    { "data" : "approvers"},
                ],
            });

            dataTablesRequestServed = $("#tableServedRequest").DataTable({
                "processing" : false,
                "serverSide" : true,
                "responsive": true,
                // "order": [[ 0, "desc" ],[ 4, "desc" ]],
                "language": {
                    "info": "Showing _START_ to _END_ of _TOTAL_ Served Forklift Request",
                    "lengthMenu": "Show _MENU_ Served Forklift Request",
                },
                "ajax" : {
                    url: "view_served_forklift_request",
                },
                "columns":[
                    { "data" : "action", orderable:false, searchable:false},
                    { "data" : "request_status"},
                    { "data" : "approver_status"},
                    { "data" : "request_info.request_no"},
                    { "data" : "name"},
                    { "data" : "request_info.department"},
                    { "data" : "request_info.date_needed"},
                    { "data" : "request_info.time"},
                    { "data" : "approvers"},
                ],
            });
            
            $('#buttonAddForkliftRequest').click(function(event){
                event.preventDefault();
                $.ajax({
                    url: 'get_request_info',
                    method: 'get',
                    data: {
                        'emp_no': ''
                    },
                    beforeSend: function(){
                    
                    },
                    success: function (response) {
                        let getNewRequestNo = response['new_request_no'];
                        let getInfo = response['get_info'];
                        let getTrafficSrSupervisor = response['get_traffic_sr_supervisor'];

                        $('#txtRequestNo').val(getNewRequestNo);

                        if(getInfo.length > 0){
                            $('#modalForkliftRequest').modal('show');
                        }else{
                            alert('          Maaring tawagan ang numerong "227" at hanapin '+'\n'+' si Binibining Jasmin Palentinos para maisakatuparan ang '+'\n'+' iyong kahilingan. Maraming Salamat!')
                        }

                        if(getInfo.length > 0){
                            $('#txtEmployeeNo').val(getInfo[0].employee_no);
                            $('#textRequestorName').val(getInfo[0].rapidx_user_info.name);
                            $('#textDepartment').val(getInfo[0].department);
                        }

                        // console.log('getInfo', getInfo.length)
                        // console.log('DATE:', (new Date()).toISOString().split('T')[0])
                        // console.log('DATE:', new Date().toLocaleTimeString())
                        
                        $time_now = moment().format('HH:mm:ss');
                        setTimeout(() => {     
                            if($time_now >= '7:30 AM' || $time_now <= '4:29 PM'){
                                $('#textTrafficSrSupervisorApprover').val(getTrafficSrSupervisor.rapidx_user_info.name);
                            }
                            else{
                                $('#textTrafficSrSupervisorApprover').val('');
                            }
                        }, 300);
                    }
                });
            });

            $(document).on('click', '.actionEditForkliftRequest', function(e){
                e.preventDefault();

                let ForkliftRequestId = $(this).attr('forklift_request-id'); 
                $("#textForkliftRequestId").val(ForkliftRequestId);

                GetForkliftRequestInfoByIdToEdit(ForkliftRequestId); 
            });

            $("#formForkliftRequest").submit(function(event){
                event.preventDefault();
                AddEditForkliftRequest();
            });

            $(document).on('click', '.actionViewForkliftRequest', function(e){
                e.preventDefault();

                let ForkliftRequestId = $(this).attr('forklift_request-id'); 
                $("#textForkliftRequestId").val(ForkliftRequestId);
                $("#footer").addClass('d-none');
                $(".request").attr('disabled', true);
                GetForkliftRequestInfoByIdToEdit(ForkliftRequestId); 
            });

            $('#modalForkliftRequest').on('hidden.bs.modal', function(event){
                event.preventDefault();
                $("#textForkliftRequestId").val('');
                $("#selectSectheadSupervisorClerk").val('').trigger('change');
                $(".request").attr('disabled', false);
                $("#footer").removeClass('d-none');
                $("#formForkliftRequest")[0].reset();
            });

            $(document).on('click', '.actionApproveForkliftRequest', function(e){
                e.preventDefault();
                $('#statusHeader').addClass('bg-success')
                $('#statusHeader').removeClass('bg-danger bg-warning')
                $('#buttonApproveDisapproveStatus').addClass('bg-success')
                $('#buttonApproveDisapproveStatus').removeClass('bg-danger bg-warning')
                $('#iBtnApproveDisapproveIcon').addClass('fa fa-xl fa-thumbs-up')
                $('#iBtnApproveDisapproveIcon').removeClass('fa-thumbs-down fa-handshake')
                $("#lblChangeLabel").text('Are you sure you want to approve the Forklift request?'); 

                let forkliftRequestId = $(this).attr('forklift_request-id');
                let forkliftRequestStatus = $(this).attr('status');
                $('#textApproveDisapproveId').val(forkliftRequestId);
                $('#textApproveDisapproveStatus').val(forkliftRequestStatus);
                $('#textRequestStatus').val('');
            });

            $(document).on('click', '.actionDisapproveForkliftRequest', function(e){
                e.preventDefault();
                $('#statusHeader').removeClass('bg-success bg-warning')
                $('#statusHeader').addClass('bg-danger')
                $('#buttonApproveDisapproveStatus').removeClass('bg-success bg-warning')
                $('#buttonApproveDisapproveStatus').addClass('bg-danger')
                $('#iBtnApproveDisapproveIcon').removeClass('fa-thumbs-up fa-handshake')
                $('#iBtnApproveDisapproveIcon').addClass('fa fa-xl fa-thumbs-down')
                $("#lblChangeLabel").text(''); 

                var html =  '<div class="removeRemark">';
                    html +=     '<textarea type="text" class="form-control" id="txtAddRemark" name="remark" placeholder="Remark" maxlength="255"></textarea>';
                    html += '</div>';
                    $('.remark').append(html);

                let forkliftRequestId = $(this).attr('forklift_request-id');
                let forkliftRequestStatus = $(this).attr('status');
                $('#textApproveDisapproveId').val(forkliftRequestId);
                $('#textApproveDisapproveStatus').val(forkliftRequestStatus);
                $('#textRequestStatus').val('');
            });

            $(document).on('click', '.actionCancelForkliftRequest', function(e){
                e.preventDefault();
                $('#statusHeader').removeClass('bg-success')
                $('#statusHeader').addClass('bg-danger')
                $('#buttonApproveDisapproveStatus').removeClass('bg-success')
                $('#buttonApproveDisapproveStatus').addClass('bg-danger')
                $('#iBtnApproveDisapproveIcon').removeClass('fa-thumbs-up')
                $('#iBtnApproveDisapproveIcon').addClass('fa fa-xl fa-cancel')
                $("#lblChangeLabel").text('Are you sure you want to cancel the Forklift request?'); 

                let forkliftRequestId = $(this).attr('forklift_request-id');
                let forkliftRequestStatus = $(this).attr('status');
                $('#textApproveDisapproveId').val(forkliftRequestId);
                $('#textApproveDisapproveStatus').val(forkliftRequestStatus);
                $('#textRequestStatus').val('');
            });

            $(document).on('click', '.actionClosedForkliftRequest', function(e){
                e.preventDefault();
                $('#statusHeader').removeClass('bg-success bg-danger')
                $('#statusHeader').addClass('bg-warning')
                $('#buttonApproveDisapproveStatus').removeClass('bg-success bg-danger')
                $('#buttonApproveDisapproveStatus').addClass('bg-warning')
                $('#iBtnApproveDisapproveIcon').removeClass('fa-thumbs-up fa-thumbs-down')
                $('#iBtnApproveDisapproveIcon').addClass('fa fa-xl fa-handshake')
                $("#lblChangeLabel").text('Are you sure you want to close the Forklift request?'); 

                let cancelForkliftRequestId = $(this).attr('forklift_request-id');
                let cancelForkliftRequestStatus = $(this).attr('request_status');
                $('#textApproveDisapproveId').val(cancelForkliftRequestId);
                $('#textApproveDisapproveStatus').val('');
                $('#textRequestStatus').val(cancelForkliftRequestStatus);
            });

            $('.closeModal').on('click', function(event){
                event.preventDefault();
                $('.removeRemark').remove();
            });

            $("#formApproveDisapprove").submit(function(event){
                event.preventDefault();
                ApproveDisapproveForkliftRequest();
            });

            // auto resize the textareas
            document.querySelectorAll("textarea").forEach(function (size) {
                size.addEventListener("input", function () {
                    var resize = window.getComputedStyle(this);
                    // reset height to allow textarea to shrink again
                    this.style.height = "auto";
                    // when "box-sizing: border-box" we need to add vertical border size to scrollHeight
                    this.style.height = (this.scrollHeight + parseInt(resize.getPropertyValue("border-top-width")) + parseInt(resize.getPropertyValue("border-bottom-width"))) + "px";
                });
            });
        });
    </script>
@endsection

