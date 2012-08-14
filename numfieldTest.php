<?php 

class NumfieldTest extends PHPUnit_Framework_TestCase {

	function test_default_GTE_min() {

		$field = array('min'=>10,'max'=>30,'step'=>1,'default_value'=>1);
		$result = Num_field::rounding($field);
		$expected = array('min'=>10,'max'=>30,'step'=>1,'default_value'=>10);
		$this->assertEquals($expected, $result);

	}
	function test_max_GTE_min() {

		$field = array('min'=>100,'max'=>10,'step'=>1,'default_value'=>10);
		$result = Num_field::rounding($field);
		$expected = array('min'=>10,'max'=>10,'step'=>1,'default_value'=>10);
		$this->assertEquals($expected, $result);

	}
}
