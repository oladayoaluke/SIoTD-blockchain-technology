<?php 
include 'db.php';
include 'functions.php';

//----------------------------------------------------------------------
//     VALIDATE API KEY
//----------------------------------------------------------------------
if(isset($_REQUEST["api_key"]))
{
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && sizeof($_POST) !== 0)
	{
		$in_api_key = protect($_POST["api_key"]);
	}
	else if($_SERVER['REQUEST_METHOD'] === 'GET' && sizeof($_GET) !== 0)
	{
		$in_api_key = protect($_GET["api_key"] );
	}
	else
	{
		return "REQUEST_METHOD not allowed!";
	}
	
	validate_api_key($conn, $in_api_key);// should return true
}
else
{
	return "Set Request API ";
}
//----------------------------------------------------------------------
//     END OF VALIDATE API KEY
//----------------------------------------------------------------------

if(isset($_POST['check']) ){
	$return_data = array();
	$email = $_POST['email'];
	$username = $_POST['username'];
	$phone = $_POST['phone'];
	$password = $_POST['password'];


	$email_check_query = mysqli_query($conn,"SELECT * FROM nsu_users where email = '$email'");
	$username_check_query = mysqli_query($conn,"SELECT * FROM nsu_users where name = '$username'");
	$phone_check_query = mysqli_query($conn,"SELECT * FROM nsu_users where phonenumber =  ".$phone);

	
	$email_check =  count(mysqli_fetch_array($email_check_query));

	$username_check =  count(mysqli_fetch_array($username_check_query));

	$phone_check =  count(mysqli_fetch_array($phone_check_query));

	$password_check = validate_password($password);



	if($email_check>0 && $username_check>0 && $phone_check>0){
		$return_data['type'] = false;
		$return_data['fields'] = ['email','username','phone'];
		$return_data['message'] = "Email,Username & Phone Number Already Exist";

	}else if($email_check > 0 && $username_check > 0 && $phone_check < 1){
		$return_data['type'] = false;
		$return_data['fields'] = ['email','username'];

		$return_data['message'] = "Email & Username Already Exist";

	}else if($email_check > 0 && $username_check < 1 && $phone_check > 0){
		$return_data['type'] = false;
		$return_data['fields'] = ['email','phone'];

		$return_data['message'] = "Email & Phone Number Already Exist";

	}else if($email_check < 1 && $username_check > 0 && $phone_check > 0){
		$return_data['type'] = false;
		$return_data['fields'] = ['username','phone'];

		$return_data['message'] = "Username & Phone Number Already Exist";

	}else if($email_check < 1 && $username_check < 1 && $phone_check > 0){
		$return_data['type'] = false;
		$return_data['fields'] = ['phone'];

		$return_data['message'] = "Phone Number Already Exist";

	}else if($email_check < 1 && $username_check > 0 && $phone_check < 1){
		$return_data['type'] = false;

		$return_data['fields'] = ['username'];

		$return_data['message'] = "User Name Already Exist";

	}else if($email_check > 0 && $username_check < 1 && $phone_check < 1){
		$return_data['type'] = false;
		$return_data['fields'] = ['email'];

		$return_data['message'] = "Email Already Exist";

	}else if($email_check < 1 && $username_check < 1 && $phone_check < 1){
		$return_data['type'] = true;
		$return_data['fields'] = [];

		$return_data['message'] = "Fine";
	}else if($password_check==false){
		$return_data['type'] = false;
		$return_data['fields'] = ['password'];
		$return_data['message'] = "Include letters, number and special character in password!";
	}

	echo json_encode($return_data);
	exit();

}


if(isset($_POST['signup'])){
	$return_data = array();
	$username= protect($_POST['username']);
	$email= protect($_POST['email']);
	$phone= protect($_POST['phone']);
	$password=  md5( protect($_POST['password']) );
	$current_date = date("Y/m/d");
	$current_date = strtotime($current_date);


	$insert = mysqli_query($conn,"INSERT INTO `nsu_users`(
		
		`name`, 
		`firstname`, 
		`lastname`, 
		`companyname`, 
		`address`, 
		`address2`, 
		`city`, 
		`state`, 
		`province`, 
		`zipcode`, 
		`country`, 
		`phonenumber`, 
		`password`, 
		`password_recovery`, 
		`email`, 
		`securityquestion`, 
		`questionanswer`, 
		`ip`, 
		`status`, 
		`created`
		) VALUES(
			'$username', 
			'demo',
			'demo last',
			'demo co',
			'demo add',
			'demo add2',
			'demo city',
			'demo state',
			'demo prov',
			'demo zip',
			'demo country',
			'$phone',
			'$password',
			'',
			'$email',
			'',
			'',
			'',
			1,
			'$current_date'
			)");

	

	if($insert){
		$id = mysqli_insert_id($conn);

		$getQuery = mysqli_query($conn,"SELECT * FROM nsu_users where id=".$id);

		$userdata = mysqli_fetch_object($getQuery);

		$return_data['type'] = true;
		$return_data['message'] = "Successfully Registered";
		$return_data['data'] = $userdata;


	}else{
		$return_data['type'] = false;
		$return_data['message'] = "Registeration failed";
		$return_data['data'] = array();	
	}

	echo json_encode($return_data);
	exit();

}




?>