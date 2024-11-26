<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\User;
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
        
    }

    public function render()
    {
        return view('dashboard');
    }
}
