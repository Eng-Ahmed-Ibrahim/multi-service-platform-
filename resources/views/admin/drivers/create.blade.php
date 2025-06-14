@extends('admin.app')
@section('title',trans('messages.create_driver'))
@section('content')
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
            <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{__('messages.create_driver')}}</h1>
                <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                    <li class="breadcrumb-item text-muted">
                        <a class="text-muted text-hover-primary">{{__('messages.Section')}}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-400 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">{{__('messages.create_driver')}}</li>
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
                        <form action="{{route('admin.drivers.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <h4 class=" mb-3">{{ __('messages.Personal_Information') }}</h4>

                            <div class="row">
                                <div class="col-md-6 mb-3   ">
                                    <label class="form-label">{{ __('messages.first_name') }}</label>
                                    <input required type="text" class="form-control" name="f_name" data-fake="Ahmed">
                                </div>
                                <div class="col-md-6 mb-3   ">
                                    <label class="form-label">{{ __('messages.last_name') }}</label>
                                    <input required type="text" class="form-control" name="l_name" data-fake="Ebrahim">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6 mb-3   ">
                                    <label class="form-label">{{ __('messages.phone') }}</label>
                                    <input required type="text" class="form-control" name="phone" data-fake="">
                                </div>
                                <div class="col-md-6 mb-3   ">
                                    <label class="form-label">{{ __('messages.password') }}</label>
                                    <input required type="password" class="form-control" name="password" data-fake="12345678">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label">{{ __('messages.address') }}</label>
                                <input required type="text" class="form-control" name="address" data-fake="Cairo">
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6 mb-3   ">
                                    <label class="form-label">{{ __('messages.date_of_birth') }}</label>
                                    <input required type="date" class="form-control" name="date_of_birth" data-fake="1989-12-11">
                                </div>
                                <div class="col-md-6 mb-3   ">
                                    <label class="form-label">{{ __('messages.gender') }}</label>
                                    <select required  class="form-control" name="gender">
                                        <option value="male" selected>{{ __('messages.male') }}</option>
                                        <option value="female">{{ __('messages.female') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3    mb-3">
                                    <label class="form-label">{{ __('messages.account_photo') }}</label>
                                    <input required type="file" class="form-control" name="account_photo">
                                </div>
                            </div>

                            <h4 class="mt-5 mb-3">{{ __('messages.Official_Documents') }}</h4>
                            <div class="row">
                                <div class="col-md-12 my-3">
                                    <label class="form-label">{{ __('messages.id_number') }}</label>
                                    <input required type="text" class="form-control" name="id_number" data-fake="30303030454545">
                                </div>
                                <div class="col-md-4 mb-3 mb-3   ">
                                    <label class="form-label">{{ __('messages.id_front') }}</label>
                                    <input required type="file" class="form-control" name="id_front">
                                </div>
                                <div class="col-md-4 mb-3 mb-3   ">
                                    <label class="form-label">{{ __('messages.id_back') }}</label>
                                    <input required type="file" class="form-control" name="id_back">
                                </div>
                                <div class="col-md-4 mb-3 mb-3   ">
                                    <label class="form-label">{{ __('messages.criminal_record') }}</label>
                                    <input required type="file" class="form-control" name="criminal_record">
                                </div>
                            </div>
                            <h4 class="mt-5 mb-3">{{ __('messages.Licenses') }}</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3    mb-3">
                                    <label class="form-label">{{ __('messages.issue_date') }}</label>
                                    <input required type="date" class="form-control" name="driving_license_issue_date" data-fake="2020-12-01">
                                </div>
                                <div class="col-md-6 mb-3    mb-3">
                                    <label class="form-label">{{ __('messages.expiry_date') }}</label>
                                    <input required type="date" class="form-control" name="driving_license_expiry_date" data-fake="2026-12-01">
                                </div>
                                <div class="col-md-4 mb-3 mb-3">
                                    <label class="form-label">{{ __('messages.driving_license_front') }}</label>
                                    <input required type="file" class="form-control" name="driving_license_front">
                                </div>
                                <div class="col-md-4 mb-3 mb-3">
                                    <label class="form-label">{{ __('messages.driving_license_back') }}</label>
                                    <input required type="file" class="form-control" name="driving_license_back">
                                </div>
                                <div class="col-md-4 mb-3 mb-3">
                                    <label class="form-label">{{ __('messages.photo_with_driving_license') }}</label>
                                    <input required type="file" class="form-control" name="photo_with_driving_license">
                                </div>
                            </div>


                            <h4 class="mt-5 mb-3">{{ __('messages.Vehicle_Information') }}</h4>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="carType" class="form-label">@lang('messages.Car_Type')</label>
                                    <select required name="car_type" id="carType" class="form-select">
                                        <option value="">@lang('messages.Select_Car_Type')</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="brand" class="form-label">@lang('messages.Brand')</label>
                                    <select required name="brand_id" id="brand" class="form-select" disabled>
                                        <option value=""> @lang('messages.Select_Brand')</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="model" class="form-label">@lang('messages.Model')</label>
                                    <select required name="model_id" id="model" class="form-select" disabled>
                                        <option value=""> @lang('messages.Select_Model')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">{{ __('messages.car_color') }}</label>
                                    <input required type="text" class="form-control" name="car_color" data-fake="black">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">{{ __('messages.car_plate_number') }}</label>
                                    <input required type="text" class="form-control" name="car_plate_number" data-fake="123456">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">{{ __('messages.production_year') }}</label>
                                    <input required type="text" class="form-control" name="production_year" data-fake="123456">
                                </div>
                                <div class="col-md-4 mb-3 mb-3">
                                    <label class="form-label">{{ __('messages.license_front') }}</label>
                                    <input required type="file" class="form-control" name="license_front">
                                </div>
                                <div class="col-md-4 mb-3 mb-3">
                                    <label class="form-label">{{ __('messages.license_back') }}</label>
                                    <input required type="file" class="form-control" name="license_back">
                                </div>
                                <div class="col-md-4 mb-3 mb-3">
                                    <label class="form-label">{{ __('messages.car_photo') }}</label>
                                    <input required type="file" class="form-control" name="car_photo">
                                </div>
                            </div>





                            <div class="mt-4 text-center">
                                <button type="submit" class="btn w-100 btn-primary px-5">{{ __('messages.submit') }}</button>
                            </div>
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
    @section("js")
    <script>
        $(document).ready(function() {
            // Fetch Car Types
            $.get("/api/v1/providers/car-type", function(response) {
                if (response.status === 200) {
                    response.data.forEach(car => {
                        $("#carType").append(`<option value="${car.id}">${car.name}</option>`);
                    });
                }
            });

            // Fetch Brands on Car Type Change
            $("#carType").change(function() {
                let typeId = $(this).val();
                $("#brand").html('<option value="">Select Brand</option>').prop('disabled', true);
                $("#model").html('<option value="">Select Model</option>').prop('disabled', true);

                if (typeId) {
                    $.get("/api/v1/providers/brands", {
                        type_id: typeId
                    }, function(response) {
                        if (response.status === 200) {
                            response.data.forEach(brand => {
                                $("#brand").append(`<option value="${brand.id}">${brand.name}</option>`);
                            });
                            $("#brand").prop('disabled', false);
                        }
                    });
                }
            });

            // Fetch Models on Brand Change
            $("#brand").change(function() {
                let brandId = $(this).val();
                $("#model").html('<option value="">Select Model</option>').prop('disabled', true);

                if (brandId) {
                    $.get("/api/v1/providers/models", {
                        brand_id: brandId
                    }, function(response) {
                        if (response.status === 200) {
                            response.data.forEach(model => {
                                $("#model").append(`<option value="${model.id}">${model.name}</option>`);
                            });
                            $("#model").prop('disabled', false);
                        }
                    });
                }
            });
        });
    </script>

    @endsection