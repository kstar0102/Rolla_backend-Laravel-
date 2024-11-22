<title>Feedback management</title>
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
                <li class="breadcrumb-item active" aria-current="page">Feedback List</li>
            </ol>
        </nav>
        <h2 class="h4">Feedback List</h2>
        <p class="mb-0">Manages Feedback.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        
    </div>
</div>
<div class="card card-body shadow border-0 table-wrapper table-responsive">
    <table class="table table-flush" id="datatable">
        <thead class="thead-light">
            <tr>
                <th>Rider</th>
                <th>Content</th>
                <th>Feedback Type</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tiger Nixon</td>
                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit ... </td>
                <td>
                    <span class="text-capitalize badge bg-danger p-sm-2">Complaint</span>
                </td>
                <td>
                    <span class="text-capitalize badge bg-warning p-sm-2">Pending</span>
                </td>
                <td>
                    2024-05-28
                </td>
                <td>
                    <div class="d-flex aligh-items-center">
                        <a href="/feedback/1" class="me-md-1">
                            <i class="fas fa-edit text-primary"></i>
                        </a>
                        <span class="me-md-1 feedback-del-btn" data-id="1">
                            <i class="fas fa-trash text-danger"></i>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Alex Miller</td>
                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit ... </td>
                <td>
                    <span class="text-capitalize badge bg-info p-sm-2">Suggestion</span>
                </td>
                <td>
                    <span class="text-capitalize badge bg-info p-sm-2">Pending</span>
                </td>
                <td>
                    2024-05-28
                </td>
                <td>
                    <div class="d-flex aligh-items-center">
                        <a href="/feedback/1" class="me-md-1">
                            <i class="fas fa-edit text-primary"></i>
                        </a>
                        <span class="me-md-1 feedback-del-btn" data-id="1">
                            <i class="fas fa-trash text-danger"></i>
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Alex Miller</td>
                <td>Lorem ipsum dolor sit amet, consectetur adipiscing elit ... </td>
                <td>
                    <span class="text-capitalize badge bg-success p-sm-2">Praise</span>
                </td>
                <td>
                    <span class="text-capitalize badge bg-success p-sm-2">Resolved</span>
                </td>
                <td>
                    2024-05-28
                </td>
                <td>
                    <div class="d-flex aligh-items-center">
                        <a href="/feedback/1" class="me-md-1">
                            <i class="fas fa-edit text-primary"></i>
                        </a>
                        <span class="me-md-1 feedback-del-btn" data-id="1">
                            <i class="fas fa-trash text-danger"></i>
                        </span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script src="../../assets/js/feedback.js"></script>