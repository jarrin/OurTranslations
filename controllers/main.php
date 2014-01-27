<?php

Class Controller
{
	private $page, $getData, $postData;

	private	$out;

	private $session, $db;

	function __construct($GET, $POST, $DB)
	{
		$this->db = $DB;
		$this->stripGET($GET);
		//setting js and css files
		$this->createSession();
		$this->setHeaders();
		//load the view and model
		$this->load();
	}

	private function setHeaders()
	{
		

	}
	private function stripGET($GET)
	{
		if(empty($GET))
		{
			$this->page = "index";
		}
		else
		{
			$this->page = $GET['a'];
			unset($GET['a']);
			foreach ($GET as $value) 
			{
				$this->getData[] = $value;
			}
		}
	}
	private function createSession()
	{
		$this->session = new session($this->db);
	}
	private function load()
	{
		//Load base html template
		$this->out = file_get_contents(BASE . "views/other/html.php" );



		//Set view and model files
		$file_view = BASE . "views/main/" . $this->page . ".html";
		$file_model = BASE . "models/main/" . $this->page . ".php";
		$file_default = BASE . "models/other/default.php";
		//Check if they exsist
		if(!file_exists($file_view))
		{

			$this->page = "notfound";
			$file_view = BASE . "views/main/" . $this->page . ".html";
			$file_model = BASE . "models/main/" . $this->page . ".php";
		}

		//Getting css & js from config.php
		$jsfiles = array_merge($GLOBALS['c']['js']['default'], $GLOBALS['c']['js'][$this->page]);
		$cssfiles = array_merge($GLOBALS['c']['css']['default'], $GLOBALS['c']['css'][$this->page]);

		//Add those to the header ([js][css])
		$this->addJS($jsfiles);
		$this->addCSS($cssfiles);
		//Set base URL
		//$this->out = replaceTag("base-url", $GLOBALS['c']['base-href'], $this->out);

		//get html view file
		$view = file_get_contents($file_view);

		//let the default model and page model alterate it
		if(file_exists($file_default)) require($file_default);
		if(file_exists($file_model)) require($file_model);
		
		//Now replace the body ([body]) in the main html file
		$this->out = replaceTag("body", $view, $this->out);
		$this->out = replaceTag("title", $GLOBALS['c']['title'], $this->out);

		//procces language tags
		$this->out = replaceLanguageTags("en", $this->out);

		//Procces data that the JS need to know about
		$this->out = replaceTag("this-page", $this->page, $this->out);

		//Procces active page
		$this->out = replaceTag("page-".$this->page, "active", $this->out);
		$this->out = replaceTagByRegex("page\-(.*?)", "", $this->out);

	}

	private function addJS($arrFiles)
	{
		$str = '';
		foreach ($arrFiles as $value) 
		{
			$str .= "<script type=\"text/javascript\" src=\"js/". $value ."\" ></script>".PHP_EOL;
		}
		$this->out = replaceTag("js", $str, $this->out);

	}
	private function addCSS($arrFiles)
	{
		$str = '';
		foreach ($arrFiles as $value) 
		{
			$str .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/".$value	."\" media=\"screen\">".PHP_EOL;
		}
		$this->out = replaceTag("css", $str, $this->out);
	}

	public function out()
	{
		echo $this->out;
	}
}