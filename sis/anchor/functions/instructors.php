<?php

/**
*	Theme functions for logged in user
*/

function instructor_authed_id() {
	if($user = Auth::instructor()) return $instructor->id;
}

function instructor_authed_firstname() {
	if($user = Auth::instructor()) return $instructor->firstname;
}

function instructor_authed_lastname() {
	if($user = Auth::instructor()) return $instructor->lastname;
}

function instructor_authed_email() {
	if($user = Auth::instructor()) return $instructor->email;
}

function instructor_authed_birthday() {
	if($user = Auth::instructor()) return $instructor->birthday;
}

function instructor_authed_subject() {
	if($user = Auth::instructor()) return $instructor->subject;
}

function instructor_object() {
	return Auth::instructor();
}

