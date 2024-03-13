@extends('layouts.admin_layout')

@section('title', 'Dashboard')

@section('content_page')
    @php
        date_default_timezone_set('Asia/Manila');
    @endphp
    <div class="content-wrapper">
        <section class="content p-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Export Forklift Request</h5>
                            </div>
                            <div class="card-body">
                                @if(session()->has('message'))
                                    <div class="alert alert-danger">
                                        <strong>{{ session()->get('message') }}</strong>
                                        {{-- <a href="{{ session()->get('message') }}">{{ session()->get('message') }}</a> --}}
                                    </div>
                                @endif
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100">From:</span>
                                    </div>
                                    <input type="date" class="form-control" name="from" id="txtSearchFrom" max="<?= date('Y-m-d'); ?>">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend w-50">
                                        <span class="input-group-text w-100">To:</span>
                                    </div>
                                    <input type="date" class="form-control" name="to" id="txtSearchTo" max="<?= date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-dark float-right" id="btnExportForkliftRequest"><i class="fas fa-file-excel"></i> Export</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

<!-- JS CONTENT --}} -->
@section('js_content')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#btnExportForkliftRequest').on('click', function(){
                let from = $('#txtSearchFrom').val();
                let to = $('#txtSearchTo').val();

                if(from == ''){
                    console.log('from',from)
                    alert('Select Date From');
                }else if(to == ''){
                    console.log('to',to)
                    alert('Select Date To');
                }else{
                    window.location.href = `export/${from}/${to}`;
                    console.log('export')
                    $('.alert').remove();
                }
            });
        });
    </script>
@endsection

