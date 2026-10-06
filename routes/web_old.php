<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\CheckPermission;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;


// Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

Route::get('/', function () {
    return view('login');
});
Route::get('/dashboard', [ProfileController::class, 'Dashboard'])->name('dashboard');
Route::get('/add-user', [ProfileController::class, 'index'])->name('profile');
Route::get('/user-list', [ProfileController::class, 'Userlist'])->name('user-list');
Route::get('/schedule', [ProfileController::class, 'Schedule'])->name('schedule');
Route::get('/doctor_schedule', [ProfileController::class, 'DoctorSchedule'])->name('doctor_schedule');
Route::get('/new_patient', [ProfileController::class, 'Newpatient'])->name('new_patient');
Route::get('/patient_detail', [ProfileController::class, 'Patient_detail'])->name('patient_detail');

Route::get('/create_role', [ProfileController::class, 'Createrole'])->name('create_role');
Route::get('/manage_role', [ProfileController::class, 'Managerole'])->name('manage_role');
Route::get('/role_permission', [ProfileController::class, 'Roleandpermission'])->name('role_permission');
Route::get('/add-on', [ProfileController::class, 'Addon'])->name('add-on');
Route::get('/appointment', [ProfileController::class, 'Appointment'])->name('appointment');
Route::get('/newappointment', [ProfileController::class, 'newAppointment'])->name('newappointment');
Route::get('/ipdpatient', [ProfileController::class, 'ipdPatient'])->name('ipdpatient');
Route::get('/newipdpatient', [ProfileController::class, 'newIpdpatient'])->name('newipdpatient');
Route::get('/opdpatient', [ProfileController::class, 'Opdpatient'])->name('opdpatient');
Route::get('/newopdpatient', [ProfileController::class, 'newOpdpatient'])->name('newOpdpatient');


// Admin Section

Route::prefix('admin')->middleware(['auth', 'verified', CheckPermission::class])->group(function () {
    // Route::get('/dashboard', Dashboard::class)->name('admin.dashboard');
    
    Route::get('/users', function () {
        return view('admin.users');
    })->name('admin.users');

    Route::get('/user-add/{name}',[UserController::class,'add_user'])->name('admin.users.add');
    Route::post('/user-add-save/{name}',[UserController::class,'add_user_save'])->name('admin.users.add_save');
    Route::get('/user-edit/{name}/{id}',[UserController::class,'edit_user'])->name('admin.users.edit');
    Route::post('/user-edit/{name}/{id}',[UserController::class,'edit_user_save'])->name('admin.users.edit_save');

    // Route::get('/schedule/',[ScheduleController::class,'index'])->name('admin.schedule.index');
    // Route::get('/doctors-schedule/',[ScheduleController::class,'doctors_schedule'])->name('admin.schedule.doctors_schedule');
    // Route::post('/doctors-schedule/',[ScheduleController::class,'doctor_schedule_save'])->name('admin.schedule.doctor_schedule_save');
    // Route::get('/get-doctor-schedule/{id}',[ScheduleController::class,'get_doctor_schedule'])->name('admin.schedule.doctor_schedule_get');


    Route::get('/user/{name}', function ($name) {
        return view('admin.users', ['name' => $name]);
    })->name('admin.user_list');

    Route::get('/user/{name}/create', function () {
        return view('admin.users');
    })->name('admin.user_create');

    Route::resource('/roles', RoleController::class);

    // Route::get('/roles/create', function () {return view('roles.index');})->name('roles.index');
    Route::name('roles.')->prefix('role')->group(function ($router) {
        Route::resource('/permission', PermissionController::class);
        Route::get('/permissions',[PermissionController::class,'permissions'])->name('permissions');
        Route::post('/assing-permission',[PermissionController::class,'assing_permission'])->name('assing_permission');
    });

    Route::get('/role/role-permission', [PermissionController::class,'role_permission'])->name('roles.role_permission');

    Route::get('/admin/permissions', function(){return view('roles.permission_new');})->name('admin.permissions.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
