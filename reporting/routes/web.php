<?php



Route::get('/', function () {
    return view('users.login');
});

// Route::get('/login', function () {
//     return redirect('/');
// });

// Route::get('/register', function () {
//    return redirect('/');
// });

//Auth::routes();

//Users Route
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('users.login');
Route::post('/login', 'Auth\LoginController@login')->name('users.login.submit');  
Route::get('/logout', 'Auth\LoginController@logout')->name('users.logout');
Route::get('register', 'Auth\RegisterController@userRegistration')->name('users.registration');
Route::post('register', 'Auth\RegisterController@register')->name('users.register');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('users.password.email');
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('users.password.request');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('users.password.reset');


Route::get('/summary', 'HomeController@index')->name('summary');
Route::get('/profile', 'HomeController@profile')->name('profile');
Route::post('/profile/updated', 'HomeController@updateProfile')->name('update.profile');
Route::get('/zing-credit', 'HomeController@zingCredit')->name('zing.credit');

Route::get('/admin', function () {
    return redirect('/admin/login');
});
Route::get('/admin/login', 'Auth\AdminLoginController@adminLoginForm')->name('admin.LoginForm');
Route::post('/admin/login', 'Auth\AdminLoginController@adminLogin')->name('admin.Login.submit');
Route::get('/admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
Route::get('/admin/summary', 'AdminController@index')->name('admin.summary');
Route::get('/admin/profile', 'AdminController@profile')->name('profile');
Route::post('/admin/profile/updated', 'AdminController@updateProfile')->name('admin.update.profile');
Route::get('/admin/zing-credit', 'AdminController@zingCredit')->name('zing.credit');
Route::get('/admin/clients', 'AdminController@getClients')->name('get.clients');
Route::post('/admin/client/register', 'AdminController@clientRegister')->name('client.register');
Route::get('/admin/client/disable/{id}', 'AdminController@disabledClient');
