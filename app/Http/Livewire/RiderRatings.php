<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\ReviewRider;
use App\Models\User;

class RiderRatings extends Component
{
    public $ratings;

    public function mount()
    {
        $this->ratings = User::select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))->get();
        foreach($this->ratings as $item) {
            $item->total_reviews = ReviewRider::where('rider_id', $item->id)
                                                ->count('rider_id');
            $item->total_rating = ReviewRider::where('rider_id', $item->id)
                                                ->sum('rating');

            if($item->total_reviews > 0) {
                $item->avg_rating = $item->total_rating / $item->total_reviews;
            } else {
                $item->avg_rating = 0;
            }
        }
    }

    public function remove($id) {
        $deleted = ReviewRider::where('rider_id', $id)->delete();

        $this->ratings = User::select('id', DB::raw("CONCAT(first_name, ' ', last_name) as name"))->get();
        foreach($this->ratings as $item) {
            $item->total_reviews = ReviewRider::where('rider_id', $item->id)
                                                ->count('rider_id');
            $item->total_rating = ReviewRider::where('rider_id', $item->id)
                                                ->sum('rating');

            if ($item->total_reviews > 0) {
                $item->avg_rating = $item->total_rating / $item->total_reviews;
            } else {
                $item->avg_rating = 0;
            }
        }
    }

    public function render()
    {
        return view('livewire.riderratings');
    }
}
