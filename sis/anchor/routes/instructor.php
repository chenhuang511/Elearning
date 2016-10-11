<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function() {

	/*
		List users
	*/
	Route::get(array('admin/instructor', 'admin/instructor/(:num)'), function($page = 1) {
		$vars['messages'] = Notify::read();
		$vars['instructors'] = Instructor::paginate($page, Config::get('admin.posts_per_page'));

		return View::create('instructor/index', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});


	Route::get(array('admin/instructor/search', 'admin/instructor/search/(:num)'), function($page = 1) {
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $key = $_GET['text-search'];

        $whatSearch = '?text-search=' . $key;
        $perpage = Config::get('admin.posts_per_page');
        list($total, $pages) = Instructor::search($key, $page, $perpage);

        $url = Uri::to('admin/contract/search');

        $pagination = new Paginator($pages, $total, $page, $perpage, $url, $whatSearch);

        $vars['instructors'] = $pagination;

        return View::create('instructor/search', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });


	Route::get('admin/instructor/edit/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['instructor'] = Instructor::find($id);
		$vars['contract'] = Contract::search_by_instructor_id($id);
		
		// extended fields
		$vars['fields'] = Extend::fields('instructor', $id);

		$vars['type_instructor'] = array(
			'contract' => __('instructor.contract'),
			'official' => __('instructor.official')
		);

		$vars['type'] = array(
			'personal' => __('contract.personal'),
			'organization' => __('contract.organization')
		);

		$vars['state'] = array(
			'paid' => __('contract.paid'),
			'unpaid' => __('contract.unpaid'),
		);
		
		return View::create('instructor/edit', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});


	Route::post('admin/instructor/edit/(:num)', function($id) {
		$input = Input::get(array('fullname', 'birthday', 'email', 'subject', 'curriculum_taught', 'instructor_id', 'type', 'name_partner', 'start_date', 'end_date', 'salary', 'state', 'rules'));
		$validator = new Validator($input);

		$validator->add('valid', function($email) use($id) {
			return Query::table(Base::table('instructors'))->where('id', '!=', $id)->where('email', '=', $email)->count() == 0;
		});
		$count_contract = Query::table(Base::table('instructor_contract'))->where('instructor_id', '=', $id)->count();

		$validator->check('fullname')
		 	->is_max(2, __('instructor.fullname_missing', 2));

		$validator->check('email')
			->is_email(__('instructor.email_missing'))
			->is_valid(__('instructor.email_was_found'));;

		$validator->check('birthday')
		 	->is_regex('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', __('instructor.birthday_missing'));

		$validator->check('subject')
		 	->is_max(2, __('instructor.subject_missing', 2));

		if($count_contract > 0){

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
		}
		if($errors = $validator->errors()) {
			Input::flash();

			Notify::error($errors);

			return Response::redirect('admin/instructor/edit/' . $id);
		}
		$input_instructor = Input::get(array('fullname', 'birthday', 'email', 'type_instructor', 'subject'));
		Instructor::update($id, $input_instructor);
		if($count_contract > 0){
			$input_contract = Input::get(array('name_contract', 'type', 'name_partner', 'start_date', 'end_date', 'salary', 'state', 'rules'));
			Query::table(Base::table('instructor_contract'))->where('instructor_id', '=', $id)->update($input_contract);
		}
		Extend::process('instructor', $id);

		Notify::success(__('instructor.updated'));

		return Response::redirect('admin/instructor/edit/' . $id);
	});


	Route::get('admin/instructor/view/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['instructor'] = Instructor::find($id);
		$vars['contract'] = Contract::search_by_instructor_id($id);
		$vars['curriculum_taught'] = Query::table(base::table('curriculum'))->where(('lecturer'), '=', $id)->count();
		// extended fields
		$vars['fields'] = Extend::fields('instructor', $id);

		$vars['type_instructor'] = array(
			'contract' => __('instructor.contract'),
			'official' => __('instructor.official')
		);

		$vars['type'] = array(
			'personal' => __('contract.personal'),
			'organization' => __('contract.organization')
		);

		$vars['state'] = array(
			'paid' => __('contract.paid'),
			'unpaid' => __('contract.unpaid'),
		);
		
		return View::create('instructor/view', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});


	Route::get('admin/instructor/curriculum/(:num)', function($id) {
		$vars['messages'] = Notify::read();
		$vars['token'] = Csrf::token();
		$vars['instructor'] = Instructor::find($id);
		// extended fields
		$vars['fields'] = Extend::fields('curriculum', $id);
		list($total, $pages) = Curriculum::getByLecturerId($id, $page=1, $perpage= Config::get('admin.posts_per_page'));

        $url = Uri::to('admin/instructor/curriculum');

        $pagination = new Paginator($pages, $total, $page, $perpage, $url);

        $vars['curriculums'] = $pagination;

		return View::create('instructor/curriculum', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});

	/*
		Add user
	*/
	Route::get('admin/instructor/add', function() {
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

		$vars['type_instructor'] = array(
			'contract' => __('instructor.contract'),
			//'official' => __('instructor.official'),
		);

        $test = Course::showallcourses();
        var_dump($test);die;

        $vars['type_subject'] = array(
            'math' => 'Math',
            'history' => 'History',
        );

		$instructor = Instructor::get_name_instructor();
		$inst = array('0' => 'Tạo Mới');

		foreach($instructor as $in)
		{
			$inst[$in->id] = $in->fullname;
		}	

		$vars['instructor_id'] = $inst;
		return View::create('instructor/add', $vars)
			->partial('header', 'partials/header')
			->partial('footer', 'partials/footer');
	});


	Route::post('admin/instructor/add', function() {
		$input = Input::get(array('fullname', 'birthday', 'email', 'type_instructor', 'subject', 'name_contract', 'instructor_id', 'type', 'name_partner', 'start_date', 'end_date', 'salary', 'state', 'rules'));
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
			$input_instructor = Input::get(array('fullname', 'birthday', 'type_instructor', 'email', 'subject'));

			$validator->add('valid', function($email) {
				return Query::table(Base::table('instructors'))->where('email', '=', $email)->count() == 0;
			});

			$validator->check('fullname')
		 		->is_max(2, __('contract.fullname_missing'));

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
				return Response::redirect('admin/instructor/add');
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
				return Response::redirect('admin/instructor/add');
			}
			$input_contract = Input::get(array('name_contract', 'instructor_id', 'type', 'name_partner', 'start_date', 'end_date', 'salary', 'state', 'rules'));
			$contract = Contract::create($input_contract);
			Extend::process('Contract', $contract->id);
			
		}
		
		Notify::success(__('contract.created'));

		return Response::redirect('admin/instructor');
	});


	/*
		Delete user
	*/
	Route::get('admin/instructor/delete/(:num)', function($id) {
		Instructor::where('id', '=', $id)->delete();
		Query::table(Base::table('instructor_contract'))->where('instructor_id', '=', $id)->delete();
		Notify::success(__('instructor.deleted'));
		return Response::redirect('admin/instructor');
		
	});

});
