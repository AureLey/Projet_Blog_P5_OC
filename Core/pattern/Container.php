<?php

namespace Aleyg\Core\Pattern;

use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RequestContext;
use Config\RoutesPath;


class Container 
{
	private $data = [];
	static protected $instance;

	public function __construct($data)
	{
		$this->data = $data;
	} 

	public function getInstance($key)
	{	
		if (!isset(self::$instance))
		{	$array = $this->data;
			array_shift($array);
			self::$instance = new $this->data[$key]($array);
		}
		return self::$instance;
	}

     public function set($key, $value)
	{
		$this->data[$key] = $value;
	}

	public function get($key)
	{
		return $this->data[$key];
	}
}