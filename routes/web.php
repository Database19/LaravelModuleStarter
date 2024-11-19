<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/assign-role', function() {
    // $userSales = User::create([
    //     'name' => 'Manager Sales',
    //     'email' => 'sales@crm.com',
    //     'password' => Hash::make('kodok123')
    // ]);

    // $userAkuntan = User::create([
    //     'name' => 'Manager Accounting',
    //     'email' => 'accounting@crm.com',
    //     'password' => Hash::make('kodok123')
    // ]);

    // $userInventory = User::create([
    //     'name' => 'Manager Inventory',
    //     'email' => 'inventory@crm.com',
    //     'password' => Hash::make('kodok123')
    // ]);

    // $userMaintenance = User::create([
    //     'name' => 'Manager Maintenance',
    //     'email' => 'maintenance@crm.com',
    //     'password' => Hash::make('kodok123')
    // ]);

    // $err = 0;
    // if(!$userSales->assignRole('Sales Manager')){
    //     $err = 1;
    // }
    // if(!$userAkuntan->assignRole('Accountant')){
    //     $err = 1;
    // }
    // if(!$userInventory->assignRole('Inventory Clerk')){
    //     $err = 1;
    // }
    // if(!$userMaintenance->assignRole('Maintenance')){
    //     $err = 1;
    // }

    // if($err === 1){
    //     return "fail";
    // }

    // return "sukses";
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
