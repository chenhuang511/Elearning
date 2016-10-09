<?php

Route::collection(array('before' => 'auth,csrf,install_exists'), function () {

    /*
        index page
        display list of equipment by roomid
    */
    Route::get(array('admin/equipment/(:any)', 'admin/equipment/(:any)/(:num)'), function ($roomid, $page = 1) {
        $room = Room::getById($roomid);

        if (!$room) {
            Notify::notice(__('rooms.room_notfound'));
            return Response::redirect('admin/equipment/add/room');
        }

        $vars['messages'] = Notify::read();

        list($total, $equipments) = Equipment::getByRoomId($roomid, $page, $perpage = Config::get('admin.posts_per_page'));

        if (count($equipments) === 0) {
            Notify::notice(__('equipment.novirtual_class_equipment'));
            return Response::redirect('admin/equipment/add/virtual_class_equipment/' . $roomid);
        }

        $url = Uri::to('admin/equipment/' . $roomid);
        $pagination = new Paginator($equipments, $total, $page, $perpage, $url);

        $vars['pages'] = $pagination;
        $vars['roomid'] = $roomid;

        return View::create('equipment/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });
    /*
        Add new room
    */
    Route::get('admin/equipment/add/room', function () {
        $vars['errors'] = Session::get('messages.error');
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();

        // extended fields
        $vars['fields'] = Extend::fields('rooms');

        return View::create('equipment/add', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/equipment/add/room', function () {
        $input = Input::get(array('name', 'description'));

        // an array of items that we shouldn't encode - they're no XSS threat
        $dont_encode = array('description', 'css', 'js');

        foreach ($input as $key => &$value) {
            if (in_array($key, $dont_encode)) continue;
            $value = eq($value);
        }

        $validator = new Validator($input);

        $validator->check('name')
            ->is_max(1, __('rooms.name_missing'));

        $validator->check('description')
            ->is_max(1, __('rooms.description_missing'));

        if ($errors = $validator->errors()) {
            Input::flash();
            Notify::error($errors);
            return Response::redirect('admin/equipment/add/room');
        }

        $user = Auth::user();

        $room = Room::create($input);

        Notify::success(__('rooms.created'));

        return Response::redirect('admin/equipment/add/virtual_class_equipment/' . $room->id);
    });

    /*
      Add new equipment
     */
    Route::get('admin/equipment/add/virtual_class_equipment/(:any)', function ($roomid) {
        $vars['errors'] = Session::get('messages.error');
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();

        $room = Room::getById($roomid);

        if (!$room) {
            Notify::warning(__('rooms.notfound'));
            return Response::redirect('admin/rooms');
        }

        $status = array();
        $status = $status + array('1' => 'Tốt','0' => 'Hỏng');

        $vars['status'] = $status;
        $vars['roomid'] = $room->id;
        $vars['roomname']= $room->name;

        $dates = array();

        $days = ceil(abs(strtotime($room->enddate) - strtotime($room->startdate)) / 86400);
        $curdate = $room->startdate;
        for ($i = 1; $i <= $days; $i++) {
            if ($i == 1) { // start date
                $dates[$i] = Equipment::GetDayOfWeek($room->startdate) . ' ' . date('d-m-Y', strtotime($room->startdate));
            } else {
                $curdate = date('Y-m-d', strtotime('+1 day', strtotime($curdate)));
                $dates[$i] = Equipment::GetDayOfWeek($curdate) . ' ' . date('d-m-Y', strtotime($curdate));
            }
        }
        $dates[($days + 1)] = Equipment::GetDayOfWeek($room->enddate) . ' ' . date('d-m-Y', strtotime($room->enddate));
        $vars['dates'] = $dates;

        // extended fields
        $vars['fields'] = Extend::fields('equipment');

        return View::create('equipment/addvirtual_class_equipment', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/equipment/add/virtual_class_equipment/(:any)', function ($roomid) {

        $room = Room::getById($roomid);

        if (!$room) {
            Notify::warning(__('rooms.notfound'));
            return Response::redirect('admin/rooms');
        }

        $dates = array();

        $days = ceil(abs(strtotime($room->enddate) - strtotime($room->startdate)) / 86400);
        $curdate = $room->startdate;
        for ($i = 1; $i <= $days; $i++) {
            if ($i == 1) { // start date
                $dates[$i] = $room->startdate;
            } else {
                $curdate = date('Y-m-d', strtotime('+1 day', strtotime($curdate)));
                $dates[$i] = $curdate;
            }
        }
        $dates[($days + 1)] = $room->enddate;

        $arr = array();
        for ($j = 1; $j <= $days + 1; $j++) {
            array_push($arr, "content_virtual_class_equipment_" . $j);
            array_push($arr, "virtual_class_equipment_" . $j);
        }

        $input = Input::get($arr);

        $validator = new Validator($input);
        $count = 1;
        foreach ($input as $key => $value) {
            if (isset($input['content_virtual_class_equipment_' . $count]) && ($key === 'content_virtual_class_equipment_' . $count && $value === '')) {
                $validator->check('virtual_class_equipment_' . $count)
                    ->is_max(1, __('equipment.virtual_class_equipmentname_missing'));
                $count++;
            }
        }

        if ($errors = $validator->errors()) {
            Input::flash();
            Notify::error($errors);
            return Response::redirect('admin/equipment/add/virtual_class_equipment/' . $room->id);
        }

        $user = Auth::user();

        $icount = 1;

        foreach ($input as $key => $val) {
            if ($key === 'content_virtual_class_equipment_' . $icount && strlen($val) !== 0) {
                $virtual_class_equipments = json_decode($val);
                foreach ($virtual_class_equipments as $virtual_class_equipment) {
                    $arr = array();
                    $arr['room'] = $room->id;
                    $arr['virtual_class_equipmenttime'] = NULL;
                    $arr['virtual_class_equipmentname'] = $virtual_class_equipment->name;
                    $arr['status'] = $virtual_class_equipment->status;
                    $arr['description'] = $virtual_class_equipment->description;
                    $arr['quantity'] = $virtual_class_equipment->quantity;
                    $arr['userid'] = $user->id;
                    $arr['timecreated'] = time();
                    $arr['timemodified'] = time();
                    $arr['usermodified'] = $user->id;

                    $equipment = Equipment::create($arr);
                }
                $icount++;
            }
        }

        Notify::success(__('equipment.virtual_class_equipmentcreated'));

        return Response::redirect('admin/equipment/' . $room->id);
    });

    /*
        Update room
     **/
    Route::get('admin/equipment/update/room/(:any)', function ($roomid) {

        $vars['errors'] = Session::get('messages.error');
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $vars['room'] = Room::getById($roomid);

        // extended fields
        $vars['fields'] = Extend::fields('rooms');

        return View::create('equipment/update', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/equipment/update/room/(:any)', function ($roomid) {
        $input = Input::get(array('name', 'shortname', 'startdate', 'enddate', 'summary'));

        // an array of items that we shouldn't encode - they're no XSS threat
        $dont_encode = array('summary', 'css', 'js');

        foreach ($input as $key => &$value) {
            if (in_array($key, $dont_encode)) continue;
            $value = eq($value);
        }

        $validator = new Validator($input);

        $validator->check('name')
            ->is_max(1, __('rooms.name_missing'));

        $validator->check('shortname')
            ->is_max(1, __('rooms.shortname_missing'));

        $validator->check('startdate')
            ->is_max(1, __('rooms.startdate_missing'));

        $validator->check('enddate')
            ->is_max(1, __('rooms.enddate_missing'));

        $validator->check('summary')
            ->is_max(1, __('rooms.summary_missing'));

        if ($errors = $validator->errors()) {
            Input::flash();
            Notify::error($errors);
            return Response::redirect('admin/equipment/update/room/' . $roomid);
        }

        $user = Auth::user();

        // set remoteid = null
        $input['remoteid'] = null;

        Room::update($roomid, $input);

        Notify::success(__('rooms.updated'));

        return Response::redirect('admin/equipment/add/virtual_class_equipment/' . $roomid);
    });

    /*
        Edit a equipment
     */
    Route::get('admin/equipment/edit/virtual_class_equipment/(:any)', function ($id) {
        $vars['errors'] = Session::get('messages.error');
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();

        $equipment = Equipment::getById($id);

        if (!$equipment) {
            Notify::error(__('equipment.notfound'));
            return Response::redirect('admin/rooms');
        }

        $vars['equipment'] = $equipment;

        $status = array();
        $status = $status + array('0' => 'Hỏng', '1' => 'Tốt');

        $vars['status'] = $status;

        // extended fields
        $vars['fields'] = Extend::fields('equipment');

        return View::create('equipment/edit', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/equipment/edit/virtual_class_equipment/(:any)', function ($id) {

        $equipment = equipment::getById($id);
        $input = Input::get(array('virtual_class_equipmenttime', 'virtual_class_equipmentname', 'status', 'description', 'quantity'));

        $validator = new Validator($input);

        $validator->check('virtual_class_equipmentname')
            ->is_max(1, __('equipment.virtual_class_equipmentname_missing'));
        if ($errors = $validator->errors()) {
            Input::flash();
            Notify::error($errors);
            return Response::redirect('admin/equipment/edit/virtual_class_equipment/' . $id);
        }

        $user = Auth::user();

        Equipment::update($id, $input);

        Notify::success(__('equipment.updated'));

        return Response::redirect('admin/equipment/' . $equipment->room);
    });


    /*
        Delete post
    */
    Route::get('admin/equipment/virtual_class_equipment/delete/(:any)', function ($id) {
        $equipment = Equipment::getById($id);
        $roomid = $equipment->room;

        $equipment->delete();

        Notify::success(__('equipment.deleted'));

        return Response::redirect('admin/equipment/' . $roomid);
    });
    Route::post('admin/equipment/add/remote/room', function () {
        $input = Input::get(array('roomid', 'loop'));
        $roomid = $input['roomid'];
        $loop = $input['loop'];

        echo Room::create_room_hub($roomid, $loop);
    });
});
