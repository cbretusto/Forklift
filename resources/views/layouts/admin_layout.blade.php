@php
    session_start();
    $isLogin = false;
    if(isset($_SESSION['rapidx_user_id'])){
        $isLogin = true;
    }
@endphp

@if($isLogin)
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title>Forklift Request System| @yield('title')</title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="shortcut icon" type="image/png" href="">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <!-- CSS LINKS -->
            @include('shared.css_links.css_links')
            <style>
                .modal-xl-custom{
                    width: 95% !important;
                    min-width: 90% !important;
                }
            </style>
        </head>
        <body class="hold-transition sidebar-mini">
            <input type="hidden" id="login_employee_number" value="<?php echo $_SESSION["rapidx_employee_number"]; ?>">
            <div class="wrapper">
                    @include('shared.pages.header')
                    @include('shared.pages.admin_nav')
                    @include('shared.pages.footer')
                @yield('content_page')
            </div>

            <!-- JS LINKS -->
            @include('shared.js_links.js_links')
            @yield('js_content')
            <script type="text/javascript">
                $(document).ready(function(){
                    
                });
            </script>
        </body>
        <script>
            verifyUser();
            function verifyUser(){
                var loginEmployeeNumber = $('#login_employee_number').val();
                console.log('Session(Admin/User):', loginEmployeeNumber);

                $.ajax({
                    url: "get_user_log",
                    method: "get",
                    data: {
                        loginEmployeeNumber : loginEmployeeNumber
                    },
                    dataType: "json",

                    success: function(response){
                        if(response['result'].length > 0){
                            for(let i = 0; i<response['result'].length;i++){
                                if(response['result'][i].rapidx_user_info.department_id == "20" || response['result'][i].rapidx_user_info.department_id == "1"){
                                    $('#userList').removeClass('d-none');
                                }
                            }
                        }
                        // else{
                        //     window.location.href = 'error';
                        // }
                    }
                });
            }
        </script>
    </html>
@else
    <script type="text/javascript">
        window.location = "../RapidX/";
    </script>
@endif
    
