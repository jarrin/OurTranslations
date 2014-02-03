<?php
switch ($this->getData[0]) {
	
	case 'checkCredentials':
		if(!$this->session->checkLoggedin())
		{
			$user = $this->postData["user"];
			$pass = $this->postData["password"];



			//Prevent brute force. 3 try's every 10 minutes.
			$brute_prevent = false; $login_succes = false;
			$user_ip = $_SERVER['REMOTE_ADDR'];
			$brute_result = $this->db->select("login_attempts", "ip = :ip", [ ":ip" => $user_ip ]);
			if(empty($brute_result))
			{
				$insert = array(
				    "ip" => $user_ip,
				    "attempts" => 1
				);
				$this->db->insert("login_attempts", $insert);
				$attempts = 1;
			}
			else
			{
				$attempts = (int)$brute_result[0]["attempts"] + 1;
				$last_attempt = time() - strtotime($brute_result[0]["date"]); //seconds ago
				$id = $brute_result[0]["id"];
				if($last_attempt >= 300) //5 minutes
				{
					$this->db->update("login_attempts", ["attempts" => 1, "date" => date(MYSQL_DATE)], "id = :id", ["id" => $id]);
					$attempts = 1;
				}
				elseif($attempts <= 5) //Give user five tries
				{

					$this->db->update("login_attempts", ["attempts" => $attempts], "id = :id", ["id" => $id]);
				}
				else $brute_prevent = true; //User did to many tries, time-out of 10 minutes
			}

			
			if($brute_prevent)
			{
				$json = [ 
					"check" => false,
					"message" => $this->lang["to-many-login-attempts"]
				];
				$login_succes = false;
			}
			else
			{
				//Check user existance
				$result = $this->db->select("users", "email = :email", [ ":email" => $user ], "password, id");
				$login_succes =  (!empty($result));
				if($login_succes) //User's email exists
				{
					$login_succes = password_verify($pass, $result[0]["password"]);
				}
			}
		} else $login_created = true; //User was already loggedin;

		if(!$brute_prevent)
		{
			if(!$login_succes && !$login_created)
			{
					$json = [ 
						"check" => false,
						"message" => $this->lang["incorrect-credentials"] . "(". $attempts ."/5)"
					];
			}
			else
			{
				//User now validated, create session
				if(!$login_created)
				{
					$this->session->create($result[0]["id"], $user_ip);
					$json = [ 
						"check" => true
					];

				}
				else
				{

					$json = [ 
						"check" => true
					];
				}

			}
		}
	break;
	
	default:
		
	break;

}
return $json;