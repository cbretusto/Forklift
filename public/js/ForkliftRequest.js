function GetClerkSupervisorSectHead(cboElement){
    let result = '<option value="">N/A</option>';

    $.ajax({

        url: "get_clerk_supervisor_secthead",
        method: "get",
        dataType: "json",

        beforeSend: function(){
            result = '<option value="" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(response){
            result = '';

            if(response['collect_approver'].length > 0){
                result = '<option selected disabled> Approver Name </option>';
                for(let index = 0; index < response['collect_approver'].length; index++){
                    result += '<option value="' + response['collect_approver'][index].employee_no+'">'+ response['collect_approver'][index].rapidx_user_info.name+'</option>';
                }
            }
            else{
                result = '<option value="0" selected disabled> No record found </option>';
            }
            cboElement.html(result);
        }

    });
}

function GetForkliftOperator(cboElement){
    let result = '<option value="">N/A</option>';

    $.ajax({

        url: "get_forklift_operator",
        method: "get",
        dataType: "json",

        beforeSend: function(){
            result = '<option value="" selected disabled> -- Loading -- </option>';
            cboElement.html(result);
        },
        success: function(response){
            result = '';

            if(response['get_forklift_operator_approver'].length > 0){
                result = '<option selected disabled> Forklift Operator </option>';
                for(let index = 0; index < response['get_forklift_operator_approver'].length; index++){
                    result += '<option value="' + response['get_forklift_operator_approver'][index].employee_no +'">'+ response['get_forklift_operator_approver'][index].rapidx_user_info.name+'</option>';
                }
            }
            else{
                result = '<option value="0" selected disabled> No record found </option>';
            }
            cboElement.html(result);
        }

    });
}

function AddEditForkliftRequest(){
	$.ajax({
        url: "add_edit_forklift_request",
        method: "post",
        data: $('#formForkliftRequest').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnForkliftRequestIcon").addClass('spinner-border spinner-border-sm');
            $("#btnForkliftRequest").addClass('disabled');
            $("#iBtnForkliftRequestIcon").removeClass('fa fa-check');
        },
        success: function(response){
            if(response['validationHasError'] == 1){
                toastr.error('Saving Request failed!');

                if(response['error']['request_no'] === undefined){
                    $("#txtRequestNo").removeClass('is-invalid');
                    $("#txtRequestNo").attr('title', '');
                }
                else{
                    $("#txtRequestNo").addClass('is-invalid');
                    $("#txtRequestNo").attr('title', response['error']['request_no']);
                }

                if(response['error']['employee_no'] === undefined){
                    $("#txtEmployeeNo").removeClass('is-invalid');
                    $("#txtEmployeeNo").attr('title', '');
                }
                else{
                    $("#txtEmployeeNo").addClass('is-invalid');
                    $("#txtEmployeeNo").attr('title', response['error']['employee_no']);
                }

                if(response['error']['requestor_name'] === undefined){
                    $("#textRequestorName").removeClass('is-invalid');
                    $("#textRequestorName").attr('title', '');
                }
                else{
                    $("#textRequestorName").addClass('is-invalid');
                    $("#textRequestorName").attr('title', response['error']['requestor_name']);
                }

                if(response['error']['department'] === undefined){
                    $("#textDepartment").removeClass('is-invalid');
                    $("#textDepartment").attr('title', '');
                }
                else{
                    $("#textDepartment").addClass('is-invalid');
                    $("#textDepartment").attr('title', response['error']['department']);
                }

                if(response['error']['date_needed'] === undefined){
                    $("#dateNeeded").removeClass('is-invalid');
                    $("#dateNeeded").attr('title', '');
                }
                else{
                    $("#dateNeeded").addClass('is-invalid');
                    $("#dateNeeded").attr('title', response['error']['date_needed']);
                }

                if(response['error']['time_start'] === undefined){
                    $("#timeStart").removeClass('is-invalid');
                    $("#timeStart").attr('title', '');
                }
                else{
                    $("#timeStart").addClass('is-invalid');
                    $("#timeStart").attr('title', response['error']['time_start']);
                }

                if(response['error']['pickup_from'] === undefined){
                    $("#txtPickupFrom").removeClass('is-invalid');
                    $("#txtPickupFrom").attr('title', '');
                }
                else{
                    $("#txtPickupFrom").addClass('is-invalid');
                    $("#txtPickupFrom").attr('title', response['error']['pickup_from']);
                }

                if(response['error']['deliver_to'] === undefined){
                    $("#textDeliverTo").removeClass('is-invalid');
                    $("#textDeliverTo").attr('title', '');
                }
                else{
                    $("#textDeliverTo").addClass('is-invalid');
                    $("#textDeliverTo").attr('title', response['error']['deliver_to']);
                }

                if(response['error']['package_commodity'] === undefined){
                    $("#nmbrPackageCommodity").removeClass('is-invalid');
                    $("#nmbrPackageCommodity").attr('title', '');
                }
                else{
                    $("#nmbrPackageCommodity").addClass('is-invalid');
                    $("#nmbrPackageCommodity").attr('title', response['error']['package_commodity']);
                }

                if(response['error']['volume_of_trips'] === undefined){
                    $("#nmbrVolumeOfTrips").removeClass('is-invalid');
                    $("#nmbrVolumeOfTrips").attr('title', '');
                }
                else{
                    $("#nmbrVolumeOfTrips").addClass('is-invalid');
                    $("#nmbrVolumeOfTrips").attr('title', response['error']['volume_of_trips']);
                }

                if(response['error']['secthead_supervisor_clerk_approver'] === undefined){
                    $("#selectSectheadSupervisorClerk").removeClass('is-invalid');
                    $("#selectSectheadSupervisorClerk").attr('title', '');
                }
                else{
                    $("#selectSectheadSupervisorClerk").addClass('is-invalid');
                    $("#selectSectheadSupervisorClerk").attr('title', response['error']['secthead_supervisor_clerk_approver']);
                }

                if(response['error']['traffic_sr_supervisor_approver'] === undefined){
                    $("#textTrafficSrSupervisorApprover").removeClass('is-invalid');
                    $("#textTrafficSrSupervisorApprover").attr('title', '');
                }
                else{
                    $("#textTrafficSrSupervisorApprover").addClass('is-invalid');
                    $("#textTrafficSrSupervisorApprover").attr('title', response['error']['traffic_sr_supervisor_approver']);
                }

                if(response['error']['forklift_operator_approver'] === undefined){
                    $("#selectForkliftOperatorApprover").removeClass('is-invalid');
                    $("#selectForkliftOperatorApprover").attr('title', '');
                }
                else{
                    $("#selectForkliftOperatorApprover").addClass('is-invalid');
                    $("#selectForkliftOperatorApprover").attr('title', response['error']['forklift_operator_approver']);
                }
            }else if(response['hasError'] == 0){
                $("#formForkliftRequest")[0].reset();
                $('#modalForkliftRequest').modal('hide');
                toastr.success('Succesfully saved!');
                dataTablesRequest.draw();
            }
            else{
                alert('Request No. "'+$("#txtRequestNo").val()+'" is already exist! '+"\n\n"+' Please refresh the browser to process the request once again.')
            }

            $("#iBtnForkliftRequestIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnForkliftRequest").removeClass('disabled');
            $("#iBtnForkliftRequestIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetForkliftRequestInfoByIdToEdit(ForkliftRequestId){
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "3000",
        "timeOut": "3000",
        "extendedTimeOut": "3000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut",
    };

    $.ajax({
        url: "get_forklift_request_info_by_id",
        method: "get",
        data: {
            ForkliftRequestId: ForkliftRequestId
        },
        dataType: "json",
        beforeSend: function(){
        },
        success: function(response){
            let forkliftRequestInfo = response['forklift_request_info'];
            let trafficSrSupervisor = response['traffic_sr_supervisor'];
            console.log('forkliftRequestInfo',forkliftRequestInfo)
            if(forkliftRequestInfo.length > 0){
                $("#txtRequestNo").val(forkliftRequestInfo[0].request_no);
                $("#txtEmployeeNo").val(forkliftRequestInfo[0].employee_info.employee_no);
                $("#textRequestorName").val(forkliftRequestInfo[0].employee_info.rapidx_user_info.name);
                $("#textDepartment").val(forkliftRequestInfo[0].employee_info.department);
                $("#dateNeeded").val(forkliftRequestInfo[0].date_needed);
                $("#timeStart").val(forkliftRequestInfo[0].time);
                $("#txtPickupFrom").val(forkliftRequestInfo[0].pick_up_from);
                $("#textDeliverTo").val(forkliftRequestInfo[0].delivery_to);
                $("#nmbrPackageCommodity").val(forkliftRequestInfo[0].package_commodity);
                $("#nmbrVolumeOfTrips").val(forkliftRequestInfo[0].volume_of_trips);
                $("#selectSectheadSupervisorClerk").val(forkliftRequestInfo[0].forklift_request_approver_info.department_approver).trigger('change');

                if( new Date().toLocaleTimeString() <= '4:30:00 PM'){
                    if(forkliftRequestInfo[0].forklift_request_approver_info.traffic_sr_supervisor_approver != null){
                        $("#textTrafficSrSupervisorApprover").val(forkliftRequestInfo[0].forklift_request_approver_info.traffic_sr_supervisor_approver_info.name);
                    }else{
                        $("#textTrafficSrSupervisorApprover").val(trafficSrSupervisor.rapidx_user_info.name);
                    }
                }else{
                    $("#selectForkliftOperatorApprover").val(forkliftRequestInfo[0].forklift_operator_approver).trigger('change');
                }
            }
            else{
                toastr.warning('No Record Found!');
            }
        },

        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function ApproveDisapproveForkliftRequest(){
	$.ajax({
        url: "forklift_request_approval",
        method: "post",
        data: $('#formApproveDisapprove').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnApproveDisapproveIcon").addClass('spinner-border spinner-border-sm');
            $("#buttonApproveDisapproveStatus").addClass('disabled');
            $("#iBtnApproveDisapproveIcon").removeClass('fa fa-check');
        },
        success: function(response){
            if(response['validationHasError'] == 1){
                toastr.error('Saving Request failed!');

                if(response['error']['approve_disapprove_id'] === undefined){
                    $("#textApproveDisapproveId").removeClass('is-invalid');
                    $("#textApproveDisapproveId").attr('title', '');
                }
                else{
                    $("#textApproveDisapproveId").addClass('is-invalid');
                    $("#textApproveDisapproveId").attr('title', response['error']['approve_disapprove_id']);
                }

                if(response['error']['status'] === undefined){
                    $("#textApproveDisapproveStatus").removeClass('is-invalid');
                    $("#textApproveDisapproveStatus").attr('title', '');
                }
                else{
                    $("#textApproveDisapproveStatus").addClass('is-invalid');
                    $("#textApproveDisapproveStatus").attr('title', response['error']['status']);
                }

                if(response['error']['remark'] === undefined){
                    $("#txtAddRemark").removeClass('is-invalid');
                    $("#txtAddRemark").attr('title', '');
                }
                else{
                    $("#txtAddRemark").addClass('is-invalid');
                    $("#txtAddRemark").attr('title', response['error']['remark']);
                }
            }else if(response['hasError'] == 0){
                $("#formApproveDisapprove")[0].reset();
                $('#modalApproveDisapprove').modal('hide');
                toastr.success('Succesfully saved!');
                dataTablesRequest.draw();
                dataTablesRequestApproved.draw();
                dataTablesRequestDisapproved.draw();
                dataTablesRequestCancelled.draw();
            }
            // else{
            //     alert('Request No. "'+$("#txtRequestNo").val()+'" is already exist! '+"\n\n"+' Please refresh the browser to process the request once again.')
            // }

            $("#iBtnApproveDisapproveIcon").removeClass('spinner-border spinner-border-sm');
            $("#buttonApproveDisapproveStatus").removeClass('disabled');
            $("#iBtnApproveDisapproveIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}