<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Trip;
use App\Models\Comments;
use App\Models\Droppin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class Dashboard extends Component
{
    public $weekly_users_count;
    public $monthly_users_count;
    public $total_users_count;
    public $diff_users_count;
    public $total_trips_count;
    public $diff_trips_count;
    public $total_comments_count;
    public $diff_comments_count;
    public $total_droppins_count;
    public $diff_droppins_count;
    public $weekly_user_data;
    public $weekly_trip_data;
    public $weekly_comment_data;
    public $weekly_data;
    public $monthly_user_data;
    public $montly_trip_data;
    public $montly_comment_data;
    public $monthly_data;

    public function mount() {
        $this->total_users_count = User::count();
        $this->total_users_count = number_format($this->total_users_count);

        $last_month_total_users_count = User::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                                                ->count();

        $this_month_total_users_count = User::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->month)
                                                ->count();

        $this->diff_users_count = $this_month_total_users_count - $last_month_total_users_count;

        $this->total_trips_count = Trip::count();
        $this->total_trips_count = number_format($this->total_trips_count);

        $last_month_total_trips_count = Trip::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                                                ->count();

        $this_month_total_trips_count = Trip::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->month)
                                                ->count();

        $this->diff_trips_count = $this_month_total_trips_count - $last_month_total_trips_count;

        $this->total_comments_count = Comments::count();
        $this->total_comments_count = number_format($this->total_comments_count);

        $last_month_total_comments_count = Comments::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                                                ->count();

        $this_month_total_comments_count = Comments::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->month)
                                                ->count();

        $this->diff_comments_count = $this_month_total_comments_count - $last_month_total_comments_count;

        $this->total_droppins_count = Droppin::count();
        $this->total_droppins_count = number_format($this->total_droppins_count);

        $last_month_total_droppins_count = Droppin::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                                                ->count();

        $this_month_total_droppins_count = Droppin::whereYear('created_at', Carbon::now()->year)
                                                ->whereMonth('created_at', Carbon::now()->month)
                                                ->count();

        $this->diff_droppins_count = $this_month_total_droppins_count - $last_month_total_droppins_count;

        // ////////////////////////////////////////////////////////////////////// //
        $temp_weekly_user_data = DB::table('users')
                                        ->selectRaw('COUNT(id) as total_users, DAYNAME(created_at) as day_of_week')
                                        ->whereRaw('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)')
                                        ->groupByRaw('DAYOFWEEK(created_at), created_at')
                                        ->orderByRaw('DAYOFWEEK(created_at)')
                                        ->get();

        $days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $this->weekly_user_data = [];
        for($i = 0; $i < count($days_of_week); $i ++) {
            $this->weekly_user_data[] = 0;
        }

        for($i = 0; $i < count($days_of_week); $i ++) {
            foreach($temp_weekly_user_data as $day) {
                if($days_of_week[$i] == $day->day_of_week) {
                    $this->weekly_user_data[$i] += number_format($day->total_users, 3);
                }
            }
        }

        $temp_weekly_trip_data = DB::table('trips')
                                    ->selectRaw('COUNT(id) as total_trips, DAYNAME(created_at) as day_of_week')
                                    ->whereRaw('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)')
                                    ->groupByRaw('DAYOFWEEK(created_at), created_at')
                                    ->orderByRaw('DAYOFWEEK(created_at)')
                                    ->get();

        $days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $this->weekly_trip_data = [];
        for($i = 0; $i < count($days_of_week); $i ++) {
            $this->weekly_trip_data[] = 0;
        }

        for($i = 0; $i < count($days_of_week); $i ++) {
            foreach($temp_weekly_trip_data as $day) {
                if($days_of_week[$i] == $day->day_of_week) {
                    $this->weekly_trip_data[$i] += number_format($day->total_trips, 3);
                }
            }
        }

        $temp_weekly_comments_data = DB::table('comments')
                                        ->selectRaw('COUNT(id) as total_comments, DAYNAME(created_at) as day_of_week')
                                        ->whereRaw('YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)')
                                        ->groupByRaw('DAYOFWEEK(created_at), created_at')
                                        ->orderByRaw('DAYOFWEEK(created_at)')
                                        ->get();

        $days_of_week = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $this->weekly_comment_data = [];
        for($i = 0; $i < count($days_of_week); $i ++) {
            $this->weekly_comment_data[] = 0;
        }

        for($i = 0; $i < count($days_of_week); $i ++) {
            foreach($temp_weekly_comments_data as $day) {
                if($days_of_week[$i] == $day->day_of_week) {
                    $this->weekly_comment_data[$i] += number_format($day->total_comments, 3);
                }
            }
        }

        $this->weekly_data = [
            [
                "name" => "User",
                "data" => $this->weekly_user_data
            ],
            [
                "name" => "Trip",
                "data" => $this->weekly_trip_data
            ],
            [
                "name" => "Comment",
                "data" => $this->weekly_comment_data
            ]
        ];        

        // ///////////////////////////////////////////////////////// //
        $tmp_monthly_user_data = DB::table('users')
                                ->selectRaw('COUNT(id) as total_count, MONTHNAME(created_at) as month')
                                ->whereRaw('YEAR(created_at) = YEAR(CURDATE())')
                                ->groupByRaw('MONTH(created_at), month')
                                ->orderByRaw('MONTH(created_at)')
                                ->get();

        $months_of_year = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $this->monthly_user_data = [];
        for($i = 0; $i < count($months_of_year); $i ++) {
            $this->monthly_user_data[] = 0;
        }

        for($i = 0; $i < count($months_of_year); $i ++) {
            foreach($tmp_monthly_user_data as $month) {
                if($months_of_year[$i] == $month->month) {
                    $this->monthly_user_data[$i] += number_format($month->total_count, 3);
                }
            }
        }

        $tmp_montly_trip_data = DB::table('trips')
                                        ->selectRaw('COUNT(id) as total_count, MONTHNAME(created_at) as month')
                                        ->whereRaw('YEAR(created_at) = YEAR(CURDATE())')
                                        ->groupByRaw('MONTH(created_at), month')
                                        ->orderByRaw('MONTH(created_at)')
                                        ->get();

        $months_of_year = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $this->montly_trip_data = [];
        for($i = 0; $i < count($months_of_year); $i ++) {
            $this->montly_trip_data[] = 0;
        }

        for($i = 0; $i < count($months_of_year); $i ++) {
            foreach($tmp_montly_trip_data as $month) {
                if($months_of_year[$i] == $month->month) {
                    $this->montly_trip_data[$i] += number_format($month->total_count, 3);
                }
            }
        }

        $tmp_montly_comment_data = DB::table('comments')
                                        ->selectRaw('COUNT(id) as total_count, MONTHNAME(created_at) as month')
                                        ->whereRaw('YEAR(created_at) = YEAR(CURDATE())')
                                        ->groupByRaw('MONTH(created_at), month')
                                        ->orderByRaw('MONTH(created_at)')
                                        ->get();

        $months_of_year = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $this->montly_comment_data = [];
        for($i = 0; $i < count($months_of_year); $i ++) {
            $this->montly_comment_data[] = 0;
        }

        for($i = 0; $i < count($months_of_year); $i ++) {
            foreach($tmp_montly_comment_data as $month) {
                if($months_of_year[$i] == $month->month) {
                    $this->montly_comment_data[$i] += number_format($month->total_count, 3);
                }
            }
        }

        $this->monthly_data = [
            [
                "name" => "User",
                "data" => $this->monthly_user_data
            ],
            [
                "name" => "Trip",
                "data" => $this->montly_trip_data
            ],
            [
                "name" => "Comment",
                "data" => $this->montly_comment_data
            ]
        ];
    }

    public function render()
    {
        return view('dashboard');
    }
}
