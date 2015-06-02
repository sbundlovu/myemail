<?php

class DbHandler {

    private $conn;

    function __construct() {
        require_once 'dbConnect.php';
        // opening db connection
        $db = new dbConnect();
        $this->conn = $db->connect();
    }
	
    /**
     * Fetching single record
     */
    public function getOneRecord($query) {
        $r = $this->conn->query($query.' LIMIT 1') or die($this->conn->error.__LINE__);
        return $result = $r->fetch_assoc();    
    }
	public function getAssociates($query)
	{
	$rows = array();
	$r = $this->conn->query($query) or die($this->conn->error.__LINE__);
		if($r === false) {
			return false;
		}
		while ($row = $r -> fetch_assoc()) {
			$rows[] = $row;
		}
	   // print_r($rows);
		return $rows;
		  
	
	}
	
	public function getAllsentsms($query) {
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
        return $result = $r->fetch_assoc();    
    }
    /**
     * Creating new record
     */
    public function insertIntoTable($obj, $column_names, $table_name) {
        
        $c = (array) $obj;
        $keys = array_keys($c);
        $columns = '';
        $values = '';
        foreach($column_names as $desired_key){ // Check the obj received. If blank insert blank into the array.
           if(!in_array($desired_key, $keys)) {
                $$desired_key = '';
            }else{
                $$desired_key = $c[$desired_key];
            }
            $columns = $columns.$desired_key.',';
            $values = $values."'".$$desired_key."',";
        }
        $query = "INSERT INTO ".$table_name."(".trim($columns,',').") VALUES(".trim($values,',').")";
		
        $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
       
        if ($r) {
            //$new_row_id = $this->conn->insert_id;
			//$new_row_id ="success";
			//print_r($new_row_id);
            return true;
            } else {
            return false;
        }
    }
	//update sms credits
public function update_credits($username,$credits)
{
$query = "UPDATE schoolsadmin SET sms_credits='$credits' WHERE ime_number='$username'";
 $r = $this->conn->query($query) or die($this->conn->error.__LINE__);
if ($r) {
            //$new_row_id = $this->conn->insert_id;
			//$new_row_id ="success";
			//print_r($new_row_id);
            return true;
            } else {
            return false;
        }
}

public function getSession(){
    if (!isset($_SESSION)) {
        session_start();
    }
    $sess = array();
    if(isset($_SESSION['s_code']))
	
    {
	
        $sess["s_code"] = $_SESSION['s_code'];
		$sess["names"] = $_SESSION['names'];
		$sess["bank_d"] = $_SESSION['bank_d'];
		$sess["sponsor_code"] = $_SESSION['sponsor_code'];
		$sess["cell"] = $_SESSION['cell'];
    }
    else
    {
        $sess["s_code"] = '';
		$sess["cell"] = '';
		$sess["names"] = '';
		$sess["bank_d"] = '';
        $sess["sponsor_code"] = '';
        
    }
    return $sess;
}
public function destroySession(){
    if (!isset($_SESSION)) {
    session_start();
    }
    if(isset($_SESSION['s_code']))
    {
        unset($_SESSION['s_code']);
        unset($_SESSION['names']);
		unset($_SESSION['bank_d']);
        unset($_SESSION['sponsor_code']);
        $info='info';
        if(isSet($_COOKIE[$info]))
        {
            setcookie ($info, '', time() - $cookie_time);
        }
        $msg="Logged Out Successfully...";
    }
    else
    {
        $msg = "Not logged in...";
    }
    return $msg;
}
function send_sms($number,$message)
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
	   $sess_id=trim($sess[1]);
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
/*function send_sms($number,$message)
{
$sms_id=112344455668;
return $sms_id;
}*/
 
}

?>
