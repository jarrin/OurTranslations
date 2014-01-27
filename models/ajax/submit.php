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

		var_dump($_POST);
		var_dump($_FILES);
	break;
	case 'uploadProcces':
		$key = ini_get("session.upload_progress.prefix") . "test";
		echo $key;
		$r = ["p" => $_SESSION['upload_progress_upload']];
		var_dump($_SESSION);
	break;
}

return $r;