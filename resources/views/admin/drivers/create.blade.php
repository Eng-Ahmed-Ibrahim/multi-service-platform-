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

                            @include('admin.drivers._form')

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