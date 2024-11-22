<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Transactions extends Component
{
    public $transactions;

    public function mount()
    {
        // $this->transactions = DB::table("orders")
        //                         ->select("orders.*", DB::raw("CONCAT(drivers.full_name) as driver_name"), "riders.name as rider_name")
        //                         ->leftJoin("drivers", "drivers.id", "=", "orders.driver_id")
        //                         ->leftJoin("riders", "riders.id", "=", "orders.rider_id")
        //                         ->get();
        $this->transactions = DB::select("select r.*, d.full_name as driver_name, CONCAT(u.first_name, '', u.last_name) as rider_name from review as r left join drivers as d on r.driver_id = d.id left join users as u on r.rider_id = u.id");
    }

    public function render()
    {
        return view('livewire.transactions');
    }
}
