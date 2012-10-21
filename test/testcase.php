<?php

abstract class TestCase extends PHPUnit_Framework_TestCase {
	
	public $api_key = 'ffab94967ee0088ace764556c616b7e4';
	
	public $secret = '2f30f9a67969e207370d259fe6928490';
	
	public function setUp() {
		$this->loadApi();
	}
	
	public function loadApi() {
		$this->api = Dezyna::instance($this->api_key, $this->secret);
		$this->api->base_url = 'http://dezyna-dev-2.com/api/';
	}

}

?>