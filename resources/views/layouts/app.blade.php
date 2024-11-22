<x-layouts.base>


    @if(in_array(request()->route()->getName(), ['dashboard', 'profile', 'drivers', 'drivercreate', 'driverdetails', 'driveredit', 'earning', 'riders', 'rideredit', 'ridercreate', 'riderequest', 'riderequestcreate', 'riderequestdetails', 'driverratings', 'driverratingdetails', 'riderratings', 'riderratingdetails', 'feedback', 'feedbackdetails', 'profile-example', 'users', 'bootstrap-tables', 'transactions',
    'buttons', 'forms', 'modals', 'notifications', 'notificationdetails', 'notificationcreate', 'typography', 'upgrade-to-pro']))

    {{-- Nav --}}
    @include('layouts.nav')
    {{-- SideNav --}}
    @include('layouts.sidenav')
    <main class="content">
        {{-- TopBar --}}
        @include('layouts.topbar')
        {{ $slot }}
        {{-- Footer --}}
        @include('layouts.footer')
    </main>

    @elseif(in_array(request()->route()->getName(), ['register', 'register-example', 'login', 'login-example',
    'forgot-password', 'forgot-password-example', 'reset-password','reset-password-example']))

    {{ $slot }}
    {{-- Footer --}}
    <!-- @include('layouts.footer2') -->


    @elseif(in_array(request()->route()->getName(), ['404', '500', 'lock']))

    {{ $slot }}

    @endif
</x-layouts.base>