<?php

namespace App\Providers;

use App\Models\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $allCounts = Requests::select('type', 'status', DB::raw('count(*) as total'))
                ->whereIn('type', ['home_services', 'car_services', 'trip'])
                ->groupBy('type', 'status')
                ->get();

            $requestsCounts = [
                'home_services' => [
                    'pending'   => 0,
                    'accepted'  => 0,
                    'completed' => 0,
                    'cancelled' => 0,
                ],
                'car_services' => [
                    'pending'   => 0,
                    'accepted'  => 0,
                    'completed' => 0,
                    'cancelled' => 0,
                ],
                'trip' => [
                    'pending'   => 0,
                    'accepted'  => 0,
                    'completed' => 0,
                    'cancelled' => 0,
                ],
            ];

            foreach ($allCounts as $row) {
                $requestsCounts[$row->type][$row->status] = $row->total;
            }

            $view->with('requestsCounts', $requestsCounts);
        });
    }
}
