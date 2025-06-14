@extends('admin.app')
@section('title',trans('messages.Edit_driver'))
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.Edit_driver')}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary">{{__('messages.Drivers')}}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{__('messages.Edit_driver')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header mt-6">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1 me-5">
                            <h1>{{$driver->name}}</h1>
                        </div>
                        <!--end::Search-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">

                        <!--end::Button-->
                    </div>
                    <!--end::Card toolbar-->
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0" id="kt_modal_add_user">
                    <!--begin::Form-->
                    <form action="{{route('admin.drivers.update')}}" class="form" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_update_role_header" data-kt-scroll-wrappers="#kt_modal_update_role_scroll" data-kt-scroll-offset="300px" style="">

                            <div class="fv-row mb-7">
                                <label class="d-block fw-semibold fs-6 mb-5">{{__("messages.Avatar")}}</label>
                                <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                                    <div class="image-input-wrapper w-125px h-125px" style="background-image: url({{asset($driver->image)}});"></div>

                                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar" data-bs-original-title="Change avatar" data-kt-initialized="1">
                                        <i class="bi bi-pencil-fill fs-7"></i>
                                        <input type="file" name="avatar" accept=".png, .jpg, .jpeg">
                                        <input type="hidden" name="avatar_remove">
                                    </label>

                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar" data-bs-original-title="Cancel avatar" data-kt-initialized="1">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow" data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar" data-bs-original-title="Remove avatar" data-kt-initialized="1">
                                        <i class="bi bi-x fs-2"></i>
                                    </span>
                                </div>

                                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                            </div>
                            <!--end::Input group-->
                            <div class="fv-row mb-7 fv-plugins-icon-container">
                                <label class="required fw-semibold fs-6 mb-2">{{__("messages.Full_name")}}</label>
                                <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{__('messages.Full_name')}}" value="{{$driver->name}}" required="">
                                <div class="fv-plugins-message-container invalid-feedback"></div>
                            </div>
                            <!--begin::Input group-->
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class="required fw-semibold fs-6 mb-2">{{__("messages.Email")}}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0" value="{{$driver->email}}" required="">
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">{{__("messages.Phone")}}</label>
                                <div class="iti iti--allow-dropdown iti--separate-dial-code">
                                    <input type="tel" id="phone" name="phone" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="01XXXXXXXXX" value="{{$driver->phone}}" required="" autocomplete="off" data-intl-tel-input-id="0">
                                </div>
                            </div>

                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class=" fw-semibold fs-6 mb-2">{{__("messages.Password")}}</label>
                                <!--end::Label-->
                                <!--begin::Input-->
                                <input type="password" name="password" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="********" value="">
                                <!--end::Input-->
                            </div>
                            <div class="fv-row mb-7">
                                <!--begin::Label-->
                                <label class=" fw-semibold fs-6 mb-2"> {{__("messages.Password_confirmation")}}</label>
                                <!--end::Label-->
                                <input type="password" name="password_confirmation" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="********" value="">
                                <!--end::Input-->
                            </div>
                            <!--end::Input group-->



                        </div>
                        <input type="hidden" name="driver_id" value="{{$driver->id}}" required="">

                        <!--end::Scroll-->
                        <!--begin::Actions-->
                        <div class="text-center pt-15 pb-15">
                            <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                <span class="indicator-label">{{__("messages.Update")}}</span>
                            </button>
                        </div>
                        <!--end::Actions-->
                    </form>

                    <div class="w-full rounded-lg shadow-xs bg-white mb-5">
                        <div class="w-full px-5 py-5">
                            <h4 class="mb-4 font-semibold text-gray-800">
                                {{__('messages.Documents')}}
                            </h4>


                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table align-middle table-row-dashed fs-6 gy-5" id="users-table">
                                    <!--begin::Table head-->
                                    <thead>
                                        <!--begin::Table row-->
                                        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                            <th class="text-center ">#</th>
                                            <th class="text-center">{{__("messages.Type")}}</th>
                                            <th class="text-center">{{__('messages.Image')}}</th>
                                        </tr>
                                        <!--end::Table row-->
                                    </thead>
                                    <!--end::Table head-->
                                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

                                        @if(count($driver->documents) >0)
                                        @foreach($driver->documents as $key =>$value)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>{{__("messages.$value->key")}}</td>
                                            <td><img src="{{asset($value->attachment)}}" style="height:100px" alt=""></td>
                                        </tr>
                                        @endforeach
                                        <tr>

                                            <td class="text-center dark:text-gray-400 dark:bg-gray-800 py-3 text-sm" colspan="10">
                                                @if($driver->status=="pending")
                                                <form style="display: inline;" action="{{route('admin.drivers.accept_driver',$driver->id)}}" method="post">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="Validate Document">
                                                        <i class="fa fa-check"></i>&nbsp;{{__('messages.Approve_driver')}}
                                                    </button>
                                                </form>
                                                <form style="display: inline;" action="{{route('admin.drivers.reject_driver',$driver->id)}}" method="post">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Validate Document">
                                                        <span style="margin-right: 10px;">&#10006;</span>{{__('messages.Reject_driver')}}
                                                    </button>
                                                </form>
                                                @elseif($driver->status=='accepted')
                                                <button type="button" class="btn btn-success btn-sm" title="Validate Document">
                                                    <i class="fa fa-check"></i>&nbsp;{{__('messages.Approved')}}
                                                </button>
                                                @elseif($driver->status=='rejected')
                                                <button type="button" class="btn btn-danger btn-sm" title="Validate Document">
                                                    <span style="margin-right: 10px;">&#10006;</span>{{__('messages.Rejected')}}
                                                </button>
                                                @endif
                                            </td>
                                        </tr>
                                        @else
                                        <tr>
                                            <td class="text-center dark:text-gray-400 dark:bg-gray-800 py-3 text-sm" colspan="10">{{__("messages.No_documents_uploaded")}}</td>
                                        </tr>
                                        @endif

                                </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
        </div>
    </div>

    @endsection