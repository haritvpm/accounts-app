<?php

//Route::redirect('/', '/salaryrecord/public/login');
Route::redirect('/', '/login');


Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');


    // Year
    Route::delete('years/destroy', 'YearController@massDestroy')->name('years.massDestroy');
    Route::resource('years', 'YearController');

    // Allocation
    Route::delete('allocations/destroy', 'AllocationController@massDestroy')->name('allocations.massDestroy');
    Route::resource('allocations', 'AllocationController');

    // Salary Bill Details
    Route::delete('salary-bill-details/destroy', 'SalaryBillDetailsController@massDestroy')->name('salary-bill-details.massDestroy');
    Route::resource('salary-bill-details', 'SalaryBillDetailsController');

    // Tds Report
    Route::resource('tds', 'TdsController');
    Route::post('tds/download', 'TdsController@download')->name('tds.download');
    Route::resource('tax-entries', 'TaxEntryController');

     // Backup routes
     Route::resource('backups',  'BackupController');
     Route::get('backup/create', 'BackupController@create');
     Route::get('backup/download/{file_name}', 'BackupController@download');
     Route::get('backup/delete/{file_name}', 'BackupController@delete');

    // Employee
    Route::delete('employees/destroy', 'EmployeeController@massDestroy')->name('employees.massDestroy');
    Route::post('employees/parse-csv-import', 'EmployeeController@parseCsvImport')->name('employees.parseCsvImport');
    Route::post('employees/process-csv-import', 'EmployeeController@processCsvImport')->name('employees.processCsvImport');
    Route::resource('employees', 'EmployeeController');
    
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
Route::group(['as' => 'frontend.', 'namespace' => 'Frontend', 'middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    // Route::get('/home', 'SalaryBillDetailsController@create')->name('home');



    // Year
    Route::delete('years/destroy', 'YearController@massDestroy')->name('years.massDestroy');
    Route::resource('years', 'YearController');

    // Allocation
    Route::delete('allocations/destroy', 'AllocationController@massDestroy')->name('allocations.massDestroy');
    Route::resource('allocations', 'AllocationController');

    // Salary Bill Details
    Route::delete('salary-bill-details/destroy', 'SalaryBillDetailsController@massDestroy')->name('salary-bill-details.massDestroy');
    Route::resource('salary-bill-details', 'SalaryBillDetailsController');


    // Tax Entry
    //Route::delete('tax-entries/destroy', 'TaxEntryController@massDestroy')->name('tax-entries.massDestroy');
    Route::resource('tax-entries', 'TaxEntryController');
    Route::resource('tds', 'TdsController');

    Route::get('frontend/profile', 'ProfileController@index')->name('profile.index');
    Route::post('frontend/profile', 'ProfileController@update')->name('profile.update');
    Route::post('frontend/profile/destroy', 'ProfileController@destroy')->name('profile.destroy');
    Route::post('frontend/profile/password', 'ProfileController@password')->name('profile.password');
});