@extends('admin.app')
@section('title',trans("messages.Notifications"))
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__("messages.Notifications")}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary">{{__('messages.Home')}}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{__("messages.Notifications")}}</li>
                </ul>
            </div>
            @can('add notifications')
            <div class="d-flex align-items-center gap-2 gap-lg-3">


                <a class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_user">{{__('messages.Push_notification')}}</a>
            </div>
            @endcan
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card">
                <div class="card-body p-lg-17">
                <div id="" class="table-responsive">

                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
                        <thead>
                            <tr class=" text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-100px">#</th>
                                <th class="min-w-175px">{{__('messages.Subject')}}</th>
                                <th class="min-w-175px">{{__('messages.Message')}}</th>
                                <th class="min-w-175px">{{__('messages.To')}}</th>

                                @if(Auth::guard("admin")->user()->can('edit notifications') || Auth::guard("admin")->user()->can('delete notifications'))
                                <th class=" min-w-100px">{{__("messages.Actions")}}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-gray-600">
                            @foreach($notifications as $key=>$notification)
                            <tr>
                                <td data-kt-ecommerce-order-filter="order_id">{{++$key}}</td>

                                <td class="">
                                    <span class="fw-bold">{{$notification->subject}}</span>
                                </td>

                                <td class="">
                                    <span class="fw-bold">{{$notification->message}}</span>
                                </td>
                                <td class="">
                                    <span class="fw-bold">{{__("messages.$notification->to")}}</span>
                                </td>

                                @if(Auth::guard("admin")->user()->can('edit notifications') || Auth::guard("admin")->user()->can('delete notifications'))

                                <td class="">
                                    <a class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                                        {{__("messages.Actions")}}
                                        <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                    <!--begin::Menu-->
                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4" data-kt-menu="true">
                                        @can('edit notifications')
                                        <div class="menu-item px-3">
                                            <a data-bs-toggle="modal"
                                                onclick="editData('{{$notification->id}}','{{$notification->subject}}','{{$notification->message}}','{{$notification->to}}'"
                                                data-bs-target="#kt_modal_edit_user" class="menu-link px-3">{{__("messages.Edit")}}</a>
                                        </div>
                                        @endcan
                                        @can('delete notifications')
                                        <div class="menu-item px-3">
                                            <form action="{{route('admin.notifications.delete',$notification->id)}}" id="delete-form" method="post">
                                                @csrf
                                                <button type="button" onclick="if(confirm('{{__('messages.Are_you_sure')}}')) document.getElementById('delete-form').submit()" class="menu-link px-3 btn " data-kt-ecommerce-order-filter="delete_row">{{__("messages.Delete")}}</button>
                                            </form>
                                        </div>
                                        @endcan
                                        <!--end::Menu item-->
                                    </div>
                                    <!--end::Menu-->
                                </td>
                                @endif
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>


                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="kt_modal_add_user" tabindex="-1" style="display: none;" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <!--begin::Modal content-->
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_user_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">{{__("messages.Push_notification")}}</h2>

                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_add_user_form" class="form form-ajax fv-plugins-bootstrap5 fv-plugins-framework" action="{{route('admin.notifications.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px" style="">

                            <div class="row">

                                <div class="col-lg-12 col-sm-12 mb-2 fv-row mb-7 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">{{__("messages.Subject")}}</label>
                                    <input type="text" name="subject" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{__('messages.Subject')}}" value="" required="">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                <div class="col-lg-12 col-sm-12 mb-2 fv-row mb-7 fv-plugins-icon-container">
                                    <div class="form-floating">
                                        <textarea class="form-control" name="message" placeholder="{{__('messages.Message')}}" id="floatingTextarea"  style="height: 100px"></textarea>
                                        <label for="floatingTextarea">{{__('messages.Message')}}</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-5">
                                    <select class="form-select" name="to" aria-label="Default select example">
                                        <option selected>{{__("messages.To")}}</option>
                                        <option value="all">{{__("messages.all")}}</option>
                                        <option value="handyman">{{__("messages.handyman")}}</option>
                                        <option value="driver">{{__("messages.driver")}}</option>
                                        <option value="user">{{__("messages.user")}}</option>
                                    </select>
                                </div>

                            </div>


                        </div>
                        <div class="text-center pt-15">
                            <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">{{__("messages.Retreat")}}</button>
                            <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                <span class="indicator-label">{{__("messages.Save")}}</span>
                                <span class="indicator-progress">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="kt_modal_edit_user" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h2 class="fw-bold">{{__("messages.Edit_car_type")}}</h2>

                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                        <span class="svg-icon svg-icon-1">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1" transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                    <!--begin::Form-->
                    <form id="kt_modal_add_user_form" class="form form-ajax fv-plugins-bootstrap5 fv-plugins-framework" action="{{route('admin.notifications.update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden name="notification_id" id="edit-notification-id">
                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px" style="">

                            <div class="row">

                                <div class="col-lg-6 col-sm-12 mb-2 fv-row mb-7 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">{{__('messages.Subject')}}</label>
                                    <input type="text" id="edit-subject" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{__('messages.Subject')}}" value="" required="">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                <div class="col-lg-12 col-sm-12 mb-2 fv-row mb-7 fv-plugins-icon-container">
                                    <div class="form-floating">
                                        <textarea   id="edit-message" class="form-control" name="message" placeholder="{{__('messages.Message')}}" id="floatingTextarea"  style="height: 100px"></textarea>
                                        <label for="floatingTextarea">{{__('messages.Message')}}</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-5">
                                    <select id="edit-to" class="form-select" name="to" aria-label="Default select example">
                                        <option selected>{{__("messages.To")}}</option>
                                        <option value="all">{{__("messages.all")}}</option>
                                        <option value="handyman">{{__("messages.handyman")}}</option>
                                        <option value="driver">{{__("messages.driver")}}</option>
                                        <option value="user">{{__("messages.user")}}</option>
                                    </select>
                                </div>

                            </div>


                        </div>
                        <div class="text-center pt-15">
                            <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">{{__("messages.Retreat")}}</button>
                            <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                <span class="indicator-label">{{__("messages.Save")}}</span>
                                <span class="indicator-progress">Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    @endsection
    @section("js")
    <script>
        function editData(id, subject, message, to) {
            document.getElementById("edit-notification-id").value = id;
            document.getElementById("edit-subject").value = subject;
            document.getElementById("edit-message").value = message;
            document.getElementById("edit-to").value = to;
        }
    </script>
    @endsection