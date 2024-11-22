<nav id="sidebarMenu" class="sidebar d-lg-block bg-gray-800 text-white collapse" data-simplebar>
  <div class="sidebar-inner px-2 pt-3">
    <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
      <div class="d-flex align-items-center">
        <div class="avatar-lg me-4">
          <img src="/assets/img/team/profile-picture-3.jpg" class="card-img-top rounded-circle border-white"
            alt="Bonnie Green">
        </div>
        <div class="d-block">
          <h2 class="h5 mb-3">Hi, Jane</h2>
          <a href="/login" class="btn btn-secondary btn-sm d-inline-flex align-items-center">
            <svg class="icon icon-xxs me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Sign Out
          </a>
        </div>
      </div>
      <div class="collapse-close d-md-none">
        <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu"
          aria-expanded="true" aria-label="Toggle navigation">
          <svg class="icon icon-xs" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"></path>
          </svg>
        </a>
      </div>
    </div>
    <ul class="nav flex-column pt-3 pt-md-0">
      <li class="nav-item">
        <a href="/dashboard" class="nav-link d-flex align-items-center">
          <span class="sidebar-icon me-3">
            <img src="/assets/img/logo.png" height="20" width="20" alt="Volt Logo">
          </span>
          <span class="mt-1 ms-1 sidebar-text">
            Kenoride Admin
          </span>
        </a>
      </li>
      <li class="nav-item {{ Request::segment(1) == 'dashboard' ? 'active' : '' }}">
        <a href="/dashboard" class="nav-link">
          <span class="sidebar-icon"> <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
              <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
            </svg></span></span>
          <span class="sidebar-text">Dashboard</span>
        </a>
      </li>
      <li class="nav-item">
        <span
          class="nav-link {{ Request::segment(1) == 'drivers' || Request::segment(1) == 'driver' || Request::segment(1) == 'earning' ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
          data-bs-toggle="collapse" data-bs-target="#submenu-drivers">
          <span>
            <span class="sidebar-icon">
              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                  clip-rule="evenodd"></path>
              </svg>
            </span>
            <span class="sidebar-text">Driver Management</span>
          </span>
          <span class="link-arrow"><svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd"></path>
            </svg></span>
        </span>
        <div class="multi-level collapse {{ Request::segment(1) == 'drivers' || Request::segment(1) == 'driver' || Request::segment(1) == 'earning' ? 'show' : '' }}" role="list"
          id="submenu-drivers" aria-expanded="false">
          <ul class="flex-column nav">
            <li class="nav-item {{ Request::segment(1) == 'drivers' ? 'active' : '' }}">
              <a class="nav-link" href="/drivers">
                <span class="sidebar-text">Drivers</span>
              </a>
            </li>
            <li class="nav-item {{ Request::segment(1) == 'earning' ? 'active' : '' }}">
              <a class="nav-link" href="/earning">
                <span class="sidebar-text">Driver Earning</span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item">
        <span
          class="nav-link {{ Request::segment(1) == 'riders' || Request::segment(1) == 'riderequest' || Request::segment(1) == 'rider' || Request::segment(1) == 'riderequestdetails' ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
          data-bs-toggle="collapse" data-bs-target="#submenu-rides">
          <span>
            <span class="sidebar-icon">
              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                  clip-rule="evenodd"></path>
              </svg>
            </span>
            <span class="sidebar-text">Ride Management</span>
          </span>
          <span class="link-arrow">
            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
          </span>
        </span>
        <div class="multi-level collapse {{ Request::segment(1) == 'riders' || Request::segment(1) == 'riderequest' || Request::segment(1) == 'rider' || Request::segment(1) == 'riderequestdetails' ? 'show' : '' }}" role="list"
          id="submenu-rides" aria-expanded="false">
          <ul class="flex-column nav">
            <li class="nav-item {{ Request::segment(1) == 'riders' ? 'active' : '' }}">
              <a class="nav-link" href="/riders">
                <span class="sidebar-text">Riders</span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      <li class="nav-item {{ Request::segment(1) == 'transactions' ? 'active' : '' }}">
        <a href="/transactions" class="nav-link">
          <span class="sidebar-icon"> <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
              <path d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"></path>
            </svg></span></span>
          <span class="sidebar-text">Transactions</span>
        </a>
      </li>
      <li class="nav-item {{ Request::segment(1) == 'riderequest' ? 'active' : '' }}">
        <a href="/orders" class="nav-link">
          <span class="sidebar-icon">
            <svg class="icon icon-xs me-2" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><script xmlns=""/>
              <rect x="5" y="4" width="14" height="17" rx="2" stroke="#222222"/>
              <path d="M9 9H15" stroke="#222222" stroke-linecap="round"/>
              <path d="M9 13H15" stroke="#222222" stroke-linecap="round"/>
              <path d="M9 17H13" stroke="#222222" stroke-linecap="round"/>
              <script xmlns=""/>
            </svg>
          </span>
          <span class="sidebar-text">Orders</span>
        </a>
      </li>
      <li class="nav-item">
        <span
          class="nav-link {{ Request::segment(1) == 'ratings' || Request::segment(1) == 'feedback' ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
          data-bs-toggle="collapse" data-bs-target="#submenu-ratings">
          <span>
            <span class="sidebar-icon">
              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="m7.171 12.906-2.153 6.411 2.672-.89 1.568 2.34 1.825-5.183m5.73-2.678 2.154 6.411-2.673-.89-1.568 2.34-1.825-5.183M9.165 4.3c.58.068 1.153-.17 1.515-.628a1.681 1.681 0 0 1 2.64 0 1.68 1.68 0 0 0 1.515.628 1.681 1.681 0 0 1 1.866 1.866c-.068.58.17 1.154.628 1.516a1.681 1.681 0 0 1 0 2.639 1.682 1.682 0 0 0-.628 1.515 1.681 1.681 0 0 1-1.866 1.866 1.681 1.681 0 0 0-1.516.628 1.681 1.681 0 0 1-2.639 0 1.681 1.681 0 0 0-1.515-.628 1.681 1.681 0 0 1-1.867-1.866 1.681 1.681 0 0 0-.627-1.515 1.681 1.681 0 0 1 0-2.64c.458-.361.696-.935.627-1.515A1.681 1.681 0 0 1 9.165 4.3ZM14 9a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"
                  clip-rule="evenodd"></path>
              </svg>
            </span>
            <span class="sidebar-text">Rating & Review</span>
          </span>
          <span class="link-arrow">
            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
          </span>
        </span>
        <div class="multi-level collapse {{ Request::segment(1) == 'driverratings' || Request::segment(1) == 'riderratings' ? 'show' : '' }}" role="list"
          id="submenu-ratings" aria-expanded="false">
          <ul class="flex-column nav">
            <li class="nav-item {{ Request::segment(1) == 'driverratings' ? 'active' : '' }}">
              <a class="nav-link" href="/driverratings">
                <span class="sidebar-text">Drivers</span>
              </a>
            </li>
            <li class="nav-item {{ Request::segment(1) == 'riderratings' ? 'active' : '' }}">
              <a class="nav-link" href="/riderratings">
                <span class="sidebar-text">Riders</span>
              </a>
            </li>
          </ul>
        </div>
      </li>
      {{-- <li class="nav-item {{ Request::segment(1) == 'feedback' ? 'active' : '' }}">
        <a href="/feedback" class="nav-link">
          <span class="sidebar-icon">
            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                d="M9 17h6l3 3v-3h2V9h-2M4 4h11v8H9l-3 3v-3H4V4Z"
                clip-rule="evenodd"></path>
            </svg>
          </span>
          <span class="sidebar-text">Feedback</span>
        </a>
      </li> --}}
      {{-- <li class="nav-item {{ Request::segment(1) == 'notifications' ? 'active' : '' }}">
        <a href="/notifications" class="nav-link">
          <span class="sidebar-icon">
            <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                d="M12 5.464V3.099m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175C19 17.4 19 18 18.462 18H5.538C5 18 5 17.4 5 16.807c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 12 5.464ZM6 5 5 4M4 9H3m15-4 1-1m1 5h1M8.54 18a3.48 3.48 0 0 0 6.92 0H8.54Z"
                clip-rule="evenodd"></path>
            </svg>
          </span>
          <span class="sidebar-text">Notifications</span>
        </a>
      </li> --}}
      {{-- <li class="nav-item">
        <span
          class="nav-link {{ Request::segment(1) == 'notifications' || Request::segment(1) == 'subscriptions' ? '' : 'collapsed' }} d-flex justify-content-between align-items-center"
          data-bs-toggle="collapse" data-bs-target="#submenu-notification">
          <span>
            <span class="sidebar-icon">
              <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                  d="M12 5.464V3.099m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175C19 17.4 19 18 18.462 18H5.538C5 18 5 17.4 5 16.807c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 12 5.464ZM6 5 5 4M4 9H3m15-4 1-1m1 5h1M8.54 18a3.48 3.48 0 0 0 6.92 0H8.54Z"
                  clip-rule="evenodd"></path>
              </svg>
            </span>
            <span class="sidebar-text">Notifications</span>
          </span>
          <span class="link-arrow">
            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
            </svg>
          </span>
        </span>
        <div class="multi-level collapse {{ Request::segment(1) == 'notifications' || Request::segment(1) == 'subscriptions' ? 'show' : '' }}" role="list"
          id="submenu-notification" aria-expanded="false">
          <ul class="flex-column nav">
            <li class="nav-item {{ Request::segment(1) == 'notifications' ? 'active' : '' }}">
              <a class="nav-link" href="/notifications">
                <span class="sidebar-text">Notifications</span>
              </a>
            </li>
            <li class="nav-item {{ Request::segment(1) == 'subscriptions' ? 'active' : '' }}">
              <a class="nav-link" href="/subscriptions">
                <span class="sidebar-text">Subscriptions</span>
              </a>
            </li>
          </ul>
        </div>
      </li> --}}
      {{-- <li role="separator" class="dropdown-divider mt-4 mb-3 border-gray-700"></li> --}}
    </ul>
  </div>
</nav>
