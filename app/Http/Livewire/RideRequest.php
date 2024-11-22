<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class RideRequest extends Component
{
    public $requests;

    public function mount()
    {
        $this->requests = DB::table("orders")
                                ->select("orders.*", "drivers.full_name as driver_name", DB::raw("CONCAT(users.first_name, ' ', users.last_name) as rider_name"))
                                ->leftJoin("drivers", "drivers.id", "=", "orders.driver_id")
                                ->leftJoin("users", "users.id", "=", "orders.rider_id")
                                ->get();
    }

    public function remove($id) {
        $request = Order::find($id);
        $request->delete();
        $this->requests = DB::table("orders")
                                ->select("orders.*", "drivers.full_name as driver_name", DB::raw("CONCAT(users.first_name, ' ', users.last_name) as rider_name"))
                                ->leftJoin("drivers", "drivers.id", "=", "orders.driver_id")
                                ->leftJoin("users", "users.id", "=", "orders.rider_id")
                                ->get();
    }

    public function render()
    {
        return view('livewire.riderequest');
    }
}
