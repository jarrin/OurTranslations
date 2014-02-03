<?php
switch ($this->getData[0]) 
{
	case 'getSessionID':
		$r = ["id" => $this->session->generateHash(24)];
		$insert = [
			":id" => $r["id"]
		];
		$this->db->run(
						"INSERT INTO `submit_sessions`
						(`id`, `expire`)
						VALUES 
						(:id, NOW() + INTERVAL 6 HOUR)
						",
						$insert);

	break;
	case 'upload':
		var_dump($_FILES);
		var_dump($_POST);

	break;
	case 'uploadProcces':
		$r = $this->db->run("SELECT cur_status
							 FROM submit_sessions
							 WHERE `id` = :id", [":id" => $_POST['id']]);
		print_r($r);
	break;
}

return $r;