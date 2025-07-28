@extends('admin.app')
@section('title', __('messages.Settings'))

@section('content')
    <div class="d-flex flex-column flex-column-fluid">




        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        {{ __('messages.Settings') }}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="../../demo1/dist/index.html"
                                class="text-muted text-hover-primary">{{ __('messages.Settings') }}</a>
                        </li>

                        <li class="breadcrumb-item text-muted">{{ __('messages.Settings') }}</li>
                    </ul>
                </div>

            </div>
        </div>

        <!--begin:: Sectons-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" style="min-height: 70vh;" class="app-container container-xxl">
                <div class="card card-flush container">
                    <!--begin::Card body-->
                    <div class="card-body">
                        <!--begin:::Tabs-->
                        <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-4 fw-semibold mb-15"
                            role="tablist">
                            <!--begin:::Tab item-->
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary d-flex align-items-center  {{ session('setting_tab') ? (session('settings_tab') == 1 ? ' active' : ' ') : 'active' }} pb-5 "
                                    data-bs-toggle="tab" href="#general" aria-selected="true" role="tab">
                                    <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                    {{ __('messages.General') }}
                                </a>
                            </li>
                            <!--begin:::Tab item-->
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary d-flex align-items-center  {{ session('settings_tab') == 2 ? ' active' : ' ' }}  pb-5 "
                                    data-bs-toggle="tab" href="#Privacy" aria-selected="true" role="tab">
                                    <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                    {{ __('messages.Privacy') }}
                                </a>
                            </li>
                            <!--begin:::Tab item-->
                            <li class="nav-item" role="presentation">
                                <a class="nav-link text-active-primary d-flex align-items-center  {{ session('settings_tab') == 3 ? ' active' : ' ' }}  pb-5 "
                                    data-bs-toggle="tab" href="#Policy" aria-selected="true" role="tab">
                                    <!-- <i class="ki-duotone ki-home fs-2 me-2"></i> -->
                                    {{ __('messages.Policy') }}
                                </a>
                            </li>



                        </ul>
                        <!--end:::Tabs-->

                        <!--begin:::Tab content-->
                        <div class="tab-content" id="myTabContent" data-select2-id="select2-data-myTabContent">

                            <!--begin:::Tab General-->
                            <div class="tab-pane fade {{ session('settings_tab') ? (session('settings_tab') == 1 ? 'show active' : ' ') : 'show active' }}"
                                id="general" role="tabpanel" data-select2-id="select2-data-general">
                                <form action="{{ route('admin.settings.update') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="tab" value="1" id="">

                                    <input type="text" hidden name="keys[]" value="price_per_kilometer">
                                    <input type="text" hidden name="keys[]" value="minimum_wallet_balance">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 mb-3">
                                            <label for="formGroupExampleInput"
                                                class="form-label">{{ __('messages.Price_per_kilometer') }}</label>
                                            <input type="text"
                                                value="{{ $settings->where('key', 'price_per_kilometer')->first()->value }}"
                                                class="form-control" id="formGroupExampleInput" name="values[]"
                                                placeholder="{{ __('messages.Price_per_kilometer') }}">
                                        </div>
                                        <div class="col-lg-6 col-sm-12 mb-3">
                                            <label for="formGroupExampleInput"
                                                class="form-label">{{ __('messages.minimum_wallet_balance') }}</label>
                                            <input type="text"
                                                value="{{ $settings->where('key', 'minimum_wallet_balance')->first()->value }}"
                                                class="form-control" id="formGroupExampleInput" name="values[]"
                                                placeholder="{{ __('messages.minimum_wallet_balance') }}">
                                        </div>
                                    </div>
                                    @can('update settings')
                                        <button class="btn btn-primary w-100 mt-4">@lang('messages.Save_changes') </button>
                                    @endcan
                                </form>
                            </div>
                            <!-- End :: Tab General -->

                            <!--begin:::Tab Privacy-->
                            <div class="tab-pane fade  {{ session('settings_tab') == 2 ? 'show active' : ' ' }} "
                                id="Privacy" role="tabpanel" data-select2-id="select2-data-general">
                                <form action="{{ route('admin.settings.update') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="tab" value="2" id="">

                                    <input type="text" hidden name="keys[]" value="privacy_users_en">
                                    <input type="text" hidden name="keys[]" value="privacy_users_ar">
                                    <input type="text" hidden name="keys[]" value="privacy_handymans_en">
                                    <input type="text" hidden name="keys[]" value="privacy_handymans_ar">
                                    <input type="text" hidden name="keys[]" value="privacy_drivers_en">
                                    <input type="text" hidden name="keys[]" value="privacy_drivers_ar">
                                    <div class="row">

                                        <h4 class="mb-3">@lang('messages.Users')</h4>
                                        <div class="form-floating mb-3 mb-3 col-md-6 col-xs-12">
                                            <textarea style="height: 100px;" class="form-control mb-4" name="values[]" placeholder="Leave a comment here"
                                                id="floatingTextarea">{{ $settings->where('key', 'privacy_users_en')->first()->value }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.In_English') }}</label>
                                        </div>
                                        <div class="form-floating mb-3 mb-3 col-md-6 col-xs-12">
                                            <textarea style="height: 100px;" class="form-control mb-4" name="values[]" placeholder="Leave a comment here"
                                                id="floatingTextarea">{{ $settings->where('key', 'privacy_users_ar')->first()->value }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.In_Arabic') }}</label>
                                        </div>

                                        <h4 class="mb-3">@lang('messages.Handymans')</h4>
                                        <div class="form-floating mb-3 mb-3 col-md-6 col-xs-12">
                                            <textarea style="height: 100px;" class="form-control mb-4" name="values[]" placeholder="Leave a comment here"
                                                id="floatingTextarea">{{ $settings->where('key', 'privacy_handymans_en')->first()->value }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.In_English') }}</label>
                                        </div>
                                        <div class="form-floating mb-3 mb-3 col-md-6 col-xs-12">
                                            <textarea style="height: 100px;" class="form-control mb-4" name="values[]" placeholder="Leave a comment here"
                                                id="floatingTextarea">{{ $settings->where('key', 'privacy_handymans_ar')->first()->value }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.In_Arabic') }}</label>
                                        </div>

                                        <h4 class="mb-3">@lang('messages.Drivers')</h4>
                                        <div class="form-floating mb-3 mb-3 col-md-6 col-xs-12">
                                            <textarea style="height: 100px;" class="form-control mb-4" name="values[]" placeholder="Leave a comment here"
                                                id="floatingTextarea">{{ $settings->where('key', 'privacy_drivers_en')->first()->value }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.In_English') }}</label>
                                        </div>
                                        <div class="form-floating mb-3 mb-3 col-md-6 col-xs-12">
                                            <textarea style="height: 100px;" class="form-control mb-4" name="values[]" placeholder="Leave a comment here"
                                                id="floatingTextarea">{{ $settings->where('key', 'privacy_drivers_ar')->first()->value }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.In_Arabic') }}</label>
                                        </div>
                                        @can('update settings')
                                            <button class="btn btn-primary w-100">{{ __('messages.Update') }}</button>
                                        @endcan
                                    </div>
                                </form>
                            </div>
                            <!-- End :: Tab Privacy -->
                            <!--begin:::Tab Policy-->
                            <div class="tab-pane fade  {{ session('settings_tab') == 3 ? 'show active' : ' ' }}"
                                id="Policy" role="tabpanel" data-select2-id="select2-data-general">
                                <form action="{{ route('admin.settings.update') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="tab" value="3" id="">
                                    <input type="text" hidden name="keys[]" value="policy_users_en">
                                    <input type="text" hidden name="keys[]" value="policy_users_ar">
                                    <input type="text" hidden name="keys[]" value="policy_handymans_en">
                                    <input type="text" hidden name="keys[]" value="policy_handymans_ar">
                                    <input type="text" hidden name="keys[]" value="policy_drivers_en">
                                    <input type="text" hidden name="keys[]" value="policy_drivers_ar">
                                    <div class="row">
                                        <h4 class="mb-3">@lang('messages.Users')</h4>
                                        <div class=" col-md-6 col-xs-12 mb-3 form-floating">
                                            <textarea style="height: 100px;" class="form-control mb-4" name="values[]" placeholder="Leave a comment here"
                                                id="floatingTextarea">{{ $settings->where('key', 'policy_users_en')->first()->value }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.In_English') }}</label>
                                        </div>
                                        <div class=" col-md-6 col-xs-12 mb-3 form-floating">
                                            <textarea style="height: 100px;" class="form-control mb-4" name="values[]" placeholder="Leave a comment here"
                                                id="floatingTextarea">{{ $settings->where('key', 'policy_users_ar')->first()->value }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.In_Arabic') }}</label>
                                        </div>
                                        <h4 class="mb-3">@lang('messages.Handymans')</h4>
                                        <div class=" col-md-6 col-xs-12 mb-3 form-floating">
                                            <textarea style="height: 100px;" class="form-control mb-4" name="values[]" placeholder="Leave a comment here"
                                                id="floatingTextarea">{{ $settings->where('key', 'policy_handymans_en')->first()->value }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.In_English') }}</label>
                                        </div>
                                        <div class=" col-md-6 col-xs-12 mb-3 form-floating">
                                            <textarea style="height: 100px;" class="form-control mb-4" name="values[]" placeholder="Leave a comment here"
                                                id="floatingTextarea">{{ $settings->where('key', 'policy_handymans_ar')->first()->value }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.In_Arabic') }}</label>
                                        </div>
                                        <h4 class="mb-3">@lang('messages.Drivers')</h4>
                                        <div class=" col-md-6 col-xs-12 mb-3 form-floating">
                                            <textarea style="height: 100px;" class="form-control mb-4" name="values[]" placeholder="Leave a comment here"
                                                id="floatingTextarea">{{ $settings->where('key', 'policy_drivers_en')->first()->value }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.In_English') }}</label>
                                        </div>
                                        <div class=" col-md-6 col-xs-12 mb-3 form-floating">
                                            <textarea style="height: 100px;" class="form-control mb-4" name="values[]" placeholder="Leave a comment here"
                                                id="floatingTextarea">{{ $settings->where('key', 'policy_drivers_ar')->first()->value }}</textarea>
                                            <label for="floatingTextarea">{{ __('messages.In_Arabic') }}</label>
                                        </div>
                                        @can('update settings')
                                            <button class="btn btn-primary w-100">{{ __('messages.Update') }}</button>
                                        @endcan
                                    </div>
                                </form>

                            </div>
                            <!-- End :: Tab Policy -->







                        </div>
                        <!--end:::Tab content-->
                    </div>
                    <!--end::Card body-->
                </div>
            </div>
        </div>




    @endsection
