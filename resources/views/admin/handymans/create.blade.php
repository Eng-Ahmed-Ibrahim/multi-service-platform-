@extends('admin.app')
@section('title',trans('messages.create_handyman'))
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.create_handyman')}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary">{{__('messages.Handymans')}}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{__('messages.create_handyman')}}</li>
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
                        <form action="{{route('admin.handymans.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- الاسم الأول والأخير -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label"> @lang('messages.first_name')</label>
                                    <input type="text" name="f_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">@lang("messages.last_name") </label>
                                    <input type="text" name="l_name" class="form-control" required>
                                </div>
                            </div>

                            <!-- البريد الإلكتروني -->
                            <div class="mb-3">
                                <label class="form-label">@lang("messages.Email") </label>
                                <input type="email" name="email" class="form-control" required>
                            </div>

                            <!-- رقم الهاتف -->
                            <div class="mb-3">
                                <label class="form-label">@lang("messages.phone") </label>
                                <input type="tel" name="phone" class="form-control" pattern="[0-9\s\-\+\(\)]*" required>
                            </div>

                            <!-- كلمة المرور -->
                            <div class="mb-3">
                                <label class="form-label"> @lang("messages.password")</label>
                                <input type="password" name="password" class="form-control" minlength="8" required>
                            </div>

                            <!-- العنوان -->
                            <div class="mb-3">
                                <label class="form-label">@lang("messages.address")</label>
                                <textarea name="address" class="form-control" rows="2" required></textarea>
                            </div>

                            <!-- تاريخ الميلاد -->
                            <div class="mb-3">
                                <label class="form-label">@lang("messages.date_of_birth") </label>
                                <input type="date" name="date_of_birth" class="form-control" required>
                            </div>

                            <!-- الرقم الوطني -->
                            <div class="mb-3">
                                <label class="form-label">@lang("messages.id_number") </label>
                                <input type="text" name="id_number" class="form-control" required>
                            </div>

                            <!-- الجنس -->
                            <div class="mb-3">
                                <label class="form-label">@lang("messages.gender") </label>
                                <select name="gender" class="form-select" required>
                                    <option value="male">@lang('messages.male')</option>
                                    <option value="female">@lang('messages.female')</option>
                                </select>
                            </div>

                            <!-- اختيار الخدمات -->
                            <div class="mb-3">
                                <label class="form-label"> @lang("messages.services") </label>
                                <select name="services[]" class="form-select" multiple required>
                                    @foreach($services as $service)
                                    <option value="{{$service->id}}">{{$service->name}}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">@lang('messages.Click_on_anything_to_get_more_from_the_service.')
                                </small>
                            </div>

                            <!-- تحميل الصور والوثائق -->
                            <div class="mb-3">
                                <label class="form-label">@lang("messages.account_photo") </label>
                                <input type="file" name="photo" class="form-control" accept="image/*" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">@lang("messages.criminal_record") </label>
                                <input type="file" name="criminal_record" class="form-control" accept="image/*" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"> @lang("messages.id_front") </label>
                                <input type="file" name="id_front" class="form-control" accept="image/*" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"> @lang("messages.id_back") </label>
                                <input type="file" name="id_back" class="form-control" accept="image/*" required>
                            </div>

                            <!-- زر الإرسال -->
                            <button type="submit" class="btn btn-primary w-100">@lang("messages.submit") </button>
                        </form>


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
