<x-layouts.base>


    @if(in_array(request()->route()->getName(), ['dashboard', 'users', 'usercreate', 'userdetails', 'useredit', 'trips', 'tripdetails', 'cartypes', 'cartypecreate', 'cartypeedit', 'droppins', 'droppincreate', 'droppindetails', 'droppinedit', 'admins', 'admincreate', 'adminedit', 'adminposts', 'adminpostcreate', 'adminpostedit', 'rollaratedlocations', 'rollaratedlocationcreate']))

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