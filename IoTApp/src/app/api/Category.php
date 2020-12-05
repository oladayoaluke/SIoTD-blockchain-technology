<?php 
include 'db.php';
include 'CryptoFunctions.php';

if(isset($_POST['inserting']) &&  strcmp($_GET['api_key'], $api_key) == 0 )
{
	$return_data = array();
	$uid = $_POST['u_id'];

	$nickname = $_POST['nickname'];

	$date  =  date('Y/m/d H:i:s' );
	$date = strtotime($date);

		//================== add communication ticket =======================//


	$insert = mysqli_query($conn,"INSERT INTO `nsu_added_category`( `uid`, `nickname`, `address`, `city`, `state`, `country`, `zip_code`, `ticket`, `created`) 
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

		$cid = mysqli_insert_id($conn);

		//check if this is user's first category in the system
		$cid_qeury = mysqli_query($conn,"SELECT * FROM `nsu_added_category` 
				where `uid` = '$uid' ");

		if(mysqli_num_rows($cid_qeury)==0){
			//get last inserted row
			$category_qeury = mysqli_query($conn,"SELECT * FROM `nsu_added_category` 
									where `cid` = '$cid' ");
			//set as current category
			$return_data['is_current_category'] = true;
			$return_data['current_category_data'] = mysqli_fetch_object($category_qeury);
		}else{
			$return_data['is_current_category'] = false;			
		}

		
		
		//then create control row for new category
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
		$return_data['is_current_category'] = false;
		$return_data['message'] = "Insert failed ".mysqli_error($conn);
	}


	echo json_encode($return_data);
	exit();
}




?>