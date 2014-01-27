<?php

Class Controller
{
	private $module, $getData, $postData;

	private	$out;

	private $session, $db, $lang;

	function __construct($GET, $POST, $DB)
	{

		$this->db = $DB;
		$this->lang = require BASE . "variables/languages/en.php";


		$this->stripGET($GET);
		$this->postData = $POST;

		//setting js and css files
		$this->createSession();
		$this->setHeaders();

		//load module
		$this->load();
	}

	private function setHeaders()
	{
		header('Content-type: application/json');
	}
	private function stripGET($GET)
	{

		$this->module = $GET['a'];
		unset($GET['a']);
		foreach ($GET as $value) 
		{
			$this->getData[] = $value;
		}
	}
	private function createSession()
	{
		$this->session = new session($this->db);
	}
	private function load()
	{
		$file = BASE . "models/ajax/" . $this->module . ".php";
		if(file_exists($file))
		{
			$this->out = require($file);
		}
		else
		{
		}
	}

	public	 function out()
	{
		echo json_encode($this->out);
	}
}