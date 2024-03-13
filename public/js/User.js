function GetEmployeeNo(cboElement){
    let result = '<option value="">N/A</option>';

    $.ajax({

    url: "get_employee_no",
    method: "get",
    dataType: "json",

    beforeSend: function(){
        result = '<option value="" selected disabled> -- Loading -- </option>';
        cboElement.html(result);
    },
    success: function(response){
        result = '';

        if(response['get_employee_no'].length > 0){
            result = '<option selected disabled> Employee No. </option>';
            for(let index = 0; index < response['get_employee_no'].length; index++){
                result += '<option value="' + response['get_employee_no'][index].employee_number+'">'+ response['get_employee_no'][index].employee_number+'</option>';
            }
        }
        else{
            result = '<option value="0" selected disabled> No record found </option>';
        }
        cboElement.html(result);
    }

    });
}

function User(){
	$.ajax({
        url: "add_edit_user",
        method: "post",
        data: $('#formUser').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnUserIcon").addClass('spinner-border spinner-border-sm');
            $("#btnUser").addClass('disabled');
            $("#iBtnUserIcon").removeClass('fa fa-check');
        },
        success: function(response){
            if(response['validationHasError'] == 1){
                toastr.error('Saving user failed!');

                if(response['error']['employee_no'] === undefined){
                    $("#selectEmployeeNo").removeClass('is-invalid');
                    $("#selectEmployeeNo").attr('title', '');
                }
                else{
                    $("#selectEmployeeNo").addClass('is-invalid');
                    $("#selectEmployeeNo").attr('title', response['error']['employee_no']);
                }

                if(response['error']['employee_name'] === undefined){
                    $("#textEmployeeName").removeClass('is-invalid');
                    $("#textEmployeeName").attr('title', '');
                }
                else{
                    $("#textEmployeeName").addClass('is-invalid');
                    $("#textEmployeeName").attr('title', response['error']['employee_name']);
                }

                if(response['error']['username'] === undefined){
                    $("#textUsername").removeClass('is-invalid');
                    $("#textUsername").attr('title', '');
                }
                else{
                    $("#textUsername").addClass('is-invalid');
                    $("#textUsername").attr('title', response['error']['username']);
                }

                if(response['error']['email_address'] === undefined){
                    $("#textEmailAddress").removeClass('is-invalid');
                    $("#textEmailAddress").attr('title', '');
                }
                else{
                    $("#textEmailAddress").addClass('is-invalid');
                    $("#textEmailAddress").attr('title', response['error']['email_address']);
                }

                if(response['error']['department'] === undefined){
                    $("#textDepartment").removeClass('is-invalid');
                    $("#textDepartment").attr('title', '');
                }
                else{
                    $("#textDepartment").addClass('is-invalid');
                    $("#textDepartment").attr('title', response['error']['department']);
                }

            }else if(response['hasError'] == 0){
                $("#formUser")[0].reset();
                $('#modalUser').modal('hide');
                toastr.success('Succesfully saved!');
                dataTableUsers.draw();
            }
            else{
                alert('Employee No. "'+$("#selectEmployeeNo").val()+'" is already exist!')
            }

            $("#iBtnUserIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnUser").removeClass('disabled');
            $("#iBtnUserIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetUserInfoByIdToEdit(UserId){
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
        url: "get_user_info_by_id",
        method: "get",
        data: {
            UserId: UserId
        },
        dataType: "json",
        beforeSend: function(){
        },
        success: function(response){
            let userInfo = response['user_info'];

            if(userInfo.length > 0){
                $("#selectEmployeeNo").val(userInfo[0].employee_no).trigger('change');
                $("#textEmployeeName").val(userInfo[0].name);
                $("#textUsername").val(userInfo[0].username);
                $("#textEmailAddress").val(userInfo[0].email);
                $("#textDepartment").val(userInfo[0].department);

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

function ChangeUserStatus(){
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
        url: "change_user_stat",
        method: "post",
        data: $('#formChangeUserStat').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnChangeUserStatIcon").addClass('fa fa-spinner fa-pulse');
            $("#btnChangeUserStat").prop('disabled', 'disabled');
        },
        success: function(response){

            if(response['validation'] == 'hasError'){
                toastr.error('User activation failed!');
            }else{
                if(response['result'] == 1){
                    if($("#txtChangeUserStatUserStat").val() == 1){
                        toastr.success('User activation success!');
                        $("#txtChangeUserStatUserStat").val() == 2;
                    }
                    else{
                        toastr.success('User deactivation success!');
                        $("#txtChangeUserStatUserStat").val() == 1;
                    }
                }
                $("#modalChangeUserStat").modal('hide');
                $("#formChangeUserStat")[0].reset();
                dataTableUsers.draw();
                dataTableApprover.draw();

            }


            $("#iBtnChangeUserStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeUserStat").removeAttr('disabled');
            $("#iBtnChangeUserStatIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
            $("#iBtnChangeUserStatIcon").removeClass('fa fa-spinner fa-pulse');
            $("#btnChangeUserStat").removeAttr('disabled');
            $("#iBtnChangeUserStatIcon").addClass('fa fa-check');
        }
    });
}

function GetApproverName(cboElement){
    let result = '<option value="">N/A</option>';

    $.ajax({

    url: "get_approver_name",
    method: "get",
    dataType: "json",

    beforeSend: function(){
        result = '<option value="" selected disabled> -- Loading -- </option>';
        cboElement.html(result);
    },
    success: function(response){
        result = '';

        if(response['get_approver_name'].length > 0){
            result = '<option selected value="" disabled> Approver Name </option>';
            for(let index = 0; index < response['get_approver_name'].length; index++){
                result += '<option value="' + response['get_approver_name'][index].employee_no+'">'+ response['get_approver_name'][index].rapidx_user_info.name+'</option>';
            }
        }
        else{
            result = '<option value="0" selected disabled> No record found </option>';
        }
        cboElement.html(result);
    }

    });
}

function Approver(){
	$.ajax({
        url: "add_edit_approver",
        method: "post",
        data: $('#formApprover').serialize(),
        dataType: "json",
        beforeSend: function(){
            $("#iBtnApproverIcon").addClass('spinner-border spinner-border-sm');
            $("#btnApprover").addClass('disabled');
            $("#iBtnApproverIcon").removeClass('fa fa-check');
        },
        success: function(response){
            if(response['validationHasError'] == 1){
                toastr.error('Saving user failed!');

                if(response['error']['approver_employee_no'] === undefined){
                    $("#selectApproverEmployeeNo").removeClass('is-invalid');
                    $("#selectApproverEmployeeNo").attr('title', '');
                }
                else{
                    $("#selectApproverEmployeeNo").addClass('is-invalid');
                    $("#selectApproverEmployeeNo").attr('title', response['error']['approver_employee_no']);
                }

                if(response['error']['classification'] === undefined){
                    $("#selectClassification").removeClass('is-invalid');
                    $("#selectClassification").attr('title', '');
                }
                else{
                    $("#selectClassification").addClass('is-invalid');
                    $("#selectClassification").attr('title', response['error']['classification']);
                }

            }else if(response['hasError'] == 1){
                $("#formApprover")[0].reset();
                $('#modalApprover').modal('hide');
                toastr.success('Succesfully saved!');
                dataTableApprover.draw();
            }else{
                alert('User is already exist!')
            }

            $("#iBtnApproverIcon").removeClass('spinner-border spinner-border-sm');
            $("#btnApprover").removeClass('disabled');
            $("#iBtnApproverIcon").addClass('fa fa-check');
        },
        error: function(data, xhr, status){
            toastr.error('An error occured!\n' + 'Data: ' + data + "\n" + "XHR: " + xhr + "\n" + "Status: " + status);
        }
    });
}

function GetUserApproverByIdToEdit(approverId){
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
        url: "get_user_approver_info_by_id",
        method: "get",
        data: {
            approverId: approverId
        },
        dataType: "json",
        beforeSend: function(){
        },
        success: function(response){
            let approverInfo = response['user_approver_info'];

            if(approverInfo.length > 0){
                $("#selectApproverEmployeeNo").val(approverInfo[0].employee_no).trigger('change');

                if(approverInfo[0].classification == 'Traffic Sr. Supervisor'){
                    $('.show').attr('disabled', false)
                    $('.hide').attr('disabled', true)
                }else{
                    $('.show').attr('disabled', true)
                    $('.hide').attr('disabled', false)
                }
                $("#selectClassification").val(approverInfo[0].classification).trigger('change');
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
