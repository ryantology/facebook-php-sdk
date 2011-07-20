<?php

/*
	Facebook requires cURL and throws an execption if it is not found.
	We don't need cURL so we catch that execption (based on string) and re-throw others.
*/

try {
	require_once "facebook.php";
} catch (Exception $execption) {
	if ($execption->getMessage() != 'Facebook needs the CURL PHP extension.') {
		throw $execption;
	}
}


class CakeFacebook extends Facebook {
	
	public function __construct($config) {
		if (!session_id()) {
			session_start();
		}
		parent::__construct($config);
	}
	
	
	/**
   * Makes an HTTP request using HttpSocket.
   *
   * @param string $url The URL to make the request to
   * @param array $params The parameters to use for the POST body
   * @param CurlHandler $ch Initialized curl handle. (Hopefully this is never used...)
   *
   * @return string The response text
   */
	protected function makeRequest($url, $params, $ch=null) {
		App::import('Core', 'HttpSocket');
		$HttpSocket = new HttpSocket();
		$result = $HttpSocket->post($url, $params);
		if ($result === false || $HttpSocket->response['status']['code'] != '200') {
			$execption = new FacebookApiException(array(
				'error_code' => $HttpSocket->response['status']['code'],
				'error' => array(
					'message' => $HttpSocket->response['status']['reason-phrase'],
					'type' => 'HttpSocketException',
				),
			));
		  throw $execption;
		}
		return $result;
	}
}

?>