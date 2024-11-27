<div>
    <title>Trip management</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Trips List</li>
                </ol>
            </nav>
            <h2 class="h4">All Trips</h2>
        </div>
        <div class="btn-toolbar mb-2 mb-md-0">
        </div>
    </div>
    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table table-flush" id="datatable">
            <thead class="thead-light">
                <tr>
                    <th>User</th>
                    <th>Start Address</th>
                    <th>Destination Address</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Miles</th>
                    <th>Sound</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trips as $item)
                    @php
                        $start_address = strlen($item->start_address) > 30 ? substr($item->start_address, 0, 30) . "..." : $item->start_address;
                        $destination_address = strlen($item->destination_address) > 30 ? substr($item->destination_address, 0, 30) . "..." : $item->destination_address;
                    @endphp
                    <tr>
                        <td>{{ $item->user->first_name }} {{ $item->user->last_name }}</td>
                        <td>{{ $start_address }}</td>
                        <td>{{ $destination_address }}</td>
                        <td>{{ $item->trip_start_date }}</td>
                        <td>{{ $item->trip_end_date }}</td>
                        <td>{{ $item->trip_miles }}</td>
                        <td>{{ $item->trip_sound }}</td>
                        <td>
                            <div class="d-flex aligh-items-center">
                                <a href="/trip/details/{{ $item->id }}" class="me-md-1">
                                    <i class="fas fa-eye text-info"></i>
                                </a>
                                <a href="javascript: remove_request({{ $item->id }});" class="me-md-1">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-gray'
        },
        buttonsStyling: false
    });
    
    function remove_request(id) {
        swalWithBootstrapButtons.fire({
            title: 'Are you sure you want to delete?',
            showCancelButton: true,
            confirmButtonClass: "btn-danger me-2",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then((res) => {
            if(res.isConfirmed) {
                @this.call('remove', id);
            }
        });
    }
</script>