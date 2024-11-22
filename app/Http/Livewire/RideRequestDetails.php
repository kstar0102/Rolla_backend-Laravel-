<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RideRequestDetails extends Component
{
    public $driver_photo;
    public $driver_name;
    public $driver_phone_number;
    public $driver_email;
    public $driver_rating;
    public $rider_photo;
    public $rider_name;
    public $rider_phone_number;
    public $rider_email;
    public $rider_rating;
    public $cost;
    public $start_location;
    public $stop_location;
    public $end_location;
    public $route_distance;
    public $order_date;
    public $arrived_date;
    public $start_date;
    public $end_date;
    public $status;

    public function mount($id) {
        $ridedetails = DB::table("orders")
                                    ->select("orders.*", DB::raw("drivers.full_name as driver_name"), DB::raw("CONCAT(users.first_name, ' ', users.last_name) as rider_name"), "drivers.driver_photo as driver_photo", "drivers.email as driver_email", "drivers.phone_number as driver_phone_number", "drivers.rating as driver_rating", "users.email as rider_email", "users.phone_number as rider_phone_number", "users.rating as rider_rating")
                                    ->leftJoin("drivers", "drivers.id", "=", "orders.driver_id")
                                    ->leftJoin("users", "users.id", "=", "orders.rider_id")
                                    ->where("orders.id", $id)
                                    ->first();

        $this->driver_photo = $ridedetails->driver_photo;
        $this->driver_name = $ridedetails->driver_name;
        $this->driver_phone_number = $ridedetails->driver_phone_number;
        $this->driver_email = $ridedetails->driver_email;
        $this->driver_rating = $ridedetails->driver_rating;
        $this->rider_name = $ridedetails->rider_name;
        $this->rider_phone_number = $ridedetails->rider_phone_number;
        $this->rider_email = $ridedetails->rider_email;
        $this->rider_rating = $ridedetails->rider_rating;
        $this->cost = $ridedetails->cost;
        $this->start_location = $ridedetails->start_location;
        $this->stop_location = $ridedetails->stop_location;
        $this->end_location = $ridedetails->end_location;
        $this->route_distance = $ridedetails->route_distance;
        $this->order_date = $ridedetails->order_date;
        $this->arrived_date = $ridedetails->arrived_date;
        $this->start_date = $ridedetails->start_date;
        $this->end_date = $ridedetails->end_date;
        $this->status = $ridedetails->status;
    }

    public function render()
    {
        return view('livewire.riderequestdetails');
    }
}
