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


// ----------------------------------------------------------------------
//     END OF VALIDATE API KEY
// ----------------------------------------------------------------------

if(isset($_POST['loginReq'])){
	$return_data = array();

	if(isset($_POST['username']) && isset($_POST['password'])){
		
		$username = protect($_POST['username']);
		$password = md5(protect($_POST['password']));
		$check_query=  mysqli_query($conn, "SELECT * FROM nsu_users WHERE name = '$username' AND password = '$password' AND status = 1 ");
		if(mysqli_num_rows($check_query)>0){
			$check = mysqli_fetch_object($check_query);

			$return_data['type'] = True;
			$return_data['message'] = "Successfully Logged IN";
			$return_data['data'] =$check;
		}else{
			$return_data['type'] = false;
			$return_data['message'] = "Username Or password Is Wrong";
			$return_data['data'] = array();
		}

	}else{
		$return_data['type'] = false;
		$return_data['message'] = "Missing Username Or Password";
		$return_data['data'] = array();

	}

	echo json_encode($return_data);
	exit();
}

?>