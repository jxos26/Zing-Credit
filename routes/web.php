<?php



Route::get('/', function () {
    return view('welcome');
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
