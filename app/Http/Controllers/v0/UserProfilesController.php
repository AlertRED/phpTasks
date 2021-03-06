<?php

namespace App\Http\Controllers\v0;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User\UserProfile;
use App\User;
use Validator;
use App\BaseFunctions;
use App\Mail\patchProfile;


class UserProfilesController extends Controller
{	
	const itemOnPage = 5;

	public static function postProfile(Request $request){
		Validator::make($request->all(), ['name' => 'required|unique:user_profiles|max:255'])->validate();
		$name = $request['name'];
		$user_id = $request['user_id'];
		$profile = UserProfile::create(['name' => $name, 'user_id' => $user_id]);
		return BaseFunctions::generateJSON(true, "created_profile", $profile);
	}

	public static function getProfile(UserProfile $userProfile){
		abort_unless($userProfile, 404, "Does not exist id=$userProfile" , ["Content-Type" => "application/json"]);
		return BaseFunctions::generateJSON(true, "profile", $userProfile);
	}

	public function getProfilesByUser($userId){
		$userProfiles = UserProfile::where('user_id', $userId)->get();
		abort_unless(filled($userProfiles), 404, "Does not exist Profiles whith id=$userId" , ["Content-Type" => "application/json"]);
		return BaseFunctions::generateJSON(true, "profiles", $userProfiles);
	}
	
	public function getAllProfiles(Request $request){
		Validator::make($request->all(), ['page' => 'required|min:1'])->validate();
		$page = $request['page'];
		$userProfiles = UserProfile::paginate(self::itemOnPage)->items();
		return BaseFunctions::generateJSON(true, "profiles", $userProfiles);
	}

	public static function patchProfileName(Request $request, UserProfile $userProfile){
		Validator::make($request->all(), ['name' => 'required|unique:user_profiles|max:255'])->validate();
		$name = $request['name'];
		$oldProfile = clone $userProfile;
		$userProfile->update(['name' => $name]);
		if ($userProfile)
			BaseFunctions::sendMailToAdmin(new patchProfile($oldProfile, $userProfile));
		return  self::getProfile($userProfile);
	}

	public static function deleteProfile(UserProfile $userProfile){
		return BaseFunctions::generateJSON((bool) $userProfile->delete());
	}

	public function getProfileDB($id){
	 	$userProfile = DB::table('user_profiles')->where('id', $id)->first();
		abort_unless($userProfile, 400, "Does not exist Profile whith id=$id" , ["Content-Type" => "application/json"]);
		return BaseFunctions::generateJSON("profile", $userProfile);
	}

	public function getProfilesByUserDB($userId){
		$userProfiles = DB::table('user_profiles')->where('user_id', $userId)->get();
		return BaseFunctions::generateJSON("profiles", $userProfiles);
	}

	public function getAllProfilesDB(){
		 Validator::make($request->all(), ['page' => 'required|min:1'])->validate();
		 $page = $request['page'];
	 	 $userProfiles = DB::table('user_profiles')->paginate(self::itemOnPage)->items();
	 	 abort_unless(filled($userProfiles) and $page, 404, "Does not exist Page" , ["Content-Type" => "application/json"]);
 		return BaseFunctions::generateJSON("profiles", $userProfiles);
	}

	public function patchProfileNameDB($id){
		Validator::make($request->all(), ['name' => 'required|unique:user_profiles|max:255'])->validate();
		$name = $request['name'];
		DB::table('user_profiles')->where('id', $id)->update(['name' => $name]);		 	 
		return $this->getProfileDB($id);
	}

	public function deleteProfileDB($id){
		return BaseFunctions::generateJSON((bool) DB::table('user_profiles')->where('id', $id)->delete());
	}

}
