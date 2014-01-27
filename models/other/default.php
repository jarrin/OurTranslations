<?php
if($this->session->checkLoggedin())
{
	$login_block = '
	<div class="" >[you-have]</div>
	<div class="number" >2401</div>
	<div class="points" >[points]</div>
	<div class="" >[to-spent]</div>
	';
	$this->out = replaceTag("under-class", "", $this->out);
}
else
{
	$login_block = '
	<div class="" style="margin-top: 40px" >[not-logged-in]</div>
	<div class="" >[click-here]</div>
	<div class="" >[to-sign-in]</div>
	';
	$this->out = replaceTag("under-class", "pointer loginPop", $this->out);
}
$this->out = replaceTag("login-block", $login_block, $this->out);