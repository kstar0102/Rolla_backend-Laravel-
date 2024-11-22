<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\ReviewRider;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RiderRatingDetails extends Component
{
    public $details;

    public function mount($id)
    {
        $this->details = User::select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))->where('id', $id)->first();
        $this->details->total_reviews = ReviewRider::where('rider_id', $this->details->id)->count('rider_id');
        $this->details->reviews = DB::table("reviewrider")
                                        ->select("reviewrider.*", "drivers.full_name as driver_name")
                                        ->leftJoin("drivers", "drivers.id", "=", "reviewrider.driver_id")
                                        ->where('reviewrider.rider_id', $this->details->id)
                                        ->get();

        $this->details->total_rating = ReviewRider::where('rider_id', $this->details->id)->sum('rating');

        if($this->details->total_reviews > 0) {
            $this->details->avg_rating = $this->details->total_rating / $this->details->total_reviews;
        } else {
            $this->details->avg_rating = 0;
        }
    }

    public function delete($id) {
        ReviewRider::where('id', $id)->delete();
        session()->flash('success', 'Review deleted successfully');

        $this->details = User::select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))->where('id', $this->details->id)->first();

        $this->details->total_reviews = ReviewRider::where('rider_id', $this->details->id)->count('rider_id');
        $this->details->reviews = DB::table("reviewrider")
                                        ->select("reviewrider.*", "drivers.full_name as driver_name")
                                        ->leftJoin("drivers", "drivers.id", "=", "reviewrider.driver_id")
                                        ->where('reviewrider.rider_id', $this->details->id)
                                        ->get();

        $this->details->total_rating = ReviewRider::where('rider_id', $this->details->id)->sum('rating');

        if($this->details->total_reviews > 0) {
            $this->details->avg_rating = $this->details->total_rating / $this->details->total_reviews;
        } else {
            $this->details->avg_rating = 0;
        }
    }

    public function render()
    {
        return view('livewire.riderratingdetails');
    }
}
