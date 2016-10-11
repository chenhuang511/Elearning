<?php
Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

	/*
		List users
	*/
	Route::get(array('admin/users', 'admin/users/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['users'] = User::paginate($page, Config::get('admin.posts_per_page'));
        $vars['tab'] = 'sys';

		return View::create('users/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit user
	*/
	Route::get('admin/users/info/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['user'] = User::find($id);

		// extended fields
		$vars['fields'] = Extend::fields('user', $id);

		$vars['statuses'] = array(
			'inactive' => __('global.inactive'),
			'active' => __('global.active')
		);

		$vars['roles'] = array(
			'administrator' => __('global.administrator'),
			'editor' => __('global.editor'),
			'user' => __('global.user')
		);

        $vars['tab'] = 'sys';

		return View::create('users/info', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/users/info/(:num)', function($id) {
		$input = Input::get(array('username', 'email', 'real_name', 'bio', 'status', 'role'));
		$password_reset = false;
		
		// A little higher to avoid messing with the password
		foreach($input as $key => &$value) {
			$value = eq($value);
		}
		
		if($password = Input::get('password')) {
			$input['password'] = $password;
			$password_reset = true;
		}
		
		$validator = new Validator($input);
		
		$validator->add('safe', function($str) use($id) {
			return ($str != 'inactive' and Auth::user()->id == $id);
		});

		$validator->check('username')
			->is_max(2, __('users.username_missing', 2));

		$validator->check('email')
			->is_email(__('users.email_missing'));

		if($password_reset) {
			$validator->check('password')
				->is_max(6, __('users.password_too_short', 6));
		}

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/users/info/' . $id);
		}

		if($password_reset) {
			$input['password'] = Hash::make($input['password']);
		}

		User::update($id, $input);

		Extend::process('user', $id);

		Notify::success(__('users.updated'));

		return Response::redirect('admin/users/info/' . $id);
	});

    /*
        Search user
    */
    Route::get(array('admin/users/search', 'admin/users/search/(:num)'), function($page = 1) {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        //$input = Input::get(array('text-search'));
        //$key = $input['text-search'];
        $key = $_GET['text-search'];

        $whatSearch = '?text-search=' . $key;
        //Session::put($whatSearch, $whatSearch);
        $perpage = Config::get('admin.posts_per_page');
        list($total, $pages) = User::searchuser($key, $page, $perpage);

        $url = Uri::to('admin/users/search');

        $pagination = new Paginator($pages, $total, $page, $perpage, $url, $whatSearch);

        $vars['users'] = $pagination;
        $vars['tab'] = 'sys';

        return View::create('users/search', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

	/*
		Add user
	*/
	Route::get('admin/users/add', function() {
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
        $vars['tab'] = 'sys';

		return View::create('users/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/users/add', function() {
		$input = Input::get(array('username', 'email', 'real_name', 'password', 'bio', 'status', 'role'));
		
		foreach($input as $key => &$value) {
			if($key === 'password') continue; // Can't avoid, so skip.
			$value = eq($value);
		}
		
		$validator = new Validator($input);

		$validator->check('username')
			->is_max(3, __('users.username_missing', 2));

		$validator->check('email')
			->is_email(__('users.email_missing'));

		$validator->check('password')
			->is_max(6, __('users.password_too_short', 6));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/users/add');
		}

		$input['password'] = Hash::make($input['password']);

		$user = User::create($input);

		Extend::process('user', $user->id);

		Notify::success(__('users.created'));

		return Response::redirect('admin/users');
	});

	/*
		Delete user
	*/
	Route::get('admin/users/delete/(:num)', function($id) {
		$self = Auth::user();

		if($self->id == $id) {
			Notify::error(__('users.delete_error'));

			return Response::redirect('admin/users/info/' . $id);
		}

		User::where('id', '=', $id)->delete();

		Query::table(Base::table('user_meta'))->where('user', '=', $id)->delete();

		Notify::success(__('users.deleted'));

		return Response::redirect('admin/users');
	});

});
