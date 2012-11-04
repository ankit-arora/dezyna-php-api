<?php

include_once 'testcase.php';

class RewardTest extends TestCase {
	
	public function testGrantReward() {
		$r = $this->api->grant_reward('c', 100.001);
		$this->assertEquals(200, $r['status']);
		$this->assertEquals(100.00,$r['data']->value);
		$this->assertEquals('c',$r['data']->code);
		$this->assertEquals(false,$r['data']->errorExists);
		
		$r = $this->api->grant_reward('d', 100.001,"2013-12-07");
		$this->assertEquals(200, $r['status']);
		$this->assertEquals(100.00,$r['data']->value);
		$this->assertEquals('d',$r['data']->code);
		$this->assertEquals("2013-12-07",$r['data']->payableOn);
		$this->assertEquals(false,$r['data']->errorExists);
		
		$r = $this->api->grant_reward('f', 100.50,"2013-12-07","Order no 007");
		$this->assertEquals(200, $r['status']);
		$this->assertEquals(100.50,$r['data']->value);
		$this->assertEquals('f',$r['data']->code);
		$this->assertEquals("2013-12-07",$r['data']->payableOn);
		$this->assertEquals("Order no 007",$r['data']->rewardString);
		$this->assertEquals(false,$r['data']->errorExists);
		
		$r = $this->api->grant_reward('g', "ankit","2013-12-07","Order no 008");
		$this->assertEquals(400, $r['status']);
		$this->assertEquals(true,$r['data']->errorExists);
		$this->assertEquals("Reward value is in the wrong format. Please specify the value in correct format.",$r['data']->errorInfo);
	}
	
}