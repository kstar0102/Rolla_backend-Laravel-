<title>Notification management</title>
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
                <li class="breadcrumb-item"><a href="/">Volt</a></li>
                <li class="breadcrumb-item active" aria-current="page">Notification List</li>
            </ol>
        </nav>
        <h2 class="h4">Notification List</h2>
        <p class="mb-0">Manages Notification</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/notifications/create" class="btn btn-sm btn-gray-800 d-inline-flex align-items-center">
            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            New Notification
        </a>
    </div>
</div>

<div class="card card-body shadow border-0 table-wrapper table-responsive">
    <table class="table table-flush" id="datatable">
        <thead class="thead-light">
            <tr>
                <th>Sender</th>
                <th>Title</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tiger Nixon</td>
                <td>This is a notification title</td>
                <td>This is a notification contents ... </td>
                <td>
                    2024-05-23
                </td>
                <td>
                    <div class="d-flex aligh-items-center">
                        <a href="/notifications/1" class="me-md-1">
                            <i class="fas fa-eye text-primary"></i>
                        </a>
                        <span class="me-md-1 notification-del-btn" data-id="1">
                            <i class="fas fa-trash text-danger"></i>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Tiger Nixon</td>
                <td>This is a notification ...</td>
                <td>This is a notification contents ... </td>
                <td>
                    2024-05-23
                </td>
                <td>
                    <div class="d-flex aligh-items-center">
                        <a href="/notifications/1" class="me-md-1">
                            <i class="fas fa-eye text-primary"></i>
                        </a>
                        <span class="me-md-1 notification-del-btn" data-id="1">
                            <i class="fas fa-trash text-danger"></i>
                        </span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script src="/assets/js/notifications.js"></script>
