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
Route::get('/zing-credit/today', 'HomeController@zingCreditToday')->name('zing.credit.today');
Route::get('/zing-credit/yesterday', 'HomeController@zingCreditYesterday')->name('zing.credit.yesterday');
Route::get('/zing-credit/last-7-days', 'HomeController@zingCreditLast7Days')->name('zing.credit.Last7Days');
Route::get('/zing-credit/last-30-days', 'HomeController@zingCredit')->name('zing.credit.Last30Days');
Route::get('/zing-credit/date-range/{start}/{end}', 'HomeController@zingCreditDateRange')->name('zing.credit.DateRange');
Route::get('/zing-credit/company/{company}', 'HomeController@zingCreditCompany')->name('zing.credit.Company');

Route::get('/users', 'HomeController@getUsers')->name('get.users');
Route::post('/user/register', 'HomeController@userRegister')->name('user.register');
Route::get('/user/disable/{id}', 'HomeController@disabledUser');
Route::post('/user/settings/update', 'HomeController@updateUserSettings')->name('update.user.settings');
Route::get('/get-company', 'HomeController@getCompany');

// Route::get('/admin', function () {
//     return redirect('/admin/login');
// });
// Route::get('/admin/login', 'Auth\AdminLoginController@adminLoginForm')->name('admin.LoginForm');
// Route::post('/admin/login', 'Auth\AdminLoginController@adminLogin')->name('admin.Login.submit');
// Route::get('/admin/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
// Route::get('/admin/summary', 'AdminController@index')->name('admin.summary');
// Route::get('/admin/profile', 'AdminController@profile')->name('profile');
// Route::post('/admin/profile/updated', 'AdminController@updateProfile')->name('admin.update.profile');

// Route::get('/admin/zing-credit', 'AdminController@zingCredit')->name('admin.zing.credit');
// Route::get('/admin/zing-credit/today', 'AdminController@zingCreditToday')->name('admin.zing.credit.today');
// Route::get('/admin/zing-credit/yesterday', 'AdminController@zingCreditYesterday')->name('admin.zing.credit.yesterday');
// Route::get('/admin/zing-credit/last-7-days', 'AdminController@zingCreditLast7Days')->name('admin.zing.credit.Last7Days');
// Route::get('/admin/zing-credit/last-30-days', 'AdminController@zingCredit')->name('admin.zing.credit.Last30Days');
// Route::post('/admin/zing-credit/date-range', 'AdminController@zingCreditDateRange')->name('admin.zing.credit.DateRange');
// Route::get('/admin/zing-credit/client/{client}', 'AdminController@zingCreditClient')->name('admin.zing.credit.Client');

// Route::get('/admin/users', 'AdminController@getUsers')->name('get.users');
// Route::post('/admin/user/register', 'AdminController@userRegister')->name('user.register');
// Route::get('/admin/user/disable/{id}', 'AdminController@disabledUser');
// Route::post('/admin/user/settings/update', 'AdminController@updateUserSettings')->name('update.user.settings');

// Route::get('/admin/get-clients', 'AdminController@getClients');