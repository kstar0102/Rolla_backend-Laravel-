<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\Driver;
use App\Models\User;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class Dashboard extends Component
{
    public $total_earning;
    public $total_drivers;
    public $diff_drivers;
    public $total_riders;
    public $diff_riders;
    public $monthly_earning;
    public $diff_monthly_earning;
    public $total_request;
    public $request_progress;
    public $request_completion;
    public $request_cancellation;
    public $driver_incomme_rankings;
    public $monthly_earning_data;
    public $weekly_earning_data;
    public $driver_locations;

    public function mount() {
        $this->total_earning = Order::where('status', '>=', '1')->sum('cost');
        $this->total_earning = number_format($this->total_earning, 2);

        $this->total_drivers = Driver::count();
        $this->total_drivers = number_format($this->total_drivers);

        $last_month_total_drivers = Driver::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                                                ->count();

        $this_month_total_drivers = Driver::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->month)
                                                ->count();

        $this->diff_drivers = $this_month_total_drivers - $last_month_total_drivers;
        
        $this->total_riders = User::count();
        $this->total_riders = number_format($this->total_riders);

        $last_month_total_riders = User::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                                                ->count();

        $this_month_total_riders = User::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->month)
                                                ->count();

        $this->diff_riders = $this_month_total_riders - $last_month_total_riders;

        $this->monthly_earning = Order::where('status', 2)
                                            ->whereYear('arrived_date', Carbon::now()->year)
                                            ->whereMonth('arrived_date', Carbon::now()->month)
                                            ->sum('cost');

        $last_month_earning = Order::where('status', 2)
                                            ->whereYear('arrived_date', Carbon::now()->year)
                                            ->whereMonth('arrived_date', Carbon::now()->subMonth()->month)
                                            ->sum('cost');

        $this->diff_monthly_earning = $this->monthly_earning - $last_month_earning;

        $this->total_request = Order::count();
        $progress = Order::where('status', 0)->count();
        $completion = Order::where('status', 1)->count();
        $cancellation = Order::where('status', 2)->count();

        if($this->total_request == 0) {
            $this->request_progress = 0;
            $this->request_completion = 0;
            $this->request_cancellation = 0;
        } else {
            $this->request_progress = number_format(($progress / $this->total_request) * 100, 1);
            $this->request_completion = number_format(($completion / $this->total_request) * 100, 1);
            $this->request_cancellation = number_format(($cancellation / $this->total_request) * 100, 1);
        }

        $this->driver_incomme_rankings = Driver::select('id', 'driver_photo', 'full_name')->get();

        foreach($this->driver_incomme_rankings as $driver) {
            $driver->total_earning = Review::where('driver_id', $driver->id)
                                            ->sum('type');
        }

        $this->driver_incomme_rankings = $this->driver_incomme_rankings->sortByDesc('total_earning')->take(5);

        $weekly_data = DB::table('orders')
                                ->selectRaw('SUM(cost) as total_cost, DAYNAME(arrived_date) as day_of_week')
                                ->whereRaw('YEARWEEK(arrived_date, 1) = YEARWEEK(CURDATE(), 1)')
                                ->where('status', 2)
                                ->groupByRaw('DAYOFWEEK(arrived_date), arrived_date')
                                ->orderByRaw('DAYOFWEEK(arrived_date)')
                                ->get();
    
        $days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $this->weekly_earning_data = [];
        for($i = 0; $i < count($days_of_week); $i ++) {
            $this->weekly_earning_data[] = 0;
        }

        for($i = 0; $i < count($days_of_week); $i ++) {
            foreach($weekly_data as $day) {
                if($days_of_week[$i] == $day->day_of_week) {
                    $this->weekly_earning_data[$i] += number_format($day->total_cost, 3);
                }
            }
        }

        $monthly_data = DB::table('orders')
                                        ->selectRaw('SUM(cost) as total_cost, MONTHNAME(arrived_date) as month')
                                        ->whereRaw('YEAR(arrived_date) = YEAR(CURDATE())')
                                        ->where('status', 2)
                                        ->groupByRaw('MONTH(arrived_date), month')
                                        ->orderByRaw('MONTH(arrived_date)')
                                        ->get();

        $months_of_year = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $this->monthly_earning_data = [];
        for($i = 0; $i < count($months_of_year); $i ++) {
            $this->monthly_earning_data[] = 0;
        }

        for($i = 0; $i < count($months_of_year); $i ++) {
            foreach($monthly_data as $month) {
                if($months_of_year[$i] == $month->month) {
                    $this->monthly_earning_data[$i] += number_format($month->total_cost, 3);
                }
            }
        }

        $this->driver_locations = DB::table('driver_locations')
                                        ->select(
                                            'driver_locations.lat', 
                                            'driver_locations.lng', 
                                            'drivers.full_name', 
                                            'drivers.id', 
                                            'drivers.driver_photo', 
                                            'drivers.phone_number',
                                            DB::raw('(SELECT o.status FROM orders o WHERE o.driver_id = driver_locations.driver_id ORDER BY o.order_date DESC LIMIT 1) as latest_status')
                                        )
                                        ->leftJoin('drivers', 'drivers.id', '=', 'driver_locations.driver_id')
                                        ->get()
                                        ->map(function ($item) {
                                            return [
                                                (float) $item->lat,
                                                (float) $item->lng,
                                                $item->full_name,
                                                $item->id,
                                                $item->driver_photo,
                                                $item->phone_number,
                                                $item->latest_status
                                            ];
                                        })
                                        ->toArray();
    }

    public function render()
    {
        return view('dashboard');
    }
}
