<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

	/*
		List users
	*/
	Route::get(array('admin/contract', 'admin/contract/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['contract'] = Contract::paginate($page, Config::get('admin.posts_per_page'));

		return View::create('contract/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Edit user
	*/
	Route::get('admin/contract/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['contract'] = Contract::find($id);
		// extended fields
		$vars['fields'] = Extend::fields('contract', $id);

		$vars['type'] = array(
			'personal' => __('contract.personal'),
			'organization' => __('contract.organization')
		);

		$vars['state'] = array(
			'paid' => __('contract.paid'),
			'unpaid' => __('contract.unpaid'),
		);
		
		$instructor = Instructor::get_name_instructor();
		$inst = array();

		foreach($instructor as $in)
		{
			$inst[$in->id] = $in->lastname." ".$in->firstname;
		}	

		$vars['instructor_id'] = $inst;

		return View::create('contract/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/contract/edit/(:num)', function($id) {
		$input = Input::get(array('instructor_id', 'type', 'name_partner', 'start_date', 'end_date', 'salary', 'state', 'rules'));
		$validator = new Validator($input);

		$validator->check('name_partner')
		 	->is_max(2, __('contract.name_partner_missing', 2));

		$validator->check('salary')
		 	->is_max(2, __('contract.salary_missing', 2));

		$validator->check('rules')
		 	->is_max(2, __('contract.rules_missing', 2));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/contract/edit/' . $id);
		}

		Contract::update($id, $input);

		Extend::process('contract', $id);

		Notify::success(__('contract.updated'));

		return Response::redirect('admin/contract/edit/' . $id);
	});

	/*
		Add user
	*/
	Route::get('admin/contract/add', function() {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		// extended fields
		$vars['fields'] = Extend::fields('contract');

		$vars['type'] = array(
			'personal' => __('contract.personal'),
			'organization' => __('contract.organization')
		);

		$vars['state'] = array(
			'paid' => __('contract.paid'),
			'unpaid' => __('contract.unpaid'),
		);

		$instructor = Instructor::get_name_instructor();
		$inst = array();

		foreach($instructor as $in)
		{
			$inst[$in->id] = $in->lastname." ".$in->firstname;
		}	

		$vars['instructor_id'] = $inst;

		return View::create('contract/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	Route::post('admin/contract/add', function() {
		$input = Input::get(array('instructor_id', 'type', 'name_partner', 'start_date', 'end_date', 'salary', 'state', 'rules'));
		$validator = new Validator($input);

		$validator->check('name_partner')
		 	->is_max(2, __('contract.name_partner_missing', 2));

		$validator->check('salary')
		 	->is_max(2, __('contract.salary_missing', 2));

		$validator->check('rules')
		 	->is_max(2, __('contract.rules_missing', 2));

		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/contract/add');
		}
		
		$contract = Contract::create($input);

		Extend::process('Contract', $contract->id);

		Notify::success(__('contract.created'));

		return Response::redirect('admin/contract');
	});

	/*
		Delete user
	*/
	Route::get('admin/contract/delete/(:num)', function($id) {
		$self = Auth::user();

		Contract::where('id', '=', $id)->delete();
		Notify::success(__('contract.deleted'));
		return Response::redirect('admin/contract');
		
	});

});
