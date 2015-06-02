<?php
/*
.....to do list:::
#need to create a function for auth calls then if everything is or then create a function to send sms.
....to do list:::
*/
function send_sms($message,$number)
{  
   $user="ariscent";
   $password="S658LM2Q";
   $api_id="3514294";
   $baseurl ="http://api.clickatell.com";
   $text=urlencode($message);
   $to=$number;
   // auth call
   $url="$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";
   // do auth call
   $ret=file($url);
   // explode our response. return string is on first line of the data returned
  $sess=explode(":",$ret[0]);
   if($sess[0]=="OK")
      {
	   $sess_id=trim($sess[1]);// remove any whitespace
	   $url="$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text";
	   
	   //do sendmsg call
	   $ret=file($url);
	   $send=explode(":",$ret[0]);
	   
	   if($send[0]=="ID")
	   {
	     //$status="sent";
//$SmSquery = "INSERT INTO sms SET sms_id='$send[1]',timestamp=now(),recipient='$recipient',message='$message',username='$username',status='$status'";
	 // $addsms=$db->query($SmSquery);
	  //print_r($addsms);
	    //echo "successmessage id: ".$send[1];
		return $send[1];
		//return true;
		//store msg id and massage text to database
	   }
	    else
		  {
		   //echo "send massege failed";
		   
		   return NULL;
		   //return false;
		   }
	}
	else
	{
	//echo "AUthentication failure: ".$ret[0];
	return NULL;
	}
}
//send_sms("hello im mush","27840377286","ntando","grade10");


?>