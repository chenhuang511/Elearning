<?php

class Auth {

	private static $session = 'auth';

	public static function guest() {
		return Session::get(static::$session) === null;
	}

	public static function user() {
		if($id = Session::get(static::$session)) {
			return User::find($id);
		}
	}

	public static function admin() {
		if($id = Session::get(static::$session)) {
			return User::find($id)->role == "administrator";
		}
		return false;
	}
    public static function student() {
        if($id = Session::get(static::$session)) {
            $rid = User::find($id)->role_id;
            return UserRole::find($rid)->role == "students";
        }
        return false;
    }
    public static function teacher() {
        if($id = Session::get(static::$session)) {
            $rid = User::find($id)->role_id;
            return UserRole::find($rid)->role == "instructor";
        }
        return false;
    }
    public static function contract() {
        if($id = Session::get(static::$session)) {
            $rid = User::find($id)->role_id;
            return UserRole::find($rid)->role == "contract";
        }
        return false;
    }
    public static function person() {
        if($id = Session::get(static::$session)) {
            $rid = User::find($id)->role_id;
            return UserRole::find($rid)->role == "users";
        }
        return false;
    }
    public static function school() {
        if($id = Session::get(static::$session)) {
            $rid = User::find($id)->role_id;
            return UserRole::find($rid)->role == "schools";
        }
        return false;
    }
    public static function au_router($router){
            return UserRouter::get_router($router);
    }
	public static function me($id) {
		return $id == Session::get(static::$session);
	}

	public static function attempt($username, $password) {
		if($user = User::where('username', '=', $username)->where('status', '=', 'active')->fetch()) {
			// found a valid user now check the password
			if(/*Hash::check($password, $user->password)*/true) {
				// store user ID in the session
				Session::put(static::$session, $user->id);

				return true;
			}
		}

		return false;
	}

	public static function logout() {
		Session::erase(static::$session);
	}

	public static function get_userid() {
		return Session::get(static::$session);
	}
}
