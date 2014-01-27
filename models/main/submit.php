<?php


$news = $this->db->select("languages", "", "", "iso as `iso-code`, CONCAT('[', iso, ']') as language ");

//$view = loopTag("loop-languages", $news, $view);