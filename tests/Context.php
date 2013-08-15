<?php namespace KirkBushell\Tests;

use KirkBushell\Tests\Stubs\Data;
use KirkBushell\Tests\Stubs\Interaction;

class ContextTest extends \PHPUnit_Framework_TestCase
{
	public $data;

	public function setUp() {
		$this->data = new Data;
		$this->data->extend( 'KirkBushell\Tests\Stubs\Interaction' );
	}

	public function testDataClassBeforeExtensionShouldNotConsistOfExtraMethods() {
		$this->assertFalse( method_exists( new Data, 'doSomething' ) );;
	}

	public function testContextShouldAddRoleWhenExtendingClass() {
		$roles = $this->data->getRoles();

		$this->assertEquals( 1, count( $roles ) );
		$this->assertEquals( 'KirkBushell\Tests\Stubs\Interaction', get_class( array_pop( $roles ) ) );
	}

	public function testExtendedMethodsCanAccessPrivateMethodsWhenScoped() {
		$this->assertEquals( 'Data::doStuff', $this->data->doStuff() );
	}

	/**
	 * @expectedException Exception
	 */
	public function testThrowsNewExceptionIfExtendedClassDoesNotHaveAMatchingMethod() {
		$this->data->missingMethod();
	}

	public function testExtendedClassMethodsCanAcceptCallParameters() {
		$this->assertEquals( 'Arg1::butter, Arg2::nut', $this->data->handleArguments( 'butter', 'nut' ) );
	}
}
