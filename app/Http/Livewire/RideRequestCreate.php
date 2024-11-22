<?php
namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use App\Models\Request;
use App\Models\Rider;
use App\Models\Driver;
use Carbon\Carbon;

class RideRequestCreate extends Component
{
    public $rider;
    public $driver;
    public $start_location;
    public $end_location;
    public $request_date;
    public $status;
    public $riders;
    public $drivers;
    public $driverQuery;

    public function mount(Request $request)
    {
        $this->rider = $request->rider;
        $this->driver = $request->driver;
        $this->start_location = $request->start_location;
        $this->end_location = $request->end_location;
        $this->request_date = $request->request_date;
        $this->status = $request->status;
        $this->driverQuery = $request->driverQuery;
    }

    public function resetDrivers()
    {
        $this->driverQuery = '';
        $this->drivers = [];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, [
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
        ]);

        sleep(1);
        $this->drivers = Driver::whereRaw("CONCAT(first_name, ' ', last_name) like '%" . $this->driverQuery . "%'")->get()->toArray();
    }

    public function save()
    {
        $validatedData = $this->validate([
            'rider' => 'required|numeric',
            'driver' => 'required|numeric',
            'start_location' => 'required|string|max:255',
            'end_location' => 'required|string|max:255',
        ]);

        $validatedData['request_date'] = Carbon::now();
        $validatedData['status'] = 0;

        try {
            $this->rider->create($validatedData);
            session()->flash('message', 'New ride requested');
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                $this->addError('email', 'The email has already been taken.');
            } else {
                $this->addError('general', 'Failed to create new rider due to unexpected error.');
            }
        }
    }

    public function render()
    {
        return view('livewire.riderequestcreate');
    }
}
