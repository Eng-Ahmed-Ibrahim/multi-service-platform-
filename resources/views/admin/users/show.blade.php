@extends('admin.app')
@section('title', trans('messages.Title'))
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        {{ $user->name }}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a class="text-muted text-hover-primary">{{ __('messages.Users') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ __('messages.Details') }}</li>
                    </ul>
                </div>

            </div>
        </div>
        <div id="kt_app_content_container" class="app-container  container-xxl ">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Sidebar-->
                <div class="flex-column flex-lg-row-auto w-100 w-xl-250px mb-10">

                    <!--begin::Card-->
                    <div class="card mb-5 mb-xl-8">
                        <!--begin::Card body-->
                        <div class="card-body pt-15">
                            <!--begin::Summary-->
                            <div class="d-flex flex-center flex-column mb-5">
                                <!--begin::Avatar-->
                                <div class="symbol symbol-100px symbol-circle mb-7">
                                    <img src="{{ asset($user->image) }}" alt="image" data--h-bstatus="4PROCESSING"
                                        class="hb-hide-temp">
                                </div>
                                <!--end::Avatar-->

                                <!--begin::Name-->
                                <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">
                                    {{ $user->name }} </a>
                                <!--end::Name-->

                                <!--begin::Position-->
                                <div class="fs-5 fw-semibold text-muted mb-6">
                                    {{ __('messages.User') }}
                                </div>
                                <!--end::Position-->

                                <!--begin::Info-->
                                <div class="d-flex flex-wrap flex-center">
                                    <!--begin::Stats-->
                                    <div class="border border-gray-300 border-dashed rounded py-3 px-3 mb-3">
                                        <div class="fs-4 fw-bold text-gray-700">
                                            <span class="w-75px">{{ count($user->requests) }}</span>
                                            <i class="ki-duotone ki-arrow-up fs-3 text-success"><span
                                                    class="path1"></span><span class="path2"></span></i>
                                        </div>
                                        <div class="fw-semibold text-muted">{{ __('messages.Requests') }}</div>
                                    </div>
                                    <!--end::Stats-->
                                </div>
                                <!--end::Info-->
                            </div>
                            <!--end::Summary-->

                            <!--begin::Details toggle-->
                            <div class="d-flex flex-stack fs-4 py-3">
                                <div class="fw-bold rotate collapsible" data-bs-toggle="collapse"
                                    href="#kt_customer_view_details" role="button" aria-expanded="false"
                                    aria-controls="kt_customer_view_details">
                                    Details
                                    <span class="ms-2 rotate-180">
                                        <i class="ki-duotone ki-down fs-3"></i> </span>
                                </div>
                                <!--
           <span data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-original-title="Edit customer details" data-kt-initialized="1">
            <a href="#" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_update_customer">
             Edit
            </a>
           </span> -->
                            </div>
                            <!--end::Details toggle-->

                            <div class="separator separator-dashed my-3"></div>

                            <!--begin::Details content-->
                            <div id="kt_customer_view_details" class="collapse show">
                                <div class="py-5 fs-6">
                                    <!--begin::Badge-->
                                    <div class="badge badge-light-info d-inline">{{ __('messages.User') }}</div>
                                    <!--begin::Badge-->

                                    <!--begin::Details item-->

                                    <div class="fw-bold mt-5">{{ __('messages.Account_ID') }}</div>
                                    <div class="text-gray-600">ID-{{ $user->id }}</div>

                                    <div class="fw-bold mt-5"> {{ __('messages.Email') }}</div>
                                    <div class="text-gray-600">
                                        <a href="mailto:{{ $user->email }}"
                                            class="text-gray-600 text-hover-primary">{{ $user->email }}</a>
                                    </div>

                                    <div class="fw-bold mt-5">{{ __('messages.Phone') }}</div>
                                    <div class="text-gray-600">{{ $user->phone }}</div>
                                </div>
                            </div>
                            <!--end::Details content-->
                        </div>
                        <!--end::Card body-->
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Sidebar-->

                <!--begin::Content-->
                <div class="flex-lg-row-fluid ms-lg-15">
                    <!--begin:::Tabs-->
                    <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8"
                        role="tablist">
                        <!--begin:::Tab item-->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                                href="#kt_customer_view_overview_tab" aria-selected="true"
                                role="tab">{{ __('messages.Requests') }}</a>
                        </li>
                        <!--end:::Tab item-->
                    </ul>
                    <!--end:::Tabs-->

                    <!--begin:::Tab content-->
                    <div class="tab-content" id="myTabContent">
                        <!--begin:::Tab pane-->
                        <div class="tab-pane fade show active" id="kt_customer_view_overview_tab" role="tabpanel">
                            <!--begin::Card-->
                            <div class="card pt-4 mb-6 mb-xl-9">
                                <!--begin::Card header-->
                                <div class="card-header border-0">
                                    <!--begin::Card title-->
                                    <div class="card-title">
                                        <h2>{{ __('messages.Requests') }}</h2>
                                    </div>
                                    <!--end::Card title-->

                                </div>
                                <!--end::Card header-->

                                <!--begin::Card body-->
                                <div class="card-body pt-0 pb-5">
                                    <!--begin::Table-->
                                    <div id="kt_table_customers_payment_wrapper"
                                        class="dt-container dt-bootstrap5 dt-empty-footer">
                                        <div id="" class="table-responsive">
                                            <table class="table align-middle table-row-dashed fs-6 gy-5"
                                                id="kt_ecommerce_sales_table">
                                                <thead>
                                                    <tr class="text-center text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                                        <th class="min-w-100px">#</th>
                                                        <th class="min-w-175px">{{ __('messages.Request_type') }}</th>
                                                        <th class="text-center min-w-100px">{{ __('messages.Price') }}</th>
                                                        <th class="text-center min-w-100px">{{ __('messages.Date') }}</th>
                                                        <th class="text-center min-w-100px">{{ __('messages.Status') }}
                                                        </th>
                                                        <th class="text-center min-w-100px">{{ __('messages.Actions') }}
                                                        </th>

                                                    </tr>
                                                </thead>
                                                <tbody class="fw-semibold text-gray-600">
                                                    @foreach ($user->requests as $request)
                                                        <tr>

                                                            <td class="text-center">
                                                                <a href="">#{{ $request->id }}</a>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ __("messages.$request->type") }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $request->accepted_price > 0 ? $request->accepted_price : $request->current_price }}
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $request->created_at->format('d/m/Y') }}</td>
                                                            <td>
                                                                @if ($request->status == 'pending')
                                                                    <span class="badge badge-light-warning me-2 text-lg">
                                                                        {{ __('messages.Pending') }}
                                                                    </span>
                                                                @elseif($request->status == 'accepted')
                                                                    <span class="badge badge-light-success me-2 text-lg">
                                                                        {{ __('messages.Accepted') }}
                                                                    </span>
                                                                @elseif($request->status == 'completed')
                                                                    <span class="badge badge-light-success me-2 text-lg">
                                                                        {{ __('messages.Completed') }}
                                                                    </span>
                                                                @elseif($request->status == 'canceled')
                                                                    <span class="badge badge-light-danger me-2 text-lg">
                                                                        {{ __('messages.Canceled') }}
                                                                    </span>
                                                                @endif

                                                            </td>

                                                            <td>
                                                                <a href="{{ route('admin.requests.show', $request->id) }}"
                                                                    class="btn btn-success btn-sm" style="width: auto;"><i
                                                                        class="fa fa-eye"></i></a>
                                                            </td>




                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                    <!--end::Table-->
                                </div>
                                <!--end::Card body-->
                            </div>
                            <!--end::Card-->

                        </div>
                        <!--end:::Tab pane-->
                    </div>
                    <!--end:::Tab content-->
                </div>
                <!--end::Content-->
            </div>
            <!--end::Layout-->

        </div>

    @endsection
