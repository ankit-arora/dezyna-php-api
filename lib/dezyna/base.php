<?php

class Dezyna_Base {
	
	public $base_url = 'https://www.dezyna.com/api/';
	
	public $version = 'v1';
	
	public $verify_ssl = TRUE;
	
	protected $key;
	
	protected $secret;
	
	protected static $instance;
	
	protected $query = array();
	
	protected $post = array();
	
	public function __construct($key, $secret) {
		$this->key = $key;
		$this->secret = $secret;
	}
	
	public static function instance($key = NULL, $secret = NULL) {
		if ( ! is_null($key) && ! is_null($secret)) {
			self::$instance = new Dezyna($key, $secret);
		}
		if (self::$instance) {
			return self::$instance;
		}
		else {
			throw new Dezyna_Exception('You must provide a key and secret the first time you get an instance.', 401);
		}
	}
	
	/**
	 * Grant a reward
	 *
	 * @param string $code The visit code that converted. (required)
	 * @param float $value The value to reward in Indian Rupees. (required)
	 * @param string $payable_on The date the reward value is payable on. (optional)
	 * @param string $reward_string The unique string that helps you track a reward (optional)
	 *
	 */
	public function grant_reward($code, $value, $payable_on = null, $reward_string = null) {
		if ($payable_on == null) {
			$payable_on = date('Y-m-d', time() + 60*60*24*30);
		}
		if ($reward_string == null) {
			$reward_string = "";
		}
		$this->post = array(
			'code' => $code,
			'value' => $value,
			'payable_on' => $payable_on,
			'reward_string' => $reward_string
		);
		return $this->_send_request('/rewards', 'POST');
	}
	
	/**
	 * Private methods.
	 */
	private function _build_url($path) {
		return sprintf('%s%s%s', $this->base_url, $this->version, $path);
	}
	
	private function _send_request($path, $method = 'GET') {
		$ch = curl_init();
		$url = $this->_build_url($path);
		$auth = base64_encode($this->key.':'.$this->secret);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERAGENT, 'dezyna-php-api');
		curl_setopt($ch, CURLOPT_SSLVERSION, 3);

		// SSL
		if ($this->verify_ssl) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/CAcerts/GoDaddyClass2CA.crt");
		}
		else {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		}
		
		$headers = array('Authorization: ' . $auth);
		
		switch ($method) {
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post);
				break;
		}

		if (count($headers) > 0) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		}

		$response = curl_exec($ch);

		if ( ! $response && $response !== '') {
			throw new Dezyna_Exception(curl_error($ch), curl_errno($ch));
		}
		
		$result = array(
			'status' => curl_getinfo($ch, CURLINFO_HTTP_CODE),
			'data' => json_decode($response)
		);
		
		curl_close($ch);
		return $result;
	}
}

class Dezyna_Exception extends Exception {
	
}