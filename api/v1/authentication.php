<?php 
$app->get('/session', function() {
    $db = new DbHandler();
    $session = $db->getSession();
    $response["names"] = $session['names'];
    $response["s_code"] = $session['s_code'];
    $response["sponsor_code"] =  $session['sponsor_code'];
	$response["bank_d"] = $session['bank_d'];
	$response["cell"] =  $session['cell'];
	/*$response["refcode"] = $session['refcode'];*/
    echoResponse(200, $session);
	//print_r($session);
});
$app->post('/login', function() use ($app) {
    require_once 'passwordHash.php';
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('emnumber', 'password'),$r->customer);
    $response = array();
    $db = new DbHandler();
    $password = $r->customer->password;
    $emnumber = $r->customer->emnumber;
    $user = $db->getOneRecord("select s_code,names,email,cell,bank_d,password,sponsor_code from users where s_code='$emnumber'");
    if ($user != NULL) {
        if(passwordHash::check_password($user['password'],$password)){
        $response['status'] = "success";
        $response['message'] = 'Logged in successfully.';
        $response['names'] = $user['names'];
        $response['s_code'] = $user['s_code'];
        $response['sponsor_code'] = $user['sponsor_code'];
        $response['bank_d'] = $user['bank_d'];
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['s_code'] = $user['s_code'];
        $_SESSION['names'] = $user['names'];
		$_SESSION['sponsor_code'] = $user['sponsor_code'];
        $_SESSION['bank_d'] = $user['bank_d'];
		$_SESSION['cell'] = $user['cell'];
		
        } else {
            $response['status'] = "error";
            $response['message'] = 'Login failed. Incorrect credentials';
        }
    }else {
            $response['status'] = "error";
            $response['message'] = 'No such user is registered';
        }
    echoResponse(200, $response);
});
	
		
$app->post('/associates',function() use ($app)
{
$response = array();
$db = new DbHandler();
$session = $db->getSession();
$s_code=$session['s_code'];
$sponsor_code=$session['sponsor_code'];
$user = $db->getAssociates("select users.s_code,users.names,users.email,users.cell,users.bank_d,users.password from users,associates where associates.friend_code=users.s_code and associates.user_code='$s_code' and associates.user_sponsor='$sponsor_code'");
	  // print_r($user);
if ($user != NULL ) {
        $response['status'] = "success";
        $response['message'] = 'please enter varification code then proceed';
        $response['user'] = $user;
       echoResponse(200,  $response);

}
else
    {
	$response['status'] = "error";
    $response['message'] = 'user has no associates';
        //$response['user'] = $user;
       echoResponse(200,  $response);
	}

});

$app->post('/sponsor',function() use ($app){
$db = new DbHandler();
$response = array();
$r = json_decode($app->request->getBody());
verifyRequiredParams(array('sponsor_code'),$r->customer);
$sponsor_code=$r->customer->sponsor_code;
//to do list 
//$user = $db->getAssociates();

});

$app->post('/sendsms',function() use ($app){
$db = new DbHandler();
$response = array();
$session = $db->getSession();
$username= $session['name'];

$r = json_decode($app->request->getBody());
 verifyRequiredParams(array('cellnumber'),$r->customer);
$number=$r->customer->cellnumber;
$code=12345;
$_SESSION['vcode']=$code;
$message="hello user thanks for joining myteam.com your varification code is:".$code;
$r->customer->username=$username;
$r->customer->message=$message;
$r->customer->recipient=$number;
//print_r($r->customer);
$sendmsg=$db->send_sms($number,$message);
if($sendmsg !=NULL)
{ 
//insert data to sms table and return the response
//table name for sms
//$_SESSION['sms_credits']=$session['sms_credits']-1;
$tabble_name = "sms";
$r->customer->sms_id=$sendmsg;
$column_names = array('sms_id', 'recipient', 'message', 'username');
		//print_r($r->customer);
        $result = $db->insertIntoTable($r->customer, $column_names, $tabble_name);

if($result)
{
//$tabble_name = "schoolsadmin";
//$db->update_credits($username,$_SESSION['sms_credits']);
$response['status'] = "success";
$response['message'] = "message was sent successfully your smsId:$sendmsg";
//$response['sms_credits'] = $_SESSION['sms_credits'];
echoResponse(200, $response);
}
else{
//if there is an error inserting to a database save the result or send email to the administrator
$response['status'] = "success";
$response['message'] = "message was sent your smsId:$sendmsg";
echoResponse(200, $response);
}



/* $response['status'] = "success";
 $response['message'] = "message was sent successfully your smsId:$sendmsg";
   echoResponse(200, $response);*/
   }
 else{
 $response['status'] = "error";
 $response['message'] = 'message was not sent please contact the administrator';
   echoResponse(200, $response);
 }
});
 
$app->post('/signUp', function() use ($app) {
    $response = array();
    $r = json_decode($app->request->getBody());
    verifyRequiredParams(array('names','cell','email','password'),$r->customer);
    require_once 'passwordHash.php';
    $db = new DbHandler();
    $names = $r->customer->names;
	 $cell = $r->customer->cell;
    $email = $r->customer->email;
	$password = $r->customer->password;
    $isUserExists = $db->getOneRecord("select * from users where cell='$cell' or email='$email'");
    if(!$isUserExists){
        $r->customer->password = passwordHash::hash($password);
        $tabble_name = "users";
        $column_names = array('names','cell','email', 'password');
		//print_r($r->customer);
        $result = $db->insertIntoTable($r->customer, $column_names, $tabble_name);
        if ($result) {
            $response["status"] = "success";
            $response["message"] = "User account created successfully";
            $response["ime"] = $result;
            /*if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['emis'] = $response["ime_number"];
			 $_SESSION['sms_credits'] = $response["sms_credits"];
          
            $_SESSION['learner'] = $learners;
            $_SESSION['email'] = $email;*/
            echoResponse(200, $response);
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to create customer. Please try again";
            echoResponse(201, $response);
        }            
    }else{
        $response["status"] = "error";
        $response["message"] = "An user with the provided phone or email already exists in our database!";
        echoResponse(201, $response);
    }
});
$app->get('/logout', function() {
    $db = new DbHandler();
    $session = $db->destroySession();
    $response["status"] = "info";
    $response["message"] = "Logged out successfully";
    echoResponse(200, $response);
});
?>