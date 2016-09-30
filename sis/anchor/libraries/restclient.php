<?php
class RestClient {

	private function defaulturl($options)
	{
		$options['domain'] = $options['domain'] ? $options['domain'] : HUB_URL;
		$options['token'] = $options['token'] ? $options['token'] : TOKEN;

		$serverUrl = $options['domain'] . '/webservice/rest/server.php' . '?wstoken=' .
			$options['token'] . '&wsfunction=' .
			$options['function_name'] . '&moodlewsrestformat=json';

		return $serverUrl;
	}

	public static function dorest($params, $method = 'post')
	{
		global $CURL;

		$support_method = array('get', 'post');

		if(!in_array($method, $support_method)) {
			throw new Exception("method is not supported");
		}

		if(!isset($params['function_name'])) {
			throw new Exception("You need a function name");
		}
		$url = self::defaulturl($params);
		
		return $CURL->$method($url, $params['params']);
	}
}
