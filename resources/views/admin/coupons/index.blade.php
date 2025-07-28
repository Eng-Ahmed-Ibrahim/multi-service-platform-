@extends('admin.app')
@section('title', trans('messages.Coupons'))
@section('css')
    <style>
        .d-flex-center {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .is-invalid {
            border-color: #dc3545 !important;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        {{ __('messages.Coupons') }}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a class="text-muted text-hover-primary">{{ __('messages.Home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ __('messages.Coupons') }}</li>
                    </ul>
                </div>
                @can('add coupons')
                    <div class="d-flex align-items-center gap-2 gap-lg-3">

                        <button class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal"
                            data-bs-target="#add">{{ __('messages.Create') }}</button>
                    </div>
                @endcan
            </div>
        </div>
        <div id="kt_app_content_container" class="app-container  container-xxl ">
            <div class="d-flex flex-column flex-xl-row">
                <div class="flex-column flex-lg-row-auto w-100  mb-10">
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-body pt-15">
                            <div id="" class="table-responsive">

                                <table class="table align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bold text-muted bg-light">
                                            <th class="ps-4 min-w-125px rounded-start">{{ __('messages.Coupon_code') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Coupon_value') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Type') }}</th>
                                            <th class="min-w-125px">{{ __('messages.Start_at') }}</th>
                                            <th class="min-w-125px">{{ __('messages.End_at') }}</th>
                                            @if (Auth::guard('admin')->user()->can('edit coupons') || Auth::guard('admin')->user()->can('delete coupons'))
                                                <th class="min-w-125px">{{ __('messages.Actions') }}</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($coupons as $coupon)
                                            <tr>
                                                <td>{{ $coupon->coupon_code }}</td>
                                                <td>{{ $coupon->coupon_value }}</td>
                                                <td>{{ __("messages.$coupon->type") }}</td>
                                                <td> {{ $coupon->start_at }}</td>
                                                <td>{{ $coupon->end_at }} </td>
                                                @if (Auth::guard('admin')->user()->can('edit coupons') || Auth::guard('admin')->user()->can('delete coupons'))
                                                    <td class="text-center">
                                                        @can('edit coupons')
                                                            <button
                                                                onclick="setData('{{ $coupon->id }}','{{ $coupon->coupon_code }}','{{ $coupon->coupon_value }}','{{ $coupon->type }}','{{ \Carbon\Carbon::parse($coupon->start_at)->format('Y-m-d') }}','{{ \Carbon\Carbon::parse($coupon->end_at)->format('Y-m-d') }}')"
                                                                data-bs-toggle="modal" data-bs-target="#edit"
                                                                class="btn btn-bg-light btn-color-muted btn-active-color-primary btn-sm px-4">
                                                                {{ __('messages.Edit') }}
                                                            </button>
                                                        @endcan
                                                        @can('delete coupons')
                                                            <form action="{{ route('admin.coupons.delete_coupon') }}"
                                                                method="post" style="display: inline-block;">
                                                                @csrf
                                                                <input type="text" name="id" hidden
                                                                    value="{{ $coupon->id }}">
                                                                <button type="submit"
                                                                    class="btn btn-bg-light btn-color-muted btn-active-color-danger btn-sm px-4">
                                                                    {{ __('messages.Delete') }}
                                                                </button>
                                                            </form>
                                                        @endcan

                                                    </td>
                                                @endif

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            {{ $coupons->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <!-- add -->
        <div class="modal fade" id="add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.add_new_coupon') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form enctype="multipart/form-data" action="{{ route('admin.coupons.add_coupon') }}"
                            method="post">
                            @csrf
                            <div class="row my-2">
                                <div class="col">
                                    <label for="coupon_code">{{ __('messages.Coupon_code') }}</label>

                                    <input required name="coupon_code" type="text" class="form-control english-only"
                                        placeholder="{{ __('messages.Coupon_code') }}" aria-label="First name">
                                    <div class="invalid-feedback">Only English numbers are allowed.</div>
                                </div>
                                <div class="col">
                                    <label for="coupon_value">{{ __('messages.Coupon_value') }}</label>

                                    <input required name="coupon_value" min="1" type="number" class="form-control  "
                                        placeholder="{{ __('messages.Coupon_value') }}" aria-label="Last name">

                                </div>

                            </div>
                            <div class="row my-2">
                                <div class="col">
                                    <label for="start_at">{{ __('messages.Start_at') }}</label>
                                    <input required id="start_at" name="start_at" type="date" class="form-control "
                                        placeholder="{{ __('messages.Start_at') }}" aria-label="First name">
                                </div>
                                <div class="col">
                                    <label for="end_at">{{ __('messages.End_at') }}</label>
                                    <input required id="end_at" name="end_at" type="date" class="form-control "
                                        placeholder="{{ __('messages.End_at') }}" aria-label="Last name">
                                </div>

                            </div>
                            <div class="row my-2">
                                <div class="col text-center">
                                    <div class="input-group row">
                                        <label for="coupon_value_percentage"
                                            class="col-md-4 col-form-label ">{{ __('messages.Percentage') }} (%)</label>
                                        <div class="col-md-6 d-flex-center">

                                            <input class="form-check-input" name="type" id="coupon_value_percentage"
                                                type="radio" value="percentage" checked="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col text-center">
                                    <div class="input-group row">
                                        <label for="coupon_value_amount"
                                            class="col-md-4 col-form-label ">{{ __('messages.Amount') }} (EGP)</label>
                                        <div class="col-md-6 d-flex-center">
                                            <input class="form-check-input" id="coupon_value_amount" name="type"
                                                type="radio" value="amount" checked="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.Add') }}</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Edit -->
        <div class="modal fade" id="edit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.edit_coupon') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form enctype="multipart/form-data" action="{{ route('admin.coupons.update_coupon') }}"
                            method="post">
                            @csrf
                            <input type="text" class="coupon_id" name="id" class="coupon_id" hidden>
                            <div class="row my-2">
                                <div class="col">
                                    <label for="coupon_code">{{ __('messages.Coupon_code') }}</label>

                                    <input required name="coupon_code" type="text"
                                        class="form-control Coupon_code english-only"
                                        placeholder="{{ __('messages.Coupon_code') }}" aria-label="First name">
                                    <div class="invalid-feedback">Only English numbers are allowed.</div>

                                </div>
                                <div class="col">
                                    <label for="coupon_value">{{ __('messages.Coupon_value') }}</label>

                                    <input required name="coupon_value" type="number" class="form-control Coupon_value "
                                        placeholder="{{ __('messages.Coupon_value') }}" min="1"
                                        aria-label="Last name">
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col">
                                    <label for="start_at">{{ __('messages.Start_at') }}</label>

                                    <input required name="start_at" type="date" class="form-control coupon_start_at"
                                        placeholder="{{ __('messages.Start_at') }}" aria-label="First name">
                                </div>
                                <div class="col">
                                    <label for="start_at">{{ __('messages.End_at') }}</label>

                                    <input required name="end_at" type="date" class="form-control coupon_end_at"
                                        placeholder="{{ __('messages.End_at') }}" aria-label="Last name">
                                </div>
                            </div>
                            <div class="row my-2">
                                <div class="col text-center">
                                    <div class="input-group row">
                                        <label for="coupon_value_percentage"
                                            class="col-md-4 col-form-label ">{{ __('messages.Percentage') }} (%)</label>
                                        <div class="col-md-6 d-flex-center">

                                            <input class="form-check-input" id="coupon_value_percentage_edit"
                                                name="type" type="radio" value="percentage">
                                        </div>
                                    </div>
                                </div>
                                <div class="col text-center">
                                    <div class="input-group row">
                                        <label for="coupon_value_amount"
                                            class="col-md-4 col-form-label ">{{ __('messages.Amount') }} (EGP)</label>
                                        <div class="col-md-6 d-flex-center">
                                            <input class="form-check-input" id="coupon_value_amount_edit" name="type"
                                                type="radio" value="amount">
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.Save_changes') }}</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection

    @section('js')
        <script>
            function setData(id, coupon_code, coupon_value, type, start_at, end_at) {
                let InputEndAt = document.querySelector(".coupon_end_at")
                let InputStartAt = document.querySelector(".coupon_start_at")
                let InputValue = document.querySelector(".Coupon_value")
                let InputCode = document.querySelector(".Coupon_code")
                let InputId = document.querySelector(".coupon_id")
                let InputAmount = document.getElementById('coupon_value_amount_edit');
                let InputPercentage = document.getElementById('coupon_value_percentage_edit');

                InputId.value = id
                InputEndAt.value = end_at
                InputStartAt.value = start_at
                InputValue.value = coupon_value
                InputCode.value = coupon_code
                if (type == 'percentage') {
                    InputPercentage.checked = true
                    InputAmount.checked = false
                } else {
                    InputPercentage.checked = false
                    InputAmount.checked = true
                }
            }
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const englishInputs = document.querySelectorAll(".english-only");

                englishInputs.forEach(function(input) {
                    input.addEventListener("input", function() {
                        const value = this.value;
                        const isEnglish = /^[\dA-Za-z\s]*$/.test(
                            value); // يسمح فقط بأرقام وحروف إنجليزية ومسافات

                        if (!isEnglish) {
                            this.classList.add("is-invalid");
                            this.nextElementSibling.style.display = "block"; // عرض رسالة الخطأ
                        } else {
                            this.classList.remove("is-invalid");
                            this.nextElementSibling.style.display = "none"; // إخفاء رسالة الخطأ
                        }
                    });
                });

                // استهداف كل الفورمات الخاصة بـ add و edit
                const forms = document.querySelectorAll("form");

                forms.forEach(function(form) {
form.addEventListener("submit", function(e) {
    let isValid = true;

    // تحقق من الحقول required
    form.querySelectorAll("[required]").forEach(function(field) {
        if (!field.value || field.classList.contains("is-invalid")) {
            field.classList.add("is-invalid");

            const feedback = field.nextElementSibling;
            if (feedback && feedback.classList.contains("invalid-feedback")) {
                feedback.style.display = "block";
            }

            isValid = false;
        }
    });

    // 🔽 تحقق من التواريخ start_at و end_at
    const startInput = form.querySelector('[name="start_at"]');
    const endInput = form.querySelector('[name="end_at"]');

    if (startInput && endInput) {
        const startDate = new Date(startInput.value);
        const endDate = new Date(endInput.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0); // إزالة الوقت للمقارنة الدقيقة

        if (endDate < today) {
            alert("End date must be today or later.");
            endInput.classList.add("is-invalid");
            isValid = false;
        } else if (endDate < startDate) {
            alert("End date must be after Start date.");
            endInput.classList.add("is-invalid");
            isValid = false;
        } else {
            endInput.classList.remove("is-invalid");
        }
    }

    if (!isValid) {
        e.preventDefault(); // امنع إرسال الفورم
    }
});

                });
            });
        </script>


    @endsection
