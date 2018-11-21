<?php

namespace ftwr\blogphp\core;

use ftwr\blogphp\boxes\RegisterBoxInterface;
use ftwr\blogphp\core\exceptions\UnknownIdentifierException;

class Container
{
	private $factories = [];
	private $services = [];

	public function factory($name, \Closure $callback)
	{
		$this->factories[$name] = $callback;
	}

	public function service($name, \Closure $callback, array $params = [])
	{
		$this->services[$name] = $this->invoke($callback, $params);
	}

	public function register(RegisterBoxInterface $box)
	{
		$box->register($this);
	}

	public function fabricate($name, ...$params)
	{
		if (!$this->factories[$name]) {
			throw new UnknownIdentifierException($name);
		}

		return $this->invoke($this->factories[$name], $params);
	}

	public function getFactory($name)
	{
		if (!$this->factories[$name]) {
			throw new UnknownIdentifierException($name);
		}

		return $this->factories[$name];
	}

	public function getService($name)
	{
		if (!$this->services[$name]) {
			throw new UnknownIdentifierException($name);
		}

		return $this->services[$name];
	}

	protected function invoke(\Closure $callback, array $params = [])
	{
		return call_user_func_array($callback, $params);
	}
}
