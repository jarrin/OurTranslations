<?php

$news = $this->db->select("news", "", "", "subject as `news-title`, text as `news-content` ");

$view = loopTag("news-content", $news, $view);
