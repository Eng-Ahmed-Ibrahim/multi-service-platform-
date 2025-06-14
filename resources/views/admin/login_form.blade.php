@extends('admin.layouts.login')
@section('title', "Login")
@section('content')
<!--begin::Card-->
<div class="card rounded-3 w-md-550px">
						<!--begin::Card body-->
    <div class="card-body p-10 p-lg-20">
        <!--begin::Form-->
        <form class="form w-100 form-ajax" action="{{route('admin.login')}}" method="post">
            @csrf
            <!--begin::Heading-->
            <div class="text-center mb-11">
                <!--begin::Title-->
                <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
                <!--end::Title-->
                <!--begin::Subtitle-->
                <!-- <div class="text-gray-500 fw-semibold fs-6">Your Social Campaigns</div> -->
                <!--end::Subtitle=-->
            </div>
            <!--begin::Heading-->
            <!--begin::Login options-->

            <!--end::Login options-->
            <!--begin::Separator-->
            <div class="separator separator-content my-14">
                <span class="w-125px text-gray-500 fw-semibold fs-7">with Admin</span>
            </div>
            <!--end::Separator-->
            <!--begin::Input group=-->
            <div class="fv-row mb-8">
                <!--begin::Email-->
                <input type="text" placeholder="Email" name="email" id="email" autocomplete="off" class="form-control bg-transparent " />
                <!--end::Email-->
                <p id="error-email" class="error-content text-danger"></p>

            </div>
            <!--end::Input group=-->
            <div class="fv-row mb-3">
                <!--begin::Password-->
                <input type="password" placeholder="Password" id="password" name="password" autocomplete="off" class="form-control bg-transparent " />
                <!--end::Password-->
                <p id="error-password" class="error-content text-danger"></p>
            </div>

            <!--end::Input group=-->
            <!--begin::Wrapper-->
            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                <div></div>
                <fieldset>
                    <label for="remember-me">Remember Me</label>
                    <input type="checkbox" name="remember_me" id="remember_me"
                        class="form-check-input" value="1">
                </fieldset>
            </div>
            <!--end::Wrapper-->
            <!--begin::Submit button-->
            <div class="d-grid mb-10">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                    <!--begin::Indicator label-->
                    <span class="indicator-label">Sign In</span>
                    <!--end::Indicator label-->
                    <!--begin::Indicator progress-->
                    <span class="indicator-progress">Please wait...
                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                    <!--end::Indicator progress-->
                </button>
            </div>
            <!--end::Submit button-->
            <!--begin::Sign up-->
            <!-- <div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet? -->
            <!-- <a href="../../demo1/dist/authentication/layouts/creative/sign-up.html" class="link-primary">Sign up</a></div> -->
            <!--end::Sign up-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Card body-->
</div>
<!--end::Card-->
@endsection
