@extends('layouts.admin_layout')

@section('title', 'Dashboard')
@section('content_page')
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <h2 class="my-3">Dashboard</h2>
                    <div class="row">
                        <div class="col-12">
                            <div class="small-box bg-secondary shadow">
                                <a href="{{ route('user_management') }}">
                                    <div class="inner" style="height:100px;">
                                        <span class="info-box-text position-absolute mt-4 ml-3"><h4><strong>User Management</strong></h4></span>
                                        <div class="icon">
                                            <i class="fas fa-users mr-3"></i>                                            
                                        </div>   
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="small-box bg-secondary shadow">
                                <a href="{{ route('forklift_request') }}">
                                    <div class="inner" style="height:100px;">
                                        <span class="info-box-text position-absolute mt-4 ml-3"><h4><strong>Forklift Request</strong></h4></span>
                                        <div class="icon">
                                            <i class="fa-solid fas fa-cart-flatbed-suitcase mr-3"></i>                                            
                                        </div>   
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="small-box bg-secondary shadow">
                                <a href="{{ route('export_report') }}">
                                    <div class="inner" style="height:100px;">
                                        <span class="info-box-text position-absolute mt-4 ml-3"><h4><strong>Report</strong></h4></span>
                                        <div class="icon">
                                            <i class="fa-solid fas fa-solid fa fa-file-excel mr-4"></i>                                            
                                        </div>   
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection

<!-- JS CONTENT -->
@section('js_content')
    <script type="text/javascript">
    </script>
@endsection
