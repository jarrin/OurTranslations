<?php
Class session
{
	private $db;
	function __construct($DB)
	{
		$this->db = $DB;
		$session_name = 'privateSession';   // Set a custom session name
		$secure = false;
		// This stops JavaScript being able to access the session id.
		$httponly = true;
		// Forces sessions to only use cookies.
		if (ini_set('session.use_only_cookies', 1) === FALSE) {
		    header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
		    exit();
		}
		// Gets current cookies params.
		$cookieParams = session_get_cookie_params();
		session_set_cookie_params($cookieParams["lifetime"],
		    $cookieParams["path"], 
		    $cookieParams["domain"], 
		    $secure,
		    $httponly);
		// Sets the session name to the one set above.
		session_name($session_name);
		session_start();            // Start the PHP session 
		//session_regenerate_id();    // regenerated the session, delete the old one. 
	}
	public function create($user_id, $ip)
	{
		//Delete any existing sessions for this user
		
		$this->db->delete("login_sessions", "user_id = :userid", [":userid" => $user_id]);

		$insert = [
			"user_id" => $user_id,
			"ip" => $ip,
			"hash" => $this->generateHash(),
			"expire" => date(MYSQL_DATE, time()+(30*24*60*60)) //One month
		];
		$this->db->insert("login_sessions", $insert);
		$_SESSION["session_id"] = $insert["hash"];
		$_SESSION["session_user"] = $insert["user_id"];

		return true;
	}
	public function generateHash($size = 128)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $size; $i++) 
	    {
        	$randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
	public function checkLoggedin()
	{
		if(isset($_SESSION["session_id"]))
		{
			$hash = $_SESSION["session_id"];
			$userid = $_SESSION["session_user"];
			$result = $this->db->select("login_sessions", "hash = :hash AND user_id = :user_id", [":hash" => $hash, ":user_id" => $userid]);
			if(!empty($result))
			{
				if(!isset($_SESSION["profile"])) $this->setProfile();
				return true;
			}
		}
		return false;
	}
	private function setProfile()
	{
		//blablac
	}

}