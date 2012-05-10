<?php
namespace Decorator;

abstract class Model extends \Laravel\Database\Eloquent\Model
{
	/**
	 * Stores any decorators that we may load at runtime.
	 */
	private $_decorators;

	/**
	 * Allows an object to be extended at runtime. When this is called,
	 * it will look for the decorator to load, and apply all the methods
	 * available in that decorator to the object available.
	 *
	 * @param string $decorator This is the decorator to load, and should
	 *	reside at: application/decorators/modelname/$decorator.php
	 */
	public function extend($decorator)
	{
		$new_methods = $this->_load_decorator($decorator);
		
		foreach ($new_methods as $method => $function)
		{
			$this->_decorators[$method] = $function;
		}
	}

	/**
	 * Loads the required decorator from the model decorator directory
	 *
	 * @param string $decorator
	 */
	private function _load_decorator($decorator)
	{
		$klass = strtolower(get_class($this));
		$decorators_path = path('app').'models'.DS.'decorators'.DS;
		$model_decorator_path = $decorators_path.$klass.DS;
		$decorator_path = $model_decorator_path.strtolower($decorator).'.php';
		$model = $this;

		if (!file_exists($decorators_path))
		{
			throw new \Exception($decorators_path.' does not exist.');
		}
		if (!file_exists($model_decorator_path))
		{
			throw new \Exception($model_decorator_path.' does not exist.');
		}

		return require $decorator_path;
	}

	/**
	 * Handle dynamic method calls on the model. This simple method
	 * is what allows us to call dynamically-added methods to the 
	 * object at runtime.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		if (isset($this->_decorators[$method]) === true) {
			$decorators = $this->_decorators;
			return call_user_func_array($decorators[$method], $parameters);
		}
		else {
			return parent::__call($method, $parameters);
		}
	}
}