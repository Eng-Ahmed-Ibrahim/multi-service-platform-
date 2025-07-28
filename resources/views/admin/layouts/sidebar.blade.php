@php
    $user = Auth::guard('admin')->user();
@endphp

<style>
    [data-kt-app-layout=dark-sidebar] .app-sidebar .menu > .menu-item .menu-link.active {
    transition: color 0.2s ease;
    background-color: #1C1C21;
    color: var(--bs-primary-inverse);
    padding-right: 0;
}
</style>

<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo"
        style="display: flex;align-items: center;justify-content: center;">
        <!--begin::Logo image-->
        <a href="{{ route('admin.dashboard') }}">
            <img alt="Logo" style="    height: 80px !important;" src="{{ asset('static/home_and_car.png') }}"
                class=" app-sidebar-logo-default" />
            <img alt="Logo" style="    height: 80px !important;" src="{{ asset('static/home_and_car.png') }}"
                class="h-20px app-sidebar-logo-minimize" />
        </a>
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
                data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
                data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px"
                data-kt-scroll-save-state="true">
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
                    data-kt-menu="true" data-kt-menu-expand="false">

                    <div class="menu-item pt-5">
                        <div class="menu-content">
                            <span class="menu-heading fw-bold text-uppercase fs-7">{{ __('messages.Home') }}</span>
                        </div>
                    </div>
                    <!-- Dashboard -->
                    <div class="menu-item">
                        <!--begin:Menu link-->
                        <a class="menu-link" href="{{ route('admin.dashboard') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-element-11 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                    <span class="path5"></span>
                                    <span class="path6"></span>
                                </i>
                            </span>
                            <span class="menu-title">{{ __('messages.Dashboard') }}</span>
                        </a>
                        <!--end:Menu link-->
                    </div>

                    @can('show notifications')
                        <!-- Notification -->

                        <div class="menu-item pt-5">
                            <div class="menu-content">
                                <span
                                    class="menu-heading fw-bold text-uppercase fs-7">{{ __('messages.Notifications') }}</span>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">

                            <!--begin:Menu link-->
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-bank fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                                <span class="menu-title">{{ __('messages.Notifications') }}</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <!--end:Menu link-->

                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">

                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a href="{{ route('admin.notifications.index') }}"
                                        class="menu-link {{ request()->path() == 'admin/notifications' ? 'active' : ' ' }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">{{ __('messages.List') }}</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>




                            </div>
                            <!--end:Menu sub-->
                        </div>
                    @endcan
                    @can('show coupons')
                        <!-- Coupons -->
                        <div class="menu-item pt-5">
                            <div class="menu-content">
                                <span class="menu-heading fw-bold text-uppercase fs-7">{{ __('messages.Coupons') }}</span>
                            </div>
                        </div>
                        <!-- Dashboard -->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a href="{{ route('admin.coupons.index') }}" class="menu-link">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-element-11 fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                    </i>
                                </span>
                                <span class="menu-title">{{ __('messages.Coupons') }}</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                    @endcan


                    <!-- Admin&Roles -->
                    @if ($user->can('view roles') || $user->can('view admins'))
                        <div class="menu-item pt-5">
                            <div class="menu-content">
                                <span
                                    class="menu-heading fw-bold text-uppercase fs-7">{{ __('messages.Admin&Roles') }}</span>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">

                            <!--begin:Menu link-->
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-bank fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                                <span class="menu-title">{{ __('messages.Admin&Roles') }}</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <!--end:Menu link-->

                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">

                                @can('view roles')
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a href="{{ route('admin.roles.index') }}"
                                            class="menu-link {{ request()->path() == 'admin/roles' ? 'active' : ' ' }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ __('messages.Roles') }}</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                @endcan

                                @can('view admins')
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a href="{{ route('admin.admins.index') }}"
                                            class="menu-link {{ request()->path() == 'admin/admins' ? 'active' : ' ' }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ __('messages.Admins') }}</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                @endcan


                            </div>
                            <!--end:Menu sub-->
                        </div>
                    @endif
                    <!-- End Admin&Roles -->
                    <!-- Members -->
                    @if ($user->can('view users') || $user->can('view drivers') || $user->can('view handymans'))
                        <div class="menu-item pt-5">
                            <div class="menu-content">
                                <span
                                    class="menu-heading fw-bold text-uppercase fs-7">{{ __('messages.Members') }}</span>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">

                            <!--begin:Menu link-->
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-bank fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                                <span class="menu-title">{{ __('messages.Members') }}</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <!--end:Menu link-->

                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">

                                @can('view users')
                                    <div class="menu-item">
                                        <a href="{{ route('admin.users.index') }}"
                                            class="menu-link {{ request()->path() == 'admin/users' ? 'active' : ' ' }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ __('messages.Users') }}</span>
                                        </a>
                                    </div>
                                @endcan

                                @can('view drivers')
                                    <div class="menu-item">
                                        <a href="{{ route('admin.drivers.index') }}"
                                            class="menu-link {{ request()->path() == 'admin/drivers' ? 'active' : ' ' }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ __('messages.Drivers') }}</span>
                                        </a>
                                    </div>
                                @endcan
                                @can('view handymans')
                                    <div class="menu-item">
                                        <a href="{{ route('admin.handymans.index') }}"
                                            class="menu-link {{ request()->path() == 'admin/handymans' ? 'active' : ' ' }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ __('messages.Handymans') }}</span>
                                        </a>
                                    </div>
                                @endcan


                            </div>
                            <!--end:Menu sub-->
                        </div>
                    @endif
                    <!-- End Members -->

                    @if (
                        $user->can('show home services requests') ||
                            $user->can('show car services requests') ||
                            $user->can('show car transportations'))
                        <!-- Requests -->
                        <div class="menu-item pt-5">
                            <div class="menu-content">
                                <span
                                    class="menu-heading fw-bold text-uppercase fs-7">{{ __('messages.Requests') }}</span>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">

                            <!--begin:Menu link-->
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-bank fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                                <span class="menu-title">{{ __('messages.Requests') }}</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <!--end:Menu link-->

                            <!--begin:Menu sub-->


                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">

                                @can('show home services requests')
                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">

                                        <!--begin:Menu link-->
                                        <span class="menu-link">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ __('messages.home_services') }}</span>
                                            <span class="menu-arrow"></span>
                                        </span>
                                        <div class="menu-sub menu-sub-accordion">
                                            <div class="menu-item">
                                                <a href="{{ route('admin.requests.index', 'home-services') . '?status=pending' }}"
                                                    class="menu-link {{ request('status') == 'pending' ? 'active' : '' }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">{{ __('messages.Pending') }}</span>
                                                    <span class="badge badge-sm bg-light text-dark ms-2">
                                                        {{ $requestsCounts['home-services']['pending'] ?? 0 }}
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a href="{{ route('admin.requests.index', 'home-services') . '?status=accepted' }}"
                                                    class="menu-link {{ request('status') == 'accepted' ? 'active' : '' }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">{{ __('messages.Accepted') }}</span>
                                                    <span class="badge badge-sm bg-light text-dark ms-2">
                                                        {{ $requestsCounts['home-services']['accepted'] ?? 0 }}
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a href="{{ route('admin.requests.index', 'home-services') . '?status=completed' }}"
                                                    class="menu-link {{ request('status') == 'completed' ? 'active' : '' }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">{{ __('messages.Completed') }}</span>
                                                    <span class="badge badge-sm bg-light text-dark ms-2">
                                                        {{ $requestsCounts['home-services']['completed'] ?? 0 }}
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a href="{{ route('admin.requests.index', 'home-services') . '?status=cancelled' }}"
                                                    class="menu-link {{ request('status') == 'cancelled' ? 'active' : '' }}">
                                                    <span class="menu-bullet">
                                                        <span class="bullet bullet-dot"></span>
                                                    </span>
                                                    <span class="menu-title">{{ __('messages.Canceled') }}</span>
                                                    <span class="badge badge-sm bg-light text-dark ms-2">
                                                        {{ $requestsCounts['home-services']['cancelled'] ?? 0 }}
                                                    </span>
                                                </a>
                                            </div>
                                        </div>


                                    </div>
                                @endcan

                                {{-- خدمات السيارات --}}
                                @can('show car services requests')
                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                        <span class="menu-link">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ __('messages.car_services') }}</span>
                                            <span class="menu-arrow"></span>
                                        </span>
                                        <div class="menu-sub menu-sub-accordion">
                                            @php $carServiceType = 'car-services'; @endphp

                                            <div class="menu-item">
                                                <a href="{{ route('admin.requests.index', $carServiceType) . '?status=pending' }}"
                                                    class="menu-link {{ request('status') == 'pending' && request()->segment(3) == $carServiceType ? 'active' : '' }}">
                                                    <span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">{{ __('messages.Pending') }}</span>
                                                    <span class="badge badge-sm bg-light text-dark ms-2">
                                                        {{ $requestsCounts[$carServiceType]['pending'] ?? 0 }}
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a href="{{ route('admin.requests.index', $carServiceType) . '?status=accepted' }}"
                                                    class="menu-link {{ request('status') == 'accepted' && request()->segment(3) == $carServiceType ? 'active' : '' }}">
                                                    <span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">{{ __('messages.Accepted') }}</span>
                                                    <span class="badge badge-sm bg-light text-dark ms-2">
                                                        {{ $requestsCounts[$carServiceType]['accepted'] ?? 0 }}
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a href="{{ route('admin.requests.index', $carServiceType) . '?status=completed' }}"
                                                    class="menu-link {{ request('status') == 'completed' && request()->segment(3) == $carServiceType ? 'active' : '' }}">
                                                    <span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">{{ __('messages.Completed') }}</span>
                                                    <span class="badge badge-sm bg-light text-dark ms-2">
                                                        {{ $requestsCounts[$carServiceType]['completed'] ?? 0 }}
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a href="{{ route('admin.requests.index', $carServiceType) . '?status=cancelled' }}"
                                                    class="menu-link {{ request('status') == 'cancelled' && request()->segment(3) == $carServiceType ? 'active' : '' }}">
                                                    <span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">{{ __('messages.Canceled') }}</span>

                                                    <span class="badge badge-sm bg-light text-dark ms-2">
                                                        {{ $requestsCounts[$carServiceType]['cancelled'] ?? 0 }}
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endcan

                                {{-- رحلات السيارات --}}
                                @can('show car transportations requests')
                                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                                        <span class="menu-link">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ __('messages.car_transportations') }}</span>
                                            <span class="menu-arrow"></span>
                                        </span>
                                        <div class="menu-sub menu-sub-accordion">
                                            @php $tripType = 'trip'; @endphp

                                            <div class="menu-item">
                                                <a href="{{ route('admin.requests.index', $tripType) . '?status=pending' }}"
                                                    class="menu-link {{ request('status') == 'pending' && request()->segment(3) == $tripType ? 'active' : '' }}">
                                                    <span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">{{ __('messages.Pending') }}</span>
                                                    <span class="badge badge-sm bg-light text-dark ms-2">
                                                        {{ $requestsCounts[$tripType]['pending'] ?? 0 }}
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a href="{{ route('admin.requests.index', $tripType) . '?status=accepted' }}"
                                                    class="menu-link {{ request('status') == 'accepted' && request()->segment(3) == $tripType ? 'active' : '' }}">
                                                    <span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">{{ __('messages.Accepted') }}</span>
                                                    <span class="badge badge-sm bg-light text-dark ms-2">
                                                        {{ $requestsCounts[$tripType]['accepted'] ?? 0 }}
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a href="{{ route('admin.requests.index', $tripType) . '?status=completed' }}"
                                                    class="menu-link {{ request('status') == 'completed' && request()->segment(3) == $tripType ? 'active' : '' }}">
                                                    <span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">{{ __('messages.Completed') }}</span>
                                                    <span class="badge badge-sm bg-light text-dark ms-2">
                                                        {{ $requestsCounts[$tripType]['completed'] ?? 0 }}
                                                    </span>
                                                </a>
                                            </div>
                                            <div class="menu-item">
                                                <a href="{{ route('admin.requests.index', $tripType) . '?status=cancelled' }}"
                                                    class="menu-link {{ request('status') == 'cancelled' && request()->segment(3) == $tripType ? 'active' : '' }}">
                                                    <span class="menu-bullet"><span
                                                            class="bullet bullet-dot"></span></span>
                                                    <span class="menu-title">{{ __('messages.Canceled') }}</span>
                                                    <span class="badge badge-sm bg-light text-dark ms-2">
                                                        {{ $requestsCounts[$tripType]['cancelled'] ?? 0 }}
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endcan





                            </div>
                            <!--end:Menu sub-->
                        </div>
                        <!-- End Requests -->
                    @endif
                    <!-- Services -->
                    @if ($user->can('show home services') || $user->can('show car services') || $user->can('show car transportations'))
                        <div class="menu-item pt-5">
                            <div class="menu-content">
                                <span
                                    class="menu-heading fw-bold text-uppercase fs-7">{{ __('messages.Services') }}</span>
                            </div>
                        </div>
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">

                            <!--begin:Menu link-->
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-bank fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                    </i>
                                </span>
                                <span class="menu-title">{{ __('messages.Services') }}</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <!--end:Menu link-->

                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion">

                                @can('show home services')
                                    <div class="menu-item">
                                        <a href="{{ route('admin.services.index', 'home-services') }}"
                                            class="menu-link {{ request()->path() == 'admin/services/home-services' ? 'active' : ' ' }}">

                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ __('messages.home_services') }}</span>
                                        </a>

                                    </div>
                                @endcan

                                @can('show car services')
                                    <div class="menu-item">
                                        <a href="{{ route('admin.services.index', 'car-services') }}"
                                            class="menu-link {{ request()->path() == 'admin/services/car-services' ? 'active' : ' ' }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ __('messages.car_services') }}</span>
                                        </a>
                                    </div>
                                @endcan
                                @can('show car transportations')
                                    <div class="menu-item">
                                        <a href="{{ route('admin.services.index', 'car-transportations') }}"
                                            class="menu-link {{ request()->path() == 'admin/services/car-transportations' ? 'active' : ' ' }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ __('messages.car_transportations') }}</span>
                                        </a>
                                    </div>
                                @endcan


                            </div>
                            <!--end:Menu sub-->
                        </div>
                    @endif

                    <!-- End Members -->
                    @can('view settings')
                        <div class="menu-item pt-5">
                            <div class="menu-content">
                                <span
                                    class="menu-heading fw-bold text-uppercase fs-7">{{ __('messages.Settings') }}</span>
                            </div>
                        </div>

                        <div class="menu-item">
                            <a href="{{ route('admin.settings.index') }}"
                                class="menu-link {{ request()->path() == 'admin/settings' ? 'active' : ' ' }}">
                                <span class="menu-icon">
                                    <i class="ki-duotone ki-element-11 fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                        <span class="path3"></span>
                                        <span class="path4"></span>
                                        <span class="path5"></span>
                                        <span class="path6"></span>
                                    </i>
                                </span>
                                <span class="menu-title">{{ __('messages.Settings') }}</span>
                            </a>
                        </div>
                    @endcan







                </div>
                <!--end::Menu-->
            </div>
            <!--end::Scroll wrapper-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->

</div>
