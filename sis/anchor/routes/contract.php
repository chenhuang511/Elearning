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

	Route::get(array('admin/contract/search', 'admin/contract/search/(:num)'), function($page = 1) {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $key = $_GET['text-search'];

        $whatSearch = '?text-search=' . $key;
        $perpage = Config::get('admin.posts_per_page');
        list($total, $pages) = Contract::search($key, $page, $perpage);

        $url = Uri::to('admin/contract/search');

        $pagination = new Paginator($pages, $total, $page, $perpage, $url, $whatSearch);

        $vars['contract'] = $pagination;

        return View::create('contract/search', $vars)
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
		$input = Input::get(array('name_contract', 'instructor_id', 'type', 'name_partner', 'start_date', 'end_date', 'salary', 'state', 'rules'));
		$validator = new Validator($input);

		$validator->check('name_contract')
		 	->is_max(2, __('contract.name_contract_missing', 2));
			 
		$validator->check('name_partner')
		 	->is_max(2, __('contract.name_partner_missing', 2));

		$validator->check('start_date')
		 	->is_regex('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', __('contract.start_date_missing'));

		$validator->check('end_date')	
		 	->is_regex('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', __('contract.end_date_missing'));
			 	 
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
		$inst = array('0' => 'Tạo Mới');

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
		$input = Input::get(array('lastname', 'firstname', 'birthday', 'email', 'subject','name_contract', 'instructor_id', 'type', 'name_partner', 'start_date', 'end_date', 'salary', 'state', 'rules'));
		$ins_id = $input['instructor_id'];
		
		$validator = new Validator($input);
		
		$validator->check('name_contract')
		 	->is_max(2, __('contract.name_contract_missing', 2));

		$validator->check('name_partner')
		 	->is_max(2, __('contract.name_partner_missing', 2));
	
		$validator->check('start_date')
		 	->is_regex('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', __('contract.start_date_missing'));

		$validator->check('end_date')	
		 	->is_regex('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', __('contract.end_date_missing'));

		$validator->check('salary')
		 	->is_max(2, __('contract.salary_missing', 2));

		$validator->check('rules')
		 	->is_max(2, __('contract.rules_missing', 2));
	
		if($ins_id == 0){
			$input_instructor = Input::get(array('lastname', 'firstname', 'birthday', 'email', 'subject'));

			$validator->add('valid', function($email) {
				return Query::table(Base::table('instructors'))->where('email', '=', $email)->count() == 0;
			});

			$validator->check('firstname')
		 		->is_max(2, __('contract.firstname_missing'));

			$validator->check('lastname')
		 		->is_max(2, __('contract.lastname_missing'));

			$validator->check('birthday')	
		 		->is_regex('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', __('contract.birthday_missing'));

			$validator->check('email')
				->is_email(__('contract.email_missing'))
				->is_valid(__('contract.email_was_found'));

			$validator->check('subject')
		 		->is_max(2, __('contract.subject_missing'));

			if($errors = $validator->errors()) {
				Input::flash();
				Notify::error($errors);
				return Response::redirect('admin/contract/add');
			}
			$instructor = Instructor::create($input_instructor);
			Extend::process('Instructor', $instructor->id);
			$input_contract = array(
				'name_contract'=>$input['name_contract'],
				'instructor_id'=>$instructor->id,
				'type'=>$input['type'],
				'name_partner'=>$input['name_partner'],
				'start_date'=>$input['start_date'],
				'end_date'=>$input['end_date'],
				'salary'=>$input['salary'],
				'state'=>$input['state'],
				'rules'=>$input['rules']
			);
			$contract = Contract::create($input_contract);
			Extend::process('Contract', $contract->id);

		}

		else{
			if($errors = $validator->errors()) {
				Input::flash();
				Notify::error($errors);
				return Response::redirect('admin/contract/add');
			}
			$input_contract = Input::get(array('name_contract', 'instructor_id', 'type', 'name_partner', 'start_date', 'end_date', 'salary', 'state', 'rules'));
			$contract = Contract::create($input_contract);
			Extend::process('Contract', $contract->id);
			
		}
		
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
