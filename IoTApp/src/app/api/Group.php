<?php 
include 'db.php';
include 'CryptoFunctions.php';
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


if(isset($_POST['inserting'])){
	$return_data = array();
	$uid = $_POST['u_id'];

	$nickname = $_POST['nickname'];
	$address = $_POST['address'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$country = $_POST['country'];
	$zipcode = $_POST['zipcode'];
	$date  =  date('Y/m/d H:i:s' );
	$date = strtotime($date);

		//================== add communication ticket =======================//
	$ticket = generate_ticket();


	$insert = mysqli_query($conn,"INSERT INTO `nsu_added_group`( `uid`, `nickname`, `address`, `city`, `state`, `country`, `zip_code`, `ticket`, `created`) 
		VALUES ($uid,
				'$nickname',
				'$address',
				'$city',
				'$state',
				'$country',
				'$zipcode',
				'$ticket',
				'$date'
			)");
	if($insert){
		$return_data['type'] = true;
		$return_data['message'] = "Successfully insert Device Group".mysqli_info($conn);

		$gid = mysqli_insert_id($conn);

		//check if this is user's first group in the system
		$gid_qeury = mysqli_query($conn,"SELECT * FROM `nsu_added_group` 
				where `uid` = '$uid' ");

		if(mysqli_num_rows($gid_qeury)==0){
			//get last inserted row
			$group_qeury = mysqli_query($conn,"SELECT * FROM `nsu_added_group` 
									where `gid` = '$gid' ");
			//set as current group
			$return_data['is_current_group'] = true;
			$return_data['current_group_data'] = mysqli_fetch_object($group_qeury);
		}else{
			$return_data['is_current_group'] = false;			
		}

		
		
		//then create control row for new group
		$get_qeury = mysqli_query($conn,"SELECT * FROM `nsu_control` 
									where `gid` = '$gid' ");

		if(mysqli_num_rows($get_qeury)>0){
			$update =  mysqli_query($conn,"UPDATE `nsu_control` SET `$uid` = '$uid', `updated` = '$date' 
											WHERE gid = '$gid' ");
			if($update){
				$return_data['type'] = true;
			}else{
				$return_data['type'] = false;
			}
		}else{
			$insert = mysqli_query($conn,"INSERT INTO `nsu_control` (`uid`,`gid`, `created`) 
											VALUES('$uid','$gid','$date') ");
			if($insert){
				$return_data['msg'] = mysqli_info($conn);
				$return_data['type']= true;
			}else{
				$return_data['msg'] = mysqli_error($conn);
				$return_data['type']= false;
	
			}
		}

	}else{
		$return_data['type'] = false;
		$return_data['is_current_group'] = false;
		$return_data['message'] = "Insert failed ".mysqli_error($conn);
	}


	echo json_encode($return_data);
	exit();
}




?>