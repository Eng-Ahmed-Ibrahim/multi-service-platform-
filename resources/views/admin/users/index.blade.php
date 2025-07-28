@extends('admin.app')
@section('title', trans('messages.Users'))
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        {{ __('messages.Users') }}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a class="text-muted text-hover-primary">{{ __('messages.Members') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ __('messages.Users') }}</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">

                    @can('create users')
                    <a class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_add_user">{{ __('messages.Create') }}</a>
                        @endcan
                </div>
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

                                        <th class="min-w-175px">@lang('messages.Name')</th>
                                        <th class=" min-w-100px">@lang('messages.Email') </th>
                                        <th class=" min-w-100px">@lang('messages.Phone') </th>
                                        <th class=" min-w-100px">@lang('messages.Created_at') </th>
                                        <th class=" min-w-100px">@lang('messages.Actions') </th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600">
                                    @foreach ($users as $key => $user)
                                        <tr>
                                            <td data-kt-ecommerce-order-filter="order_id">

                                                <a href="{{ route('admin.users.show', $user->id) }}">{{ $user->id }}</a>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <!--begin:: Avatar -->
                                                    <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                                        <a>
                                                            <div class="symbol-label">
                                                                <img src="{{ asset($user->image) }}" alt="Emma Smith"
                                                                    class="w-100" />
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <!--end::Avatar-->
                                                    <div class="ms-5">
                                                        <!--begin::Title-->
                                                        <a
                                                            class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $user->name }}</a>
                                                        <!--end::Title-->
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $user->email }}</span>
                                            </td>

                                            <td>
                                                <span class="fw-bold">{{ $user->phone }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $user->created_at->format('d/m/Y') }}</span>
                                            </td>

                                            <td>
                                                <a class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                                                    data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                                                    <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                                                <!--begin::Menu-->
                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                                                    data-kt-menu="true">

                                                    @can("update users")
                                                    <div class="menu-item px-3">
                                                        <a data-bs-toggle="modal"
                                                            onclick="editData('{{ $user->id }}','{{ $user->name }}','{{ $user->image }}','{{ $user->email }}','{{ $user->phone }}')"
                                                            data-bs-target="#kt_modal_edit_user"
                                                            class="menu-link px-3">{{ __('messages.Edit') }}</a>
                                                    </div>
                                                    @endcan

                                                    @can("delete users")
                                                    <div class="menu-item px-3">
                                                        <form action="{{ route('admin.users.delete', $user->id) }}"
                                                            id="delete-form" method="post">
                                                            @csrf
                                                            <button type="button"
                                                                onclick="if(confirm('{{ __('messages.Are_you_sure') }}')) document.getElementById('delete-form').submit()"
                                                                class="menu-link px-3 btn "
                                                                data-kt-ecommerce-order-filter="delete_row">{{ __('messages.Delete') }}</button>
                                                        </form>
                                                    </div>
                                                    @endcan
                                                    <!--end::Menu item-->
                                                </div>
                                                <!--end::Menu-->
                                            </td>
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
                        <h2 class="fw-bold">{{ __('messages.Add') }}</h2>

                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                        transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                        transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <!--begin::Form-->
                        <form id="kt_modal_add_user_form" class="form form-ajax fv-plugins-bootstrap5 fv-plugins-framework"
                            action="{{ route('admin.users.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll"
                                data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                                data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header"
                                data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px"
                                style="">

                                <div class="fv-row mb-7">
                                    <label class="d-block fw-semibold fs-6 mb-5">{{ __('messages.Avatar') }}</label>
                                    <div class="image-input image-input-outline image-input-placeholder"
                                        data-kt-image-input="true">
                                        <div class="image-input-wrapper w-125px h-125px"
                                            style="background-image: url({{ asset('static/images/300-29.jpg') }});"></div>

                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            aria-label="Change avatar" data-bs-original-title="Change avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg">
                                            <input type="hidden" name="avatar_remove">
                                        </label>

                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            aria-label="Cancel avatar" data-bs-original-title="Cancel avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            aria-label="Remove avatar" data-bs-original-title="Remove avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                    </div>

                                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                </div>

                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __('messages.Full_name') }}</label>
                                    <input type="text" name="name"
                                        class="form-control form-control-solid mb-3 mb-lg-0"
                                        placeholder="{{ __('messages.Full_name') }}" value="" required=""
                                        pattern="[A-Za-z\s]+" title="Only letters are allowed">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __('messages.Email') }}</label>
                                    <input type="email" name="email"
                                        class="form-control form-control-solid mb-3 mb-lg-0"
                                        placeholder="example@domain.com" value="" required="">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                <div class="fv-row mb-7" style="display: flex; gap: 10px;">
                                    <select id="country_code" name="country_code" style="width:140px"
                                        class="form-select">
                                        <option disabled>{{ __('messages.Choose_country') }}</option>
                                        <option value="1">🇺🇸 United States (+1)</option>
                                        <option value="44">🇬🇧 United Kingdom (+44)</option>
                                        <option value="20" selected>🇪🇬 Egypt (+20)</option>
                                        <option value="971">🇦🇪 United Arab Emirates (+971)</option>
                                        <option value="966">🇸🇦 Saudi Arabia (+966)</option>
                                        <option value="974">🇶🇦 Qatar (+974)</option>
                                        <option value="965">🇰🇼 Kuwait (+965)</option>
                                        <option value="973">🇧🇭 Bahrain (+973)</option>
                                        <option value="968">🇴🇲 Oman (+968)</option>
                                        <option value="961">🇱🇧 Lebanon (+961)</option>
                                        <option value="962">🇯🇴 Jordan (+962)</option>
                                        <option value="963">🇸🇾 Syria (+963)</option>
                                        <option value="964">🇮🇶 Iraq (+964)</option>
                                        <option value="212">🇲🇦 Morocco (+212)</option>
                                        <option value="213">🇩🇿 Algeria (+213)</option>
                                        <option value="216">🇹🇳 Tunisia (+216)</option>
                                        <option value="218">🇱🇾 Libya (+218)</option>
                                        <option value="249">🇸🇩 Sudan (+249)</option>
                                        <option value="7">🇷🇺 Russia (+7)</option>
                                        <option value="86">🇨🇳 China (+86)</option>
                                        <option value="91">🇮🇳 India (+91)</option>
                                        <option value="92">🇵🇰 Pakistan (+92)</option>
                                        <option value="880">🇧🇩 Bangladesh (+880)</option>
                                        <option value="81">🇯🇵 Japan (+81)</option>
                                        <option value="82">🇰🇷 South Korea (+82)</option>
                                        <option value="90">🇹🇷 Turkey (+90)</option>
                                        <option value="39">🇮🇹 Italy (+39)</option>
                                        <option value="33">🇫🇷 France (+33)</option>
                                        <option value="34">🇪🇸 Spain (+34)</option>
                                        <option value="49">🇩🇪 Germany (+49)</option>
                                        <option value="43">🇦🇹 Austria (+43)</option>
                                        <option value="41">🇨🇭 Switzerland (+41)</option>
                                        <option value="31">🇳🇱 Netherlands (+31)</option>
                                        <option value="32">🇧🇪 Belgium (+32)</option>
                                        <option value="46">🇸🇪 Sweden (+46)</option>
                                        <option value="45">🇩🇰 Denmark (+45)</option>
                                        <option value="47">🇳🇴 Norway (+47)</option>
                                        <option value="48">🇵🇱 Poland (+48)</option>
                                        <option value="380">🇺🇦 Ukraine (+380)</option>
                                        <option value="357">🇨🇾 Cyprus (+357)</option>
                                        <option value="61">🇦🇺 Australia (+61)</option>
                                        <option value="64">🇳🇿 New Zealand (+64)</option>
                                        <option value="1">🇨🇦 Canada (+1)</option>
                                        <option value="52">🇲🇽 Mexico (+52)</option>
                                        <option value="55">🇧🇷 Brazil (+55)</option>
                                        <option value="54">🇦🇷 Argentina (+54)</option>
                                        <option value="51">🇵🇪 Peru (+51)</option>
                                        <option value="56">🇨🇱 Chile (+56)</option>
                                        <option value="63">🇵🇭 Philippines (+63)</option>
                                        <option value="60">🇲🇾 Malaysia (+60)</option>
                                        <option value="65">🇸🇬 Singapore (+65)</option>
                                        <option value="66">🇹🇭 Thailand (+66)</option>
                                        <option value="84">🇻🇳 Vietnam (+84)</option>
                                        <option value="62">🇮🇩 Indonesia (+62)</option>
                                        <option value="94">🇱🇰 Sri Lanka (+94)</option>
                                        <option value="880">🇧🇩 Bangladesh (+880)</option>
                                        <option value="254">🇰🇪 Kenya (+254)</option>
                                        <option value="27">🇿🇦 South Africa (+27)</option>
                                        <option value="234">🇳🇬 Nigeria (+234)</option>
                                        <option value="221">🇸🇳 Senegal (+221)</option>
                                        <option value="225">🇨🇮 Côte d'Ivoire (+225)</option>
                                        <option value="256">🇺🇬 Uganda (+256)</option>
                                        <option value="255">🇹🇿 Tanzania (+255)</option>
                                    </select>
                                    <input type="text" name="phone"
                                        class="form-control form-control-solid mb-3 mb-lg-0" placeholder="1XXXXXXXXX"
                                        value="" required="" title="Only numbers are allowed *123456789"
                                        maxlength="11" pattern="^[1-9][0-9]{0,11}$">
                                </div>
                                <div class="fv-row mb-7 fv-plugins-icon-container">

                                    <label class="required fw-semibold fs-6 mb-2">{{ __('messages.Password') }}</label>
                                    <input type="password" name="password"
                                        class="form-control form-control-solid mb-3 mb-lg-0" placeholder="********"
                                        value="" required="">
                                    <!--end::Input-->
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                <div class="fv-row mb-7 fv-plugins-icon-container">

                                    <label
                                        class="required fw-semibold fs-6 mb-2">{{ __('messages.Password_confirmation') }}</label>
                                    <input type="password" name="password_confirmation"
                                        class="form-control form-control-solid mb-3 mb-lg-0" placeholder="********"
                                        value="" required="">
                                    <!--end::Input-->
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>

                            </div>
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3"
                                    data-kt-users-modal-action="cancel">{{ __('messages.Retreat') }}</button>
                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                    <span class="indicator-label">{{ __('messages.Save') }}</span>
                                    <span class="indicator-progress">Please wait... <span
                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
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
                        <h2 class="fw-bold">{{ __('messages.Edit') }}</h2>

                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal"
                            aria-label="Close">
                            <span class="svg-icon svg-icon-1">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                                        transform="rotate(-45 6 17.3137)" fill="currentColor"></rect>
                                    <rect x="7.41422" y="6" width="16" height="2" rx="1"
                                        transform="rotate(45 7.41422 6)" fill="currentColor"></rect>
                                </svg>
                            </span>
                        </div>
                    </div>
                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <!--begin::Form-->
                        <form id="kt_modal_add_user_form"
                            class="form form-ajax fv-plugins-bootstrap5 fv-plugins-framework"
                            action="{{ route('admin.users.update') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="text" hidden name="user_id" id="edit-user-id">
                            <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_user_scroll"
                                data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}"
                                data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_user_header"
                                data-kt-scroll-wrappers="#kt_modal_add_user_scroll" data-kt-scroll-offset="300px"
                                style="">

                                <div class="fv-row mb-7">
                                    <label class="d-block fw-semibold fs-6 mb-5">{{ __('messages.Avatar') }}</label>
                                    <div class="image-input image-input-outline image-input-placeholder"
                                        data-kt-image-input="true">
                                        <div class="image-input-wrapper w-125px h-125px" id="edit-image"
                                            style="background-image: url({{ asset('static/images/300-29.jpg') }});"></div>
                                        <label
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                            aria-label="Change avatar" data-bs-original-title="Change avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-pencil-fill fs-7"></i>
                                            <input type="file" name="avatar" accept=".png, .jpg, .jpeg">
                                            <input type="hidden" name="avatar_remove">
                                        </label>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                            aria-label="Cancel avatar" data-bs-original-title="Cancel avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                        <span
                                            class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                            data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                                            aria-label="Remove avatar" data-bs-original-title="Remove avatar"
                                            data-kt-initialized="1">
                                            <i class="bi bi-x fs-2"></i>
                                        </span>
                                    </div>
                                    <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                                </div>

                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __('messages.Full_name') }}</label>
                                    <input type="text" id="edit-name" name="name"
                                        class="form-control form-control-solid mb-3 mb-lg-0"
                                        placeholder="{{ __('messages.Full_name') }}" value="" required=""
                                        pattern="[A-Za-z\s]+" title="Only letters are allowed">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <label class="required fw-semibold fs-6 mb-2">{{ __('messages.Email') }}</label>
                                    <input type="email" id="edit-email" name="email"
                                        class="form-control form-control-solid mb-3 mb-lg-0"
                                        placeholder="example@domain.com" value="" required="">
                                    <div class="fv-plugins-message-container invalid-feedback"></div>
                                </div>
                                <div class=" mb-7" style="display: flex; gap: 10px;">
                                    <select id="country_code" name="country_code" style="width:140px"
                                        class="form-select">
                                        <option disabled>{{ __('messages.Choose_country') }}</option>
                                        <option value="1">🇺🇸 United States (+1)</option>
                                        <option value="44">🇬🇧 United Kingdom (+44)</option>
                                        <option value="20" selected>🇪🇬 Egypt (+20)</option>
                                        <option value="971">🇦🇪 United Arab Emirates (+971)</option>
                                        <option value="966">🇸🇦 Saudi Arabia (+966)</option>
                                        <option value="974">🇶🇦 Qatar (+974)</option>
                                        <option value="965">🇰🇼 Kuwait (+965)</option>
                                        <option value="973">🇧🇭 Bahrain (+973)</option>
                                        <option value="968">🇴🇲 Oman (+968)</option>
                                        <option value="961">🇱🇧 Lebanon (+961)</option>
                                        <option value="962">🇯🇴 Jordan (+962)</option>
                                        <option value="963">🇸🇾 Syria (+963)</option>
                                        <option value="964">🇮🇶 Iraq (+964)</option>
                                        <option value="212">🇲🇦 Morocco (+212)</option>
                                        <option value="213">🇩🇿 Algeria (+213)</option>
                                        <option value="216">🇹🇳 Tunisia (+216)</option>
                                        <option value="218">🇱🇾 Libya (+218)</option>
                                        <option value="249">🇸🇩 Sudan (+249)</option>
                                        <option value="7">🇷🇺 Russia (+7)</option>
                                        <option value="86">🇨🇳 China (+86)</option>
                                        <option value="91">🇮🇳 India (+91)</option>
                                        <option value="92">🇵🇰 Pakistan (+92)</option>
                                        <option value="880">🇧🇩 Bangladesh (+880)</option>
                                        <option value="81">🇯🇵 Japan (+81)</option>
                                        <option value="82">🇰🇷 South Korea (+82)</option>
                                        <option value="90">🇹🇷 Turkey (+90)</option>
                                        <option value="39">🇮🇹 Italy (+39)</option>
                                        <option value="33">🇫🇷 France (+33)</option>
                                        <option value="34">🇪🇸 Spain (+34)</option>
                                        <option value="49">🇩🇪 Germany (+49)</option>
                                        <option value="43">🇦🇹 Austria (+43)</option>
                                        <option value="41">🇨🇭 Switzerland (+41)</option>
                                        <option value="31">🇳🇱 Netherlands (+31)</option>
                                        <option value="32">🇧🇪 Belgium (+32)</option>
                                        <option value="46">🇸🇪 Sweden (+46)</option>
                                        <option value="45">🇩🇰 Denmark (+45)</option>
                                        <option value="47">🇳🇴 Norway (+47)</option>
                                        <option value="48">🇵🇱 Poland (+48)</option>
                                        <option value="380">🇺🇦 Ukraine (+380)</option>
                                        <option value="357">🇨🇾 Cyprus (+357)</option>
                                        <option value="61">🇦🇺 Australia (+61)</option>
                                        <option value="64">🇳🇿 New Zealand (+64)</option>
                                        <option value="1">🇨🇦 Canada (+1)</option>
                                        <option value="52">🇲🇽 Mexico (+52)</option>
                                        <option value="55">🇧🇷 Brazil (+55)</option>
                                        <option value="54">🇦🇷 Argentina (+54)</option>
                                        <option value="51">🇵🇪 Peru (+51)</option>
                                        <option value="56">🇨🇱 Chile (+56)</option>
                                        <option value="63">🇵🇭 Philippines (+63)</option>
                                        <option value="60">🇲🇾 Malaysia (+60)</option>
                                        <option value="65">🇸🇬 Singapore (+65)</option>
                                        <option value="66">🇹🇭 Thailand (+66)</option>
                                        <option value="84">🇻🇳 Vietnam (+84)</option>
                                        <option value="62">🇮🇩 Indonesia (+62)</option>
                                        <option value="94">🇱🇰 Sri Lanka (+94)</option>
                                        <option value="880">🇧🇩 Bangladesh (+880)</option>
                                        <option value="254">🇰🇪 Kenya (+254)</option>
                                        <option value="27">🇿🇦 South Africa (+27)</option>
                                        <option value="234">🇳🇬 Nigeria (+234)</option>
                                        <option value="221">🇸🇳 Senegal (+221)</option>
                                        <option value="225">🇨🇮 Côte d'Ivoire (+225)</option>
                                        <option value="256">🇺🇬 Uganda (+256)</option>
                                        <option value="255">🇹🇿 Tanzania (+255)</option>
                                    </select>
                                    <input type="text" id="edit-phone" name="phone"
                                        class="form-control form-control-solid mb-3 mb-lg-0" placeholder="01XXXXXXXXX"
                                        value="" required="" title="Only numbers are allowed *123456789" maxlength="11" pattern="^[1-9][0-9]{0,11}$">
                                </div>

                            </div>
                            <div class="text-center pt-15">
                                <button type="reset" class="btn btn-light me-3"
                                    data-kt-users-modal-action="cancel">{{ __('messages.Retreat') }}</button>
                                <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
                                    <span class="indicator-label">{{ __('messages.Save') }}</span>
                                    <span class="indicator-progress">Please wait... <span
                                            class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    @endsection
    @section('js')
        <script>
            function editData(id, name, image, email, phone) {
                document.getElementById("edit-user-id").value = id;
                document.getElementById("edit-name").value = name;
                document.getElementById("edit-phone").value = phone;
                document.getElementById("edit-email").value = email;
                document.getElementById("edit-image").style.background = `url('${image}')`

            }
        </script>
    @endsection
