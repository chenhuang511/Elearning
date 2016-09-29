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
            return User::find($id)->role == "student";
        }
        return false;
    }
    public static function teacher() {
        if($id = Session::get(static::$session)) {
            return User::find($id)->role == "teacher";
        }
        return false;
    }
    public static function office() {
        if($id = Session::get(static::$session)) {
            return User::find($id)->role == "office";
        }
        return false;
    }
    public static function training() {
        if($id = Session::get(static::$session)) {
            return User::find($id)->role == "training";
        }
        return false;
    }
    public static function specialized() {
        if($id = Session::get(static::$session)) {
            return User::find($id)->role == "specialized";
        }
        return false;
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
