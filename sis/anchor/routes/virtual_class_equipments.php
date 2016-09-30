<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

	/*
		List users
	*/
	Route::get(array('admin/virtual_class_equipments', 'admin/virtual_class_equipments/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['virtual_class_equipments'] = VirtualClassEquipment::paginate($page, Config::get('admin.posts_per_page'));

		return View::create('virtual_class_equipments/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit user
	*/
	Route::get('admin/virtual_class_equipments/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['virtual_class_equipments'] = VirtualClassEquipment::find($id);

		// extended fields
		$vars['fields'] = Extend::fields('virtual_class_equipments', $id);

		return View::create('virtual_class_equipments/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/virtual_class_equipments/edit/(:num)', function($id) {
		$input = Input::get(array('name', 'description', 'image_url', 'quantity', 'status'));
				
		$validator = new Validator($input);

		$validator->check('name')
		 	->is_max(2, __('Bạn chưa nhập tên thiết bị', 2));

		$validator->check('description')
		 	->is_max(2, __('Bạn chưa nhập mô tả', 2));

		$validator->check('quantity')
			->is_max(1, __('Bạn chưa nhập số lượng', 1));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/virtual_class_equipments/edit/' . $id);
		}

		VirtualClassEquipment::update($id, $input);

		Extend::process('virtual_class_equipments', $id);

		Notify::success(__('Bạn đã cập nhật thành công'));

		return Response::redirect('admin/virtual_class_equipments/edit/' . $id);
	});

	/*
		Add user
	*/
	Route::get('admin/virtual_class_equipments/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();

		// extended fields
		$vars['fields'] = Extend::fields('user');

		return View::create('virtual_class_equipments/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/virtual_class_equipments/add', function() {
		$input = Input::get(array('name', 'description', 'image_url', 'quantity', 'status'));

		$validator = new Validator($input);

		$validator->check('name')
		 	->is_max(2, __('Bạn chưa nhập tên thiết bị hoặc tên quá ngắn', 2));

		$validator->check('description')
		 	->is_max(2, __('Bạn chưa nhập mô tả hoặc quá ngắn', 2));

		$validator->check('quantity')
			->is_max(1, __('Bạn chưa nhập số lượng', 1));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/virtual_class_equipments/add');
		}
		$inst = VirtualClassEquipment::create($input);

		Extend::process('VirtualClassEquipment', $inst->id);

		Notify::success(__('Tạo mới thành công'));

		return Response::redirect('admin/virtual_class_equipments');
	});

	/*
		Delete user
	*/
	Route::get('admin/virtual_class_equipments/delete/(:num)', function($id) {
		VirtualClassEquipment::where('id', '=', $id)->delete();
		Notify::success(__('Bạn đã xóa thành công'));
		return Response::redirect('admin/virtual_class_equipments');
		
	});

});
