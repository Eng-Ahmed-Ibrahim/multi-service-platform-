@extends('admin.app')
@section('title', trans('messages.Dashboard'))
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        {{ __('messages.Dashboard') }}</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a class="text-muted text-hover-primary">{{ __('messages.Home') }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">{{ __('messages.Dashboard') }}</li>
                    </ul>
                </div>

            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="card">
                    <div class="card-body p-lg-17">

                        <div class="row">
                            <!-- Providers Chart -->
                            <div class="col-md-6 mb-4">
                                <div >
                                    <div class="card-header">{{ __('messages.Providers_by_Role') }}</div>
                                    <div class="card-body">
                                        <canvas id="providersChart"></canvas>
                                    </div>
                                </div>
                            </div>

                            <!-- Requests Chart -->
                            <div class="col-md-6 mb-4">
                                <div >
                                    <div class="card-header"> {{ __('messages.Requests_by_Type') }}</div>
                                    <div class="card-body">
                                        <canvas id="requestsChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection


@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Providers Data
        const providersData = {
            labels: {!! json_encode($providersCount->keys()) !!},
            datasets: [{
                label: "{{ __('messages.Providers_Count') }}",
                data: {!! json_encode($providersCount->values()) !!},
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc']
            }]
        };

        // Requests Data
        const requestsData = {
            labels: {!! json_encode($requestsCount->keys()) !!},
            datasets: [{
                label: "{{ __('messages.Requests_Count') }}",
                data: {!! json_encode($requestsCount->values()) !!},
                backgroundColor: ['#f6c23e', '#e74a3b', '#858796']
            }]
        };

        const config1 = {
            type: 'bar',
            data: providersData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        const config2 = {
            type: 'bar',
            data: requestsData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        };

        new Chart(document.getElementById('providersChart'), config1);
        new Chart(document.getElementById('requestsChart'), config2);
    </script>
@endsection
