<?php namespace KirkBushell\Tests\Stubs;

use KirkBushell\Fatty\Context;

class Data
{
	use Context;

	public function doStuff() {
		return 'Data::doStuff';
	}

	public function getRoles() {
		return $this->availableRoles;
	}
}
