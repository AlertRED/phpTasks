<?php

use Illuminate\Http\Request;
use \App\Http\Middleware\StopBanned;
use \App\Http\Middleware\AdminOnly;


// Route::group(['prefix' => 'v1/'], function(){
//         Route::get('auth/login', 'v1\UserAuthController@UserAuth')
//     });

Route::prefix('v1/')->group(function() {
	 Route::get('auth/login', 'v1\UserAuthController@userLogin');
	 Route::get('auth/logout', 'v1\UserAuthController@userLogout');
	 Route::get('users', 'v1\UserAuthController@getUsers')->middleware(StopBanned::class)->middleware(AdminOnly::class);
	 Route::patch('user/{userId}', 'v1\UserAuthController@patchUser')->middleware(StopBanned::class)->middleware(AdminOnly::class);
});

Route::prefix('v0/')->group(function() {
	# работа с профилями
	Route::get('users/profile/{userProfile}', 'v0\UserProfilesController@getProfile');
	Route::patch('users/profile/{userProfile}', 'v0\UserProfilesController@patchProfileName');
	Route::delete('users/profile/{userProfile}', 'v0\UserProfilesController@deleteProfile');

	Route::get('user/{userId}/profiles', 'v0\UserProfilesController@getProfilesByUser');
	Route::get('users/profiles', 'v0\UserProfilesController@getAllProfiles');

	# работа с группами
	Route::post('users/group', 'v0\UserProfilesController@postGroup');
	Route::get('user/{userId}/groups', 'v0\UserProfilesController@getGroupsByUser');
	Route::delete('users/groups/{groupId}', 'v0\UserProfilesController@deleteGroup');

	Route::post('user/{userId}/group/{groupId}', 'v0\UserProfilesController@addUserToGroup');
	Route::delete('user/{userId}/group/{groupId}', 'v0\UserProfilesController@deleteUsetByGroup');
});


Route::prefix('v0/db/')->group(function() {
	# работа с профилями
	Route::get('users/profile/{id}', 'v0\UserProfilesController@getProfileDB');
	Route::patch('users/profile/{id}', 'v0\UserProfilesController@patchProfileNameDB');
	Route::delete('users/profile/{id}', 'v0\UserProfilesController@deleteProfileDB');

	Route::get('user/{userId}/profiles', 'v0\UserProfilesController@getProfilesByUserDB');
	Route::get('users/profiles', 'v0\UserProfilesController@getAllProfilesDB');
});
