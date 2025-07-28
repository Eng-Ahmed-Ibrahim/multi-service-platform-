@extends('admin.app')
@section('title',__('messages.Details'))
@section('css')
<style>
    .attachment-style {
        height: 160px;
        width: 160px;
    }

    html {
        box-sizing: border-box;
    }

    *,
    *::before,
    *::after {
        margin: 0;
        padding: 0;
        box-sizing: inherit;
    }






    /* Popup Styling */
    .img-popup {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: rgba(255, 255, 255, .5);
        display: flex;
        justify-content: center;
        align-items: center;
        display: none;
        z-index: 9999999999999999;
    }

    .img-popup img {
        max-width: 900px;
        width: 100%;
        opacity: 0;
        transform: translateY(-100px);
        -webkit-transform: translateY(-100px);
        -moz-transform: translateY(-100px);
        -ms-transform: translateY(-100px);
        -o-transform: translateY(-100px);
    }

    .close-btn {
        width: 35px;
        height: 30px;
        display: flex;
        justify-content: center;
        flex-direction: column;
        position: absolute;
        top: 20px;
        right: 20px;
        cursor: pointer;
    }

    .close-btn .bar {
        height: 4px;
        background: #333;
    }

    .close-btn .bar:nth-child(1) {
        transform: rotate(45deg);
    }

    .close-btn .bar:nth-child(2) {
        transform: translateY(-4px) rotate(-45deg);
    }

    .opened {
        display: flex;
    }

    .opened img {
        animation: animatepopup 1s ease-in-out .8s;
        -webkit-animation: animatepopup .3s ease-in-out forwards;
    }

    @keyframes animatepopup {

        to {
            opacity: 1;
            transform: translateY(0);
            -webkit-transform: translateY(0);
            -moz-transform: translateY(0);
            -ms-transform: translateY(0);
            -o-transform: translateY(0);
        }

    }

    @media screen and (max-width: 880px) {

        .container .container__img-holder:nth-child(3n+1) {
            margin-left: 16px;
        }

    }
</style>
@endsection
@section('content')
<div class="d-flex flex-column flex-column-fluid">




    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.handymans')}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="../../demo1/dist/index.html" class="text-muted text-hover-primary">{{__('messages.Details')}}</a>
                    </li>

                    <li class="breadcrumb-item text-muted">{{__('messages.handyman')}}</li>
                </ul>
            </div>

        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" style="min-height: 70vh;" class="app-container container-xxl">
            <div class="d-flex flex-column flex-lg-row">
                <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">

                    <div class="card mb-5 mb-xl-8">
                        <div class="card-body">


                            <div class="d-flex flex-center flex-column py-5">
                                <div class="symbol symbol-100px symbol-circle mb-7">
                                    <img src="{{asset($driver->image)}}" alt="image" data--h-bstatus="4PROCESSING" class="hb-hide-temp">
                                </div>

                                <a class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">
                                    {{$driver->name}} </a>

                                <div class="mb-9">
                                    <div class="badge badge-lg badge-light-primary d-inline">{{__("messages.Driver")}}</div>
                                </div>

                                <div class="d-flex flex-wrap flex-center">
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-75px">{{$driver->balance}}</span>
                                            <i class="ki-duotone ki-arrow-up fs-3 text-success"><span class="path1"></span><span class="path2"></span></i>
                                        </div>
                                        <div class="fw-semibold text-muted">@lang('messages.Balance')</div>
                                    </div>

                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mx-4 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-50px">{{count($driver->requests)}}</span>
                                            <i class="ki-duotone ki-arrow-down fs-3 text-danger"><span class="path1"></span><span class="path2"></span></i>
                                        </div>
                                        <div class="fw-semibold text-muted">@lang('messages.Total_requests') </div>
                                    </div>


                                </div>
                            </div>

                            <div class="d-flex flex-stack fs-4 py-3">
                                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details" role="button" aria-expanded="false" aria-controls="kt_user_view_details">
                                    {{ __('messages.Details') }}
                                    <span class="ms-2 rotate-180">
                                        <i class="ki-duotone ki-down fs-3"></i> </span>
                                </div>

                            </div>

                            <div class="separator"></div>

                            <div id="kt_user_view_details" class="collapse show">
                                <div class="pb-5 fs-6">
                                    <div class="fw-bold mt-5"> {{__("messages.Account_ID")}}</div>
                                    <div class="text-gray-600">ID-{{$driver->id}}</div>
                                    <div class="fw-bold mt-5">{{__("messages.Phone")}}</div>
                                    <div class="text-gray-600"><a class="text-gray-600 text-hover-primary">{{$driver->phone}}</a></div>
                                    <div class="fw-bold mt-5">{{__("messages.Address")}}</div>
                                    <div class="text-gray-600">{{$driver->address}}</div>
                                    <div class="fw-bold mt-5">{{__("messages.Gender")}}</div>
                                    <div class="text-gray-600">{{$driver->gender}}</div>
                                    <div class="fw-bold mt-5">{{__("messages.Date_of_birth")}}</div>
                                    <div class="text-gray-600">{{$driver->date_of_birth}}</div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="flex-lg-row-fluid ms-lg-15">
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-4 {{session('current_process_number') ==0 || session('current_process_number') ==2  ?'active' :' ' }}" data-bs-toggle="tab" href="#official_documents" aria-selected="true" role="tab">{{__("messages.official_documents")}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#services" aria-selected="true" role="tab">{{__("messages.Services")}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#requests" aria-selected="true" role="tab">{{__("messages.Requests")}}</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-4 " data-bs-toggle="tab" href="#Transcations" aria-selected="true" role="tab">{{__("messages.Transcations")}}</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane fade {{session('current_process_number') ==0 || session('current_process_number') ==2  ?'active show' :' ' }}  " id="official_documents" role="tabpanel">
                            @php
                            $process_number = 2;
                            $process = $driver->processes->firstWhere('process_number', $process_number);
                            $status = $process ? $process->status : null;
                            $rejection_reason = $status === 'rejected' ? $process->rejection_reason : null;
                            @endphp
                            @if($status === 'rejected')
                            <div class="notice d-flex bg-light-danger rounded border-danger border border-dashed  p-6 mb-3">
                                <i class="ki-duotone ki-information fs-2tx text-danger me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> <!--end::Icon-->
                                <div class="d-flex flex-stack flex-grow-1 ">
                                    <div class=" fw-semibold">
                                        <h4 class="text-gray-900 fw-bold">{{__("messages.Rejected_reason")}}</h4>

                                        <div class="fs-6 text-gray-700 ">{{$rejection_reason}}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($status === 'accepted')
                            <div class="notice d-flex bg-light-success rounded border-success border border-dashed  p-6 mb-3">
                                <i class="ki-duotone ki-information fs-2tx text-success me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i> <!--end::Icon-->
                                <div class="d-flex flex-stack flex-grow-1 ">
                                    <div class=" fw-semibold">
                                        <h4 class="text-gray-900 fw-bold">{{__("messages.Accepted")}}</h4>

                                        <div class="fs-6 text-gray-700 ">{{__("messages.This_section_has_been_accepted_by_admin")}}</div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <div class="card-header border-0">
                                    <div class="card-title">
                                        <h2>{{__("messages.Details")}}</h2>
                                    </div>

                                </div>

                                <div class="card-body pt-0 pb-5">
                                    <div id="kt_table_customers_payment_wrapper" class="dt-container dt-bootstrap5 dt-empty-footer">
                                        <div class="row mb-7">
                                            <label class="col-lg-4 fw-semibold text-muted">{{__("messages.National_ID_Number")}}</label>

                                            <div class="col-lg-8">
                                                <span class="fw-bold fs-6 text-gray-800">{{$driver->id_number}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card pt-4 mb-6 mb-xl-9">
                                <div class="card-header border-0">
                                    <div class="card-title">
                                        <h2 class="fw-bold mb-0"> {{__("messages.Documents")}} </h2>
                                    </div>

                                </div>

                                <div id="kt_customer_view_payment_method" class="card-body pt-0">

                                    @foreach($groupedDocuments['2'] as $document)
                                    <div class="py-0" data-kt-customer-payment-method="row">
                                        <div class="py-3 d-flex flex-stack flex-wrap">
                                            <div class="d-flex align-items-center collapsible rotate collapsed" data-bs-toggle="collapse" href="#{{$document->id}}" role="button" aria-expanded="false" aria-controls="{{$document->id}}">
                                                <div class="me-3 rotate-90"><i class="ki-duotone ki-right fs-3"></i></div>


                                                <div class="me-3">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{asset($document->attachment)}}" style="max-height:180px;width:auto" class="w-40px me-3 hb-hide-temp" alt="" data--h-bstatus="0OBSERVED">
                                                        <div class="text-gray-800 fw-bold">
                                                            {{__("messages.$document->name")}}
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex my-3 ms-9">

                                                <!-- <a  class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" data-bs-toggle="tooltip" data-kt-customer-payment-method="delete" aria-label="Delete" data-bs-original-title="Delete" data-kt-initialized="1">
                                                    <i class="ki-duotone ki-eye fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i> </a> -->

                                                <a href="{{asset($document->attachment)}}" download="" class="btn btn-icon btn-active-light-primary w-30px h-30px me-3" data-bs-toggle="tooltip" data-kt-customer-payment-method="delete" aria-label="Delete" data-bs-original-title="Delete" data-kt-initialized="1">
                                                    <i class="ki-duotone ki-cloud-download">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                    </i>

                                                </a>


                                            </div>
                                        </div>

                                        <div id="{{$document->id}}" class="fs-6 ps-10 collapse" data-bs-parent="#kt_customer_view_payment_method" style="">
                                            <div class="d-flex flex-wrap py-5">
                                                <img src="{{asset($document->attachment)}}" style="max-height:180px;width:auto" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach


                                </div>
                            </div>

                            <div style="display: flex; align-items:center;justify-content:center" class="my-2 col-12">


                                @if($status === 'rejected' || $status =="pending")
                                <form class="mx-1" method="post" action="{{ route('admin.drivers.change_process_status') }}">
                                    @csrf
                                    <input type="hidden" name="process_number" value="{{ $process_number }}">
                                    <input type="hidden" name="driver_id" value="{{ $driver->id }}">
                                    <input type="hidden" name="process_number" value="{{ $process_number }}">
                                    <input type="hidden" name="status" value="accepted">
                                    <button type="submit" class="btn btn-success btn-sm" title="Validate Document">
                                        <i class="fa fa-check"></i>&nbsp;{{ __('messages.Approve') }}
                                    </button>
                                </form>
                                @endif

                                @if($status === 'accepted' || $status =="pending")
                                <button data-bs-toggle="modal" data-bs-target="#rejected-reson" onclick="setProcessNumber({{$process_number}})" class="btn btn-danger btn-sm" title="Validate Document">
                                    <span style="margin-right: 10px;">&#10006;</span>{{ __('messages.Reject') }}
                                </button>
                                @endif


                            </div>

                        </div>
                        <div class="tab-pane fade " id="services" role="tabpanel">
                            <div id="" class="table-responsive">

                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
                                    <thead>
                                        <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-100px">#</th>
                                            <th class="min-w-175px">{{__("messages.Service_name")}}</th>
                                            <th class=" min-w-100px">{{__("messages.Image")}}</th>

                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        @foreach($driver->services as $key=>$service)
                                        <tr>


                                            <td class="text-center">
                                                <span class="fw-bold">#-{{$key+1}}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold">{{session("lang")? $service['service']['name'] :$service['service']['name_ar'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <img src="{{asset($service['service']['image'])}}" style="max-height: 70px;" alt="">
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade  " id="requests" role="tabpanel">
                            <div id="" class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
                                    <thead>
                                        <tr class="text-center text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-100px">#</th>
                                            <th class="text-center min-w-100px">{{__("messages.Price")}}</th>
                                            <th class="text-center min-w-100px">{{__("messages.Date")}}</th>
                                            <th class="text-center min-w-100px">{{__("messages.Status")}}</th>
                                            <th class="text-center min-w-100px">{{__("messages.Actions")}}</th>

                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        @if(count($driver->requests) > 0)
                                        @foreach($driver->requests as $request)
                                        <tr>

                                            <td class="text-center">
                                                <a href="">#{{$request['id']}}</a>
                                            </td>

                                            <td class="text-center">{{($request['accepted_price'] > 0 ? $request['accepted_price'] : $request['current_price'])}}</td>
                                            <td class="text-center">{{ $request['created_at']->format('d/m/Y') }}</td>
                                            <td>
                                                @if($request['status']=="pending")
                                                <span class="badge badge-light-warning me-2 text-lg">
                                                    {{__("messages.Pending")}}
                                                </span>
                                                @elseif($request['status']=="accepted")
                                                <span class="badge badge-light-success me-2 text-lg">
                                                    {{__("messages.Accepted")}}
                                                </span>
                                                @elseif($request['status']=="completed")
                                                <span class="badge badge-light-success me-2 text-lg">
                                                    {{__("messages.Completed")}}
                                                </span>
                                                @elseif($request['status']=="canceled")
                                                <span class="badge badge-light-danger me-2 text-lg">
                                                    {{__("messages.Canceled")}}
                                                </span>
                                                @endif

                                            </td>

                                            <td>
                                                <a href="{{route('admin.requests.show',$request['id'])}}" class="btn btn-success btn-sm" style="width: auto;"><i class="fa fa-eye"></i></a>
                                            </td>




                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <span>No Data</span>
                                            </td>
                                        </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="tab-pane fade  " id="Transcations" role="tabpanel">
                            <div id="" class="table-responsive">
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
                                    <thead>
                                        <tr class="text-center text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                            <th class="min-w-100px">#</th>
                                            <th class="text-center min-w-100px">{{__("messages.Amount")}}</th>
                                            <th class="text-center min-w-100px">{{__("messages.Type")}}</th>
                                            <th class="text-center min-w-100px">{{__("messages.Date")}}</th>

                                        </tr>
                                    </thead>
                                    <tbody class="fw-semibold text-gray-600">
                                        @if(count($driver->walletTransactions) > 0)
                                        @foreach($driver->walletTransactions as $transaction)
                                        <tr>

                                            <td class="text-center">
                                                <a href="">#{{$transaction['id']}}</a>
                                            </td>
                                            <td>
                                                {{$transaction['amount']}}
                                            </td>
                                            <td>{{ __("messages." . $transaction['type']) }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($transaction['created_at'])->format('d/m/Y h:i A') }}

                                            </td>


                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <span>No Data</span>
                                            </td>
                                        </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<div class="img-popup">
    <img src="" id="pop-image" alt="Popup Image" style="    height: 400px;width: auto;">
    <div class="close-btn">
        <div class="bar"></div>
        <div class="bar"></div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="rejected-reson" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{__("messages.Rejected_reason")}}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="mx-1" method="post" action="{{ route('admin.drivers.change_process_status') }}">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="driver_id" value="{{ $driver->id }}">
                    <input type="hidden" name="process_number" id="proccess_id">
                    <input type="hidden" name="status" value="rejected">
                    <div class="form-floating">
                        <textarea class="form-control" name="rejection_reason" style="height: 100px;" placeholder="{{__('messages.Rejected_reason')}}" id="floatingTextarea"></textarea>
                        <label for="floatingTextarea">{{__("messages.Rejected_reason")}}</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__("messages.Close")}}</button>
                    <button type="submit" class="btn btn-primary">{{__("messages.Confirm")}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section("js")
<script>
    function setProcessNumber(processNumber) {
        document.getElementById('proccess_id').value = processNumber;
    }

    function setStatus(inputId, value) {
        // Set the hidden status input field based on the button clicked
        document.getElementById(inputId).value = value;
    }

    function openPop(image) {

        document.getElementById("pop-image").src = image
        document.querySelector('.img-popup').style.display = "flex"
    }
    $(document).ready(function() {

        // required elements
        var imgPopup = $('.img-popup');
        var imgCont = $('.container__img-holder');
        var popupImage = $('.img-popup img');
        var closeBtn = $('.close-btn');

        // handle events
        imgCont.on('click', function() {
            var img_src = $(this).children('img').attr('src');
            imgPopup.children('img').attr('src', img_src);
            imgPopup.addClass('opened');
        });

        $(imgPopup, closeBtn).on('click', function() {
            imgPopup.removeClass('opened');
            imgPopup.children('img').attr('src', '');
        });

        popupImage.on('click', function(e) {
            e.stopPropagation();
        });

    });
</script>
@endsection