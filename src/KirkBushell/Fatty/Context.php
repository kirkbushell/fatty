<?php namespace KirkBushell\Fatty;

trait Context
{
	/**
	 * Stores the registered roles within this class context. Everytime
	 * extend() is called, the class and its methods are cached on this
	 * property so that we're not doing class reflection each time we
	 * want to call a method.
	 * 
	 * @var array
	 */
	protected $availableRoles;

	/**
	 * Extends the object at runtime by making the methods that are available 
	 * on the the class ($role) that is passed to the method, available
	 * to $this, and ensuring that class/instance scope is also provided.
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

		if ( !empty( $parent ) ) {
			return parent::__call( $method, $arguments );
		}

		throw new \Exception( "Method [$method] is not defined on the Query class." );
	}
}

// $parent = get_parent_class( $this );
// 		$class  = get_called_class();

// 		foreach ( $this->availableRoles as $role ) {
// 			if ( method_exists( $role, $method ) ) {
// 				$closure = $role->$method();
// 				$bound = $closure->bindTo( $this, $class );

// 				return $bound( $arguments );
// 			}
// 		}

// 		if ( !is_null( $parent ) && $parent != '' ) {
// 			return parent::__call( $method, $arguments );
// 		}

// 		throw new \Exception( "Method [$method] is not defined on the {$class} class." );