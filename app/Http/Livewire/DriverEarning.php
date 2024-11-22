<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Review;
use App\Models\Driver;
use Carbon\Carbon;

class DriverEarning extends Component
{
    public $drivers;

    public function mount()
    {
        $this->drivers = Driver::select('id', 'full_name')->get();
        foreach($this->drivers as $driver) {
            $driver->total_earning = Review::where('driver_id', $driver->id)
                                                ->sum('type');
            $driver->month_earning = Review::where('driver_id', $driver->id)
                                                ->whereYear('updated_at', Carbon::now()->year)
                                                ->whereMonth('updated_at', Carbon::now()->month)
                                                ->sum('type');
            $driver->today_earning = Review::where('driver_id', $driver->id)
                                                ->whereDate('updated_at', Carbon::today())
                                                ->sum('type');
        }
    }

    public function render()
    {
        return view('livewire.driverearning');
    }
}
