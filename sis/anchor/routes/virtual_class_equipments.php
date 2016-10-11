<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

	/*
		List users
	*/
	Route::get(array('admin/virtual_class_equipments', 'admin/virtual_class_equipments/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
        list($total, $equipments) = VirtualClassEquipment::getList($page, $perpage = Config::get('admin.posts_per_page'));

        $url = Uri::to('admin/virtual_class_equipments');

        $pagination = new Paginator($equipments, $total, $page, $perpage, $url);

        $vars['pages'] = $pagination;

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
        $vars['tab'] = 'room';

		return View::create('virtual_class_equipments/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/virtual_class_equipments/edit/(:num)', function($id) {
		$input = Input::get(array('name', 'description', 'image_url', 'quantity', 'status'));

		$validator = new Validator($input);

		// if ($input['image_url'] == null) {
		// 	Input::flash();

		// 	Notify::error('Bạn chưa nhập ảnh');

		// 	return Response::redirect('admin/virtual_class_equipments/edit/' . $id);
		// }

		// else

		// 	$allowedTypes = array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF);
		// 	$detectedType = exif_imagetype($_FILES['image_url']['tmp_name']);
		// 	$check = !in_array($detectedType, $allowedTypes);

		// if ($check == 1) {
		// 	Notify::error("Ảnh phải có định dạng jpeg, png hoặc gif");
		// 	return Response::redirect('admin/virtual_class_equipments/edit/' . $id);
		// } else {

		$validator->check('name')
		 	->is_max(2, __('Bạn chưa nhập tên thiết bị'));

		$validator->check('description')
		 	->is_max(2,('Bạn chưa nhập mô tả'));

		$validator->check('quantity')
			->is_max(1, __('Bạn chưa nhập số lượng'));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/virtual_class_equipments/edit/' . $id);
		}
		// }

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
		$vars['fields'] = Extend::fields('post');
        $vars['tab'] = 'room';

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
		Delete 
	*/
	Route::get('admin/virtual_class_equipments/delete/(:num)', function($id) {
		VirtualClassEquipment::where('id', '=', $id)->delete();
		Notify::success(__('Bạn đã xóa thành công'));
		return Response::redirect('admin/virtual_class_equipments');
	});

	/*
	/   Search 
	*/
	Route::get('admin/virtual_class_equipments/search', function() {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        //$key = Input::get(array('text-search'));
        $key = $_GET['text-search'];
        $vars['tab'] = 'room';

        $vars['virtual_class_equipments'] = VirtualClassEquipment::where('name', 'LIKE', '%' . $key . '%')
        															->or_where('description', 'LIKE', '%' . $key . '%')
        															->get();

        return View::create('virtual_class_equipments/search', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

});
