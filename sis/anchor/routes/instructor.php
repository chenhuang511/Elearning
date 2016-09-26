<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

	/*
		List users
	*/
	Route::get(array('admin/instructor', 'admin/instructor/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['users'] = User::paginate($page, Config::get('admin.posts_per_page'));

		return View::create('instructor/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit user
	*/
	Route::get('admin/instructor/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['instructor'] = Instructor::find($id);

		// extended fields
		$vars['fields'] = Extend::fields('instructor', $id);

		return View::create('instructor/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/instructor/edit/(:num)', function($id) {
		$input = Input::get(array('firstname', 'lastname', 'email', 'birthday', 'subject'));
		
		// A little higher to avoid messing with the password
		
		$validator = new Validator($input);
		
		$validator->add('safe', function($str) use($id) {
			return (Auth::instructor()->id == $id);
		});

		$validator->check('firstname')
		 	->is_max(2, __('instructor.firstname_missing', 2));

		$validator->check('lastname')
		 	->is_max(2, __('instructor.lastname_missing', 2));

		$validator->check('email')
			->is_email(__('instructor.email_missing'));

		$validator->check('birthday')
			->is_regex('#([012]?[1-9]|[12]0|3[01])\/(0?[1-9]|1[012])\/([0-9]{4})$#', __('instructor.birthday_missing'));

		$validator->check('subject')
		 	->is_max(2, __('instructor.subject_missing', 2));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/instructor/edit/' . $id);
		}

		Instructor::update($id, $input);

		Extend::process('instructor', $id);

		Notify::success(__('instructor.updated'));

		return Response::redirect('admin/instructor/edit/' . $id);
	});

	/*
		Add user
	*/
	Route::get('admin/instructor/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		// extended fields
		$vars['fields'] = Extend::fields('user');

		$vars['statuses'] = array(
			'inactive' => __('global.inactive'),
			'active' => __('global.active')
		);

		$vars['roles'] = array(
			'administrator' => __('global.administrator'),
			'editor' => __('global.editor'),
			'user' => __('global.user')
		);

		return View::create('instructor/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/instructor/add', function() {
		$input = Input::get(array('firstname', 'lastname', 'email', 'birthday', 'subject'));
		$validator = new Validator($input);

		$validator->check('firstname')
		 	->is_max(2, __('instructor.firstname_missing', 2));

		$validator->check('lastname')
		 	->is_max(2, __('instructor.lastname_missing', 2));

		$validator->check('email')
			->is_email(__('instructor.email_missing'));

		$validator->check('birthday')
			->is_regex('#([012]?[1-9]|[12]0|3[01])\/(0?[1-9]|1[012])\/([0-9]{4})$#', __('instructor.birthday_missing'));

		$validator->check('subject')
		 	->is_max(2, __('instructor.subject_missing', 2));
		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/instructor/add');
		}
		
		$inst = Instructor::create($input);

		Extend::process('Instructor', $inst->id);

		Notify::success(__('instructor.created'));

		return Response::redirect('admin/instructor');
	});

	/*
		Delete user
	*/
	Route::get('admin/instructor/delete/(:num)', function($id) {
		$mysqlconn = new mysqli("localhost", "root", "", "anchor");
        $sql= "DELETE anchor_instructors.*,anchor_instructor_contract.* FROM anchor_instructors LEFT JOIN anchor_instructor_contract ON anchor_instructors.id=anchor_instructor_contract.instructor_id WHERE anchor_instructors.id=".$id;
        mysqli_query($mysqlconn,$sql);
		Notify::success(__('instructor.deleted'));
		return Response::redirect('admin/instructor');
		
	});

});
