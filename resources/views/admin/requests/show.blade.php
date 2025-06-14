@extends('admin.app')
@section('title',trans('messages.Request_details')." - #$request_details->id")
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Request_details')}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary">{{__('messages.Requests')}}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{__('messages.Request_details')}}</li>
                </ul>
            </div>

        </div>
    </div>
    <div id="kt_app_content_container" class="app-container  container-xxl ">
        <!--begin::Layout-->
        <div class="d-flex flex-column flex-xl-row">
            <!--begin::Sidebar-->
            <div class="flex-column flex-lg-row-auto w-100  mb-10">

                <!--begin::Card-->
                <div class="card mb-5 mb-xl-8">
                    <!--begin::Card body-->
                    <div class="card-body pt-15">
                        <!--begin::Layout-->
                        <div class="d-flex flex-column flex-xl-row">
                            <!--begin::Content-->
                            <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                                <!--begin::Invoice 2 content-->
                                <div class="mt-n1">
                                    <!--begin::Top-->
                                    <div class="d-flex flex-stack pb-10">
                                    </div>
                                    <!--end::Top-->
                                    <!--begin::Wrapper-->
                                    <div class="m-0">
                                        <!--begin::Label-->
                                        <div class="fw-bold fs-3 text-gray-800 mb-8">Request #{{$request_details->id}}</div>
                                        <!--end::Label-->
                                        <!--begin::Row-->
                                        <div class="row g-5 mb-11">
                                            <!--end::Col-->
                                            <div class="col-sm-6">
                                                <!--end::Label-->
                                                <div class="fw-bold fs-7 text-gray-800 mb-1">Date:</div>
                                                <!--end::Label-->
                                                <!--end::Col-->
                                                <div class="fw-semibold fs-7 text-gray-600">{{ $request_details->created_at->format('Y-m-d H:i:s') }}</div>
                                                <!--end::Col-->
                                            </div>
                                            <!--end::Col-->
                                            <!--end::Col-->
                                            <div class="col-sm-6">
                                                <!--end::Label-->
                                                <div class="fw-bold fs-7 text-gray-800 mb-1">{{__("messages.Status")}}:</div>
                                                <!--end::Label-->
                                                <!--end::Info-->
                                                <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                                    @if($request_details->status=="pending")
                                                    <span class="badge badge-light-warning me-2 text-lg">
                                                        {{__("messages.Pending")}}
                                                    </span>
                                                    @elseif($request_details->status=="accepted")
                                                    <span class="badge badge-light-success me-2 text-lg">
                                                        {{__("messages.Accepted")}}
                                                    </span>
                                                    @elseif($request_details->status=="completed")
                                                    <span class="badge badge-light-success me-2 text-lg">
                                                        {{__("messages.Completed")}}
                                                    </span>
                                                    @elseif($request_details->status=="canceled")
                                                    <span class="badge badge-light-danger me-2 text-lg">
                                                        {{__("messages.Canceled")}}
                                                    </span>
                                                    @endif
                                                </div>
                                                <!--end::Info-->
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                        <!--begin::Row-->
                                        <div class="row g-5 mb-12">
                                            <!--end::Col-->
                                            <div class="col-sm-6">
                                                <!--end::Label-->
                                                <div class="fw-bold fs-7 text-gray-800 mb-1">User Details:</div>
                                                <!--end::Label-->
                                                <!--end::Text-->
                                                <div class="fw-semibold fs-6 text-gray-600"> </div>
                                                <!--end::Text-->
                                                <!--end::Description-->
                                                <div class="fw-semibold fs-7 text-gray-600"></div>
                                                <div class="fw-semibold fs-7 text-gray-600"> {{$request_details->user->phone}}</div>
                                                <div class="fw-semibold fs-7 text-gray-600">User Rating:- 0</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Col-->
                                            <!--end::Col-->
                                            <div class="col-sm-6">
                                                <!--end::Label-->
                                                <div class="fw-bold fs-7 text-gray-800 mb-1">Driver Details:</div>

                                                <div class="fw-semibold fs-7 text-gray-600"> </div>
                                                <div class="fw-semibold fs-7 text-gray-600"></div>
                                                <div class="fw-semibold fs-7 text-gray-600"></div>
                                                <div class="fw-semibold fs-7 text-gray-600">Driver Rating:- 0</div>
                                                <!--end::Description-->
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Row-->
                                        <!--begin::Content-->
                                        <div class="flex-grow-1">
                                            <!--begin::Table-->
                                            <div class="table-responsive border-bottom mb-9">
                                                <table class="table mb-3">
                                                    <thead>
                                                        <tr class="border-bottom fs-6 fw-bold text-muted">
                                                            <th class="text-center pb-2">Payment Mode</th>
                                                            <!-- <th class="min-w-70px text-center pb-2">Start Time</th>
                                                            <th class="min-w-80px text-center pb-2">Finished At</th> -->
                                                            <th class="min-w-70px text-center pb-2">Payment Method</th>
                                                            <th class="min-w-80px text-center pb-2">Paid </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="fw-bold text-gray-700 fs-5 text-center">
                                                            <td class="d-flex align-items-center  text-center pt-6" style="justify-content: center;">
                                                            @if($request_details->status=="pending")
                                            <span class="badge badge-light-warning me-2 text-lg">
                                                {{__("messages.Pending")}}
                                            </span>
                                            @elseif($request_details->status=="accepted")
                                            <span class="badge badge-light-success me-2 text-lg">
                                                {{__("messages.Accepted")}}
                                            </span>
                                            @elseif($request_details->status=="completed")
                                            <span class="badge badge-light-success me-2 text-lg">
                                                {{__("messages.Completed")}}
                                            </span>
                                            @elseif($request_details->status=="canceled")
                                            <span class="badge badge-light-danger me-2 text-lg">
                                                {{__("messages.Canceled")}}
                                            </span>
                                            @endif
                                                            </td>
                                                            <td class="pt-6">Cash</td>
                                                            <td class="pt-6">{{($request_details->accepted_price > 0 ? $request_details->accepted_price : $request_details->current_price)}}</td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <!--end::Content-->
                                    </div>
                                    <!--end::Wrapper-->
                                </div>
                                <!--end::Invoice 2 content-->
                            </div>

                        </div>
                        <!--end::Layout-->
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->

            </div>
            <!--end::Sidebar-->
        </div>
        <!--end::Layout-->

        <!--begin::Modals-->

    </div>

    @endsection