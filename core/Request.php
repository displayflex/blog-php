<?php

namespace core;


class Request
{
	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';

	private $get;
	private $post;
	private $server;
	private $cookie;
	private $session;
	private $files;

	public function __construct($get, $post, $server, $cookie, $session, $files)
	{
		$this->get = $get;
		$this->post = $post;
		$this->server = $server;
		$this->cookie = $cookie;
		$this->session = $session;
		$this->files = $files ;
	}

	public function getGET($key = null)
	{
		return  $this->getData($this->get, $key);
	}

	public function getPOST($key = null)
	{
		return  $this->getData($this->post, $key);
	}

	public function getSERVER($key = null)
	{
		return  $this->getData($this->server, $key);
	}

	public function getCOOKIE($key = null)
	{
		return  $this->getData($this->cookie, $key);
	}

	public function getFILES($key = null)
	{
		return  $this->getData($this->files, $key);
	}

	public function getSESSION($key = null)
	{
		return  $this->getData($this->session, $key);
	}
	
	public function isGET()
	{
		return $this->server['REQUEST_METHOD'] === self::METHOD_GET;
	}

	public function isPOST()
	{
		return $this->server['REQUEST_METHOD'] === self::METHOD_POST;
	}

	private function getData(array $arr, $key = null)
	{
		if (!$key) {
			return $arr;
		}

		if (isset($arr[$key])) {
			return $arr[$key];
		}

		return null;
	}
}
