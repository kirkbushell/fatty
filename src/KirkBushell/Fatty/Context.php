<?php namespace KirkBushell\Fatty;

trait Context
{
	/**
	 * Stores the roles registered within this class context.
	 * 
	 * @var array
	 */
	protected $availableRoles;

	/**
	 * Extends the object at runtime, instantiating the role class that is
	 * passed to the method, and caching the result on the class.
	 *
	 * @param string $role
	 */
	public function extend( $role ) {
		$class = "\\$role";
		$this->availableRoles[ $role ] = new $class;
	}
	
	/**
	 * Whenever a missing method on the object is called, then Context will
	 * look to see if any roles with the provided method are available. If
	 * they are, then it will execute and return the first matching method.
	 *
	 * @param string $method
	 * @param array $arguments
	 */
	public function __call( $method, $arguments ) {
		$class  = get_called_class();
		$parent = get_parent_class( $this );

		foreach ( $this->availableRoles as $role ) {
			if ( method_exists( $role, $method ) ) {
				$closure = $role->$method();
				$bound = $closure->bindTo( $this, $class );

				return call_user_func_array( $bound, $arguments );
			}
		}

		// If the class implementing Context is part of a content, let the 
		// parent manage any magic that it may want to execute.
		if ( !empty( $parent ) ) {
			return parent::__call( $method, $arguments );
		}

		throw new \Exception( "Method [$method] is not defined on the {$class} class." );
	}
}
