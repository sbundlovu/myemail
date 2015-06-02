<?php
require_once 'dbHandler.php';
$db = new DbHandler();
$number="27840377286";
$message="hello im mush";
$sendmsg=$db->send_sms($number,$message);
?>