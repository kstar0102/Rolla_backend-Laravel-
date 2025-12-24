<?php

use App\Http\Livewire\BootstrapTables;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Components\Buttons;
use App\Http\Livewire\Components\Forms;
use App\Http\Livewire\Components\Modals;
use App\Http\Livewire\Components\Typography;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Users;
use App\Http\Livewire\UserCreate;
use App\Http\Livewire\UserDetails;
use App\Http\Livewire\UserEdit;
use App\Http\Livewire\Trips;
use App\Http\Livewire\TripDetails;
use App\Http\Livewire\CarTypes;
use App\Http\Livewire\CarTypeCreate;
use App\Http\Livewire\CarTypeEdit;
use App\Http\Livewire\Droppins;
use App\Http\Livewire\DroppinCreate;
use App\Http\Livewire\DroppinEdit;
use App\Http\Livewire\DroppinDetails;
use App\Http\Livewire\Admins;
use App\Http\Livewire\AdminCreate;
use App\Http\Livewire\AdminEdit;
use App\Http\Livewire\AdminPosts;
use App\Http\Livewire\AdminPostCreate;
use App\Http\Livewire\AdminPostEdit;
use App\Http\Livewire\RollaRatedLocations;
use App\Http\Livewire\RollaRatedLocationCreate;
use App\Http\Livewire\RollaRatedLocationEdit;
use App\Http\Livewire\RollaRatedLocationDetails;


use App\Http\Livewire\Err404;
use App\Http\Livewire\Err500;
use App\Http\Livewire\ResetPassword;
use App\Http\Livewire\ForgotPassword;
use App\Http\Livewire\Lock;
use App\Http\Livewire\Profile;
use App\Http\Livewire\ForgotPasswordExample;
use App\Http\Livewire\Index;
use App\Http\Livewire\LoginExample;
use App\Http\Livewire\ProfileExample;
use App\Http\Livewire\RegisterExample;
use App\Http\Livewire\Transactions;
use App\Http\Livewire\ResetPasswordExample;
use App\Http\Livewire\UpgradeToPro;
use App\Http\Livewire\Drivers;
use App\Http\Livewire\DriverCreate;
use App\Http\Livewire\DriverDetails;
use App\Http\Livewire\DriverEdit;
use App\Http\Livewire\DriverEarning;
use App\Http\Livewire\Riders;
use App\Http\Livewire\RiderCreate;
use App\Http\Livewire\RiderEdit;
use App\Http\Livewire\RideRequest;
use App\Http\Livewire\Riderequestdetails;
use App\Http\Livewire\RideRequestCreate;
use App\Http\Livewire\DriverRatings;
use App\Http\Livewire\DriverRatingDetails;
use App\Http\Livewire\RiderRatings;
use App\Http\Livewire\RiderRatingDetails;
use App\Http\Livewire\Feedback;
use App\Http\Livewire\FeedbackDetails;
use App\Http\Livewire\Notifications;
use App\Http\Livewire\NotificationDetails;
use App\Http\Livewire\NotificationCreate;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/login');
Route::get('/register', Register::class)->name('register');
Route::get('/admin/register', \App\Http\Livewire\Auth\AdminRegister::class)->name('admin.register');
Route::get('/login', Login::class)->name('login');
Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');
Route::get('/reset-password/{id}', ResetPassword::class)->name('reset-password')->middleware('signed');

Route::get('/404', Err404::class)->name('404');
Route::get('/500', Err500::class)->name('500');

Route::middleware(['auth:admin'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/users', Users::class)->name('users');
    Route::get('/user/create', UserCreate::class)->name('usercreate');
    Route::get('/user/details/{id}', UserDetails::class)->name('userdetails');
    Route::get('/user/edit/{id}', UserEdit::class)->name('useredit');
    Route::get('/trips', Trips::class)->name('trips');
    Route::get('/trip/details/{id}', TripDetails::class)->name('tripdetails');
    Route::get('/car-types', CarTypes::class)->name('cartypes');
    Route::get('/car-type/create', CarTypeCreate::class)->name('cartypecreate');
    Route::get('/car-type/edit/{id}', CarTypeEdit::class)->name('cartypeedit');
    Route::get('/droppins', Droppins::class)->name('droppins');
    Route::get('/droppin/create', DroppinCreate::class)->name('droppincreate');
    Route::get('/droppin/details/{id}', DroppinDetails::class)->name('droppindetails');
    Route::get('/droppin/edit/{id}', DroppinEdit::class)->name('droppinedit');
    Route::get('/admins', Admins::class)->name('admins');
    Route::get('/admin/create', AdminCreate::class)->name('admincreate');
    Route::get('/admin/edit/{id}', AdminEdit::class)->name('adminedit');
    Route::get('/admin-posts', AdminPosts::class)->name('adminposts');
    Route::get('/admin-post/create', AdminPostCreate::class)->name('adminpostcreate');
    Route::get('/admin-post/edit/{id}', AdminPostEdit::class)->name('adminpostedit');
    Route::get('/rolla-rated-locations', RollaRatedLocations::class)->name('rollaratedlocations');
    Route::get('/rolla-rated-location/create', RollaRatedLocationCreate::class)->name('rollaratedlocationcreate');
    Route::get('/rolla-rated-location/edit/{id}', RollaRatedLocationEdit::class)->name('rollaratedlocationedit');
    Route::get('/rolla-rated-location/details/{id}', RollaRatedLocationDetails::class)->name('rollaratedlocationdetails');
});
