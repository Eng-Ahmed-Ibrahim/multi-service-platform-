@extends('admin.app')
@section('title',trans("messages.$section"))
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__("messages.$section")}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary">{{__('messages.Requests')}}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{__("messages.$section")}}</li>
                </ul>
            </div>

        </div>
    </div>
    <div id="kt_app_content_container" class="app-container  container-xxl ">
        <!--begin::Layout-->
        <div class="d-flex flex-column flex-xl-row">
            <!--begin::Sidebar-->
            <div class="flex-column flex-lg-row-auto w-100 mb-10">

                <!--begin::Card-->
                <div class="card mb-5 mb-xl-8">
                    <!--begin::Card body-->
                    <div class="card-body pt-15">
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
                                    @foreach($requests as $request)
                                    <tr>

                                        <td class="text-center">
                                            <a href="{{route('admin.requests.show',$request->id)}}">#{{$request->id}}</a>
                                        </td>

                                        <td class="text-center">{{($request->accepted_price > 0 ? $request->accepted_price : $request->current_price)}}</td>
                                        <td class="text-center">{{ $request->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            
                                            @if($request->status=="pending")
                                            <span class="badge badge-light-warning me-2 text-lg">
                                                {{__("messages.Pending")}}
                                            </span>
                                            @elseif($request->status=="accepted")
                                            <span class="badge badge-light-success me-2 text-lg">
                                                {{__("messages.Accepted")}}
                                            </span>
                                            @elseif($request->status=="completed")
                                            <span class="badge badge-light-success me-2 text-lg">
                                                {{__("messages.Completed")}}
                                            </span>
                                            @elseif($request->status=="cancelled")
                                            <span class="badge badge-light-danger me-2 text-lg">
                                                {{__("messages.Canceled")}}
                                            </span>
                                            @endif

                                        </td>

                                        <td>
                                            <a href="{{route('admin.requests.show',$request->id)}}" class="btn btn-success btn-sm" style="width: auto;"><i class="fa fa-eye"></i></a>
                                        </td>




                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                                                    {{ $requests->links('vendor.pagination.custom') }}

                        </div>
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