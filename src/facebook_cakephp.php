<?php

/*
	Facebook requires cURL and throws an execption if it is not found.
	We don't need cURL so we catch that execption (based on string) and re-throw others.
*/

try {
	require_once "base_facebook.php";
} catch (Exception $execption) {
	if ($execption->getMessage() != 'Facebook needs the CURL PHP extension.') {
		throw $execption;
	}
}


class CakeFacebook extends BaseFacebook {
	
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
	
	protected static $kSupportedKeys = array('state', 'code', 'access_token', 'user_id');

  /**
   * Provides the implementations of the inherited abstract
   * methods.  The implementation uses PHP sessions to maintain
   * a store for authorization codes, user ids, CSRF states, and
   * access tokens.
   */
	protected function setPersistentData($key, $value) {
		if (!in_array($key, self::$kSupportedKeys)) {
			debug('Unsupported key passed to setPersistentData.');
			return;
		}
		
		$session_var_name = $this->constructSessionVariableName($key);
		$this->Session->write($session_var_name, $value);
	}

	protected function getPersistentData($key, $default = false) {
		if (!in_array($key, self::$kSupportedKeys)) {
			debug('Unsupported key passed to getPersistentData.');
			return $default;
		}

		$session_var_name = $this->constructSessionVariableName($key);
		return isset($this->Session->read($session_var_name)) ?
			$this->Session->read($session_var_name) : $default;
	}

	protected function clearPersistentData($key) {
		if (!in_array($key, self::$kSupportedKeys)) {
			debug('Unsupported key passed to clearPersistentData.');
			return;
		}

		$session_var_name = $this->constructSessionVariableName($key);
		$this->Session->delete($session_var_name);
	}

	protected function clearAllPersistentData() {
		$this->Session->delete('fb.'.$this->getAppId());
	}

	protected function constructSessionVariableName($key) {
		return implode('.', array('fb', $this->getAppId(), $key));
	}
}

?>