<?php
Route::collection(array('before' => 'auth'), function () {
    Route::get(array('admin/permission', 'admin/permission/(:any)'), function ($role = 'user') {
        $vars['errors'] = Session::get('messages.error');
        $vars['messages'] = Notify::read();
        $vars['token'] = Csrf::token();
        $routes = Router::$routes;
        $patterns = Router::$patterns;
        $searches = array_keys($patterns);
        $replaces = array_values($patterns);
        $pattern = Router::$pattern;
        $search = array_keys($pattern);
        $replace = array_values($pattern);
        $getrouters = array();
        foreach ($routes['GET'] as $pattern => $action) {
            if (strpos($pattern, ':') !== false) {
                $pattern = str_replace($searches, $replaces, $pattern);
                $pattern = str_replace($search, $replace, $pattern);
            }
            $getrouters = array_merge($getrouters, (array)$pattern);
        }
        foreach ($routes['POST'] as $pattern => $action) {
            if (strpos($pattern, ':') !== false) {
                $pattern = str_replace($searches, $replaces, $pattern);
                $pattern = str_replace($search, $replace, $pattern);
            }
            $postrouters = array_merge($getrouters, (array)$pattern);
        }
        $routers = array_merge($getrouters, $postrouters);
        $routers = array_unique($routers);

        $perpage = Config::get('admin.advance_per_page');
        $url = Uri::to('admin/permission/' . $role);
        $total = count($routers);
        $pagination = new Paginator($routers, $total, $page = 1, $perpage, $url);
        $vars['permission'] = $pagination;
        $vars['tab'] = 'course';
        $dbrouter = array();
        $router = UserRouter::get_router($role);
        if ($router[0]->router) {
            foreach (explode(',', $router[0]->router) as $action) {
                $dbrouter = array_merge($dbrouter, (array)$action);
            }
        }
        $vars['dbrouter'] = $dbrouter;
        $vars['role'] = $role;
        return View::create('permission/index', $vars)
            ->partial('header', 'partials/header')
            ->partial('footer', 'partials/footer');
    });

    Route::post('admin/permission/(:any)', function ($role) {
        $input = Input::get('box');

        if( empty($input)) {

            Input::flash();

            Notify::error(__('global.box_null'));

            return Response::redirect('admin/permission/'.$role);
        }else {

            $comma_separated = implode(",", $input);
            $id = UserRouter::get_router($role)[0]->id;
            $value = array('action' => $role, 'router' => $comma_separated);
            UserRouter::update($id, $value);
            Extend::process('post', $id);
            Notify::success(__('global.update_success'));
            return Response::redirect('admin/permission/' . $role);
        }
    });

});