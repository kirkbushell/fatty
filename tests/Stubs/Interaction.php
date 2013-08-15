<?php namespace KirkBushell\Tests\Stubs;

class Interaction
{
	private function doSomething() {
		return function() {
			return $this->doStuff();
		};
	}

	public function handleArguments() {
		return function( $argument1, $argument2 ) {
			return 'Arg1::' . $argument1 . ', Arg2::' . $argument2;
		};
	}
}