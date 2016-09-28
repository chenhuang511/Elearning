<?php

define('TOKEN', '');
define('SRVDOMAIN', '');

function defaulturl($restname)
{
	return SRVDOMAIN . PATH_SEPARATE . 'ws_service.php?token=' . TOKEN;
}

function dorest($method, $params)
{
	require __DIR__ . '/../vendor/autoload.php';

	use \Curl\Curl;

	$curl = new Curl();
	$support_method = array('get', 'post');
	
	if(!in_array($method, $support_method)) {
		throw new Exception("method is not supported");
	}
	
	return $curl->$method(defaulturl($params['restname']), $params['options']);	
}

function getgrade()
{
	return dorest('post', array('restname'=>'get_grade_...', 'options'=>array('')));
}