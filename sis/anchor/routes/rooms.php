<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

	/*
		List rooms
	*/
	Route::get(array('admin/rooms', 'admin/rooms/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['rooms'] = Room::paginate($page, Config::get('admin.posts_per_page'));
		return View::create('rooms/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit user
	*/
	Route::get('admin/rooms/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['rooms'] = Room::find($id);

		// extended fields
		$vars['fields'] = Extend::fields('rooms', $id);

		return View::create('rooms/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

    Route::get('admin/rooms/view/(:num)', function($id) {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $vars['rooms'] = Room::find($id);

        // extended fields
        $vars['fields'] = Extend::fields('rooms', $id);

        return View::create('rooms/view', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

	Route::post('admin/rooms/edit/(:num)', function($id) {
		$input = Input::get(array('name', 'description', 'status'));

		$validator = new Validator($input);

		$validator->check('name')
		 	->is_max(2, __('Bạn chưa nhập tên thiết bị'));

		$validator->check('description')
		 	->is_max(2,('Bạn chưa nhập mô tả'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/rooms/edit/' . $id);
		}

		Room::update($id, $input);

		Extend::process('rooms', $id);

		Notify::success(__('Bạn đã cập nhật thành công'));

		return Response::redirect('admin/rooms/edit/' . $id);
	});

	/*
		Add user
	*/
	Route::get('admin/rooms/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['user'] = Auth::user() ;

		// extended fields
		$vars['fields'] = Extend::fields('post');

		return View::create('rooms/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/rooms/add', function() {
		$input = Input::get(array('name', 'description', 'status'));

		$validator = new Validator($input);

		$validator->check('name')
		 	->is_max(2, __('Bạn chưa nhập tên thiết bị hoặc tên quá ngắn', 2));

		$validator->check('description')
		 	->is_max(2, __('Bạn chưa nhập mô tả hoặc quá ngắn', 2));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/rooms/add');
		}
		$inst = Room::create($input);

		Extend::process('Room', $inst->id);

		Notify::success(__('Tạo mới thành công'));

		return Response::redirect('admin/rooms');
	});

	/*
		Delete 
	*/
	Route::get('admin/rooms/delete/(:num)', function($id) {
		Room::where('id', '=', $id)->delete();
		Notify::success(__('Bạn đã xóa thành công'));
		return Response::redirect('admin/rooms');
	});

	/*
	/   Search 
	*/
	Route::get('admin/rooms/search', function() {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        //$key = Input::get(array('text-search'));
        $key = $_GET['text-search'];

        $vars['rooms'] = Room::where('name', 'LIKE', '%' . $key . '%')
							->or_where('description', 'LIKE', '%' . $key . '%')
							->get();

        return View::create('rooms/search', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

});
