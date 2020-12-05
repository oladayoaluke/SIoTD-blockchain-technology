<?php 
include "db.php";
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


if(isset($_GET['fetch_detail'])){
	$uid= $_GET['u_id'];

	$get = mysqli_query($conn,"SELECT * FROM nsu_users where id = $uid");

	echo json_encode(mysqli_fetch_object($get));
	exit();
}

if(isset($_POST['update_profile'])){
	
	$return_data = array();
	$u_id = $_POST['u_id'];
	$first_name =$_POST['first_name'];
	$last_name =$_POST['last_name'];
	$company_name =$_POST['company_name'];
	$address1 =$_POST['address1'];
	$address2 =$_POST['address2'];
	$city =$_POST['city'];
	$state =$_POST['state'];
	$country =$_POST['country'];
	$zip_code =$_POST['zip_code'];
	$phone =$_POST['phone'];
	$email =$_POST['email'];
	$question =$_POST['question'];
	$answer =$_POST['answer'];
	$password =$_POST['password'];
	$confirm_pass =$_POST['confirm_pass'];
	$date = date('Y/m/d H:i:s');
	$date = strtotime($date);
	$update = mysqli_query($conn,"UPDATE `nsu_users` 
								SET `firstname`='$first_name',
									`lastname`='$last_name',
									`companyname`='$company_name',
									`address`='$address1',
									`address2`='$address2',
									`city`='$city',
									`state`='$state',
									`province`='',
									`zipcode`='$zip_code',
									`country`='$country',
									`phonenumber`='$phone',
									`email`='$email',
									`securityquestion`='$question',
									`questionanswer`='$answer',
									`updated`='$date'
									 WHERE id = $u_id");
	if($password !=''){
		$update_pass = mysqli_query($conn,"UPDATE nsu_users SET password = '$password' 
											WHERE id = $u_id ");
	}
	if($update){
		$return_data['type'] = true;
		$return_data['message'] = "Successfully Updated. ".mysqli_info($conn);

		$get = mysqli_query($conn,"SELECT * FROM nsu_users where id = $u_id");
		$return_data['data'] = mysqli_fetch_object($get);

	}else{
		$return_data['type'] = false;
		$return_data['message'] = "Update Failed. ".mysqli_error($conn);
		$return_data['data'] = array();

	}
	echo json_encode($return_data);
	exit();
}


?>