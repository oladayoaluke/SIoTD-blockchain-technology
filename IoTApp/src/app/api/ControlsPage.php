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



if(isset($_GET['GetControlData'])){
	$return_data = array();
	$uid=  $_GET['uid'];

	$get_qeury = mysqli_query($conn,"SELECT * FROM `nsu_control` 
									where `uid` = '$uid'  ");
	
	if(mysqli_num_rows($get_qeury)>0){
		$return_data['type'] = true;
		$return_data['ControlTable'] = mysqli_fetch_object($get_qeury);
	}else{
		$return_data['type'] = false;
		$return_data['ControlTable'] = array();
	}

	
	echo json_encode($return_data);
	exit();
}

if(isset($_POST['SetControlDataToggleStart'])){
	$return_data = array();
	$uid = $_POST['uid'];
	$gid = $_POST['gid'];
	$field  = $_POST['field'];
	$value =  $_POST['value'];

	$date = date('Y/m/d H:i:s');
	$date =   strtotime($date);

	$get_qeury = mysqli_query($conn,"SELECT * FROM `nsu_control` 
									where `uid` = '$uid' AND `gid` = '$gid'  ");

	if(mysqli_num_rows($get_qeury)>0){
		$update =  mysqli_query($conn,"UPDATE `nsu_control` SET `$field` = '$value', `updated` = '$date' 
										WHERE uid = '$uid'");
		if($update){
			$return_data['type'] = true;
		}else{
			$return_data['type'] = false;
		}
	}else{
		$return_data['msg'] = "ERROR! : System failed to insert control in addgroup!";
		$return_data['type']= false;
	}

	echo json_encode($return_data);
	exit();
}

if(isset($_GET['GetControlPhase'])){
	$return_data = array();
	$uid=  $_POST['uid'];
	$gid=  $_POST['gid'];

	$get_query  = mysqli_query($conn,"SELECT nsu_added_group.gid,nsu_phase_programming.* 
									FROM nsu_added_group 
									INNER JOIN nsu_added_device 
									ON nsu_added_device.gid = nsu_added_group.gid 
									INNER JOIN nsu_phase_programming 
									ON nsu_phase_programming.adid = nsu_added_device.id 
									WHERE nsu_added_group.uid = '$uid' 
									AND nsu_added_group.gid = '$gid'");


	echo json_encode(mysqli_fetch_object($get_qeury));
	exit();


}

if(isset($_GET['GetAddedTypesId'])){
	$return_data = Array();
	$get_qeury = mysqli_query($conn,"SELECT * FROM `nsu_device` ");

	if(mysqli_num_rows($get_qeury)>0){
		$return_data['type'] = true;
		$return_data['data'] = mysqli_fetch_object($get_qeury);
	}

}

// if(isset($_GET['GetAddedDeviceId'])){
// 	$return_data = array();
	
// 	$uid=  $_POST['uid'];
// 	$gid=  $_POST['gid'];
// 	$dtid=  $_POST['dtid'];

// 	if($dtid <= 4 || intVal($dtid) <= 4){
// 		$get_query  = mysqli_query($conn,"SELECT nsu_added_group.gid,nsu_adapter_programming.* 
// 									FROM nsu_added_group 
// 									INNER JOIN nsu_added_device 
// 									ON nsu_added_device.gid = nsu_added_group.gid 
// 									INNER JOIN nsu_adapter_programming 
// 									ON nsu_adapter_programming.adid = nsu_added_device.id 
// 									WHERE nsu_added_group.uid = '$uid' 
// 									AND nsu_added_group.gid = '$gid'");

// 	}else if($dtid == 5 || $dtid == '5'){
// 		$get_query  = mysqli_query($conn,"SELECT nsu_added_group.gid,nsu_transfer_programming.* 
// 									FROM nsu_added_group 
// 									INNER JOIN nsu_added_device 
// 									ON nsu_added_device.gid = nsu_added_group.gid 
// 									INNER JOIN nsu_transfer_programming 
// 									ON nsu_transfer_programming.adid = nsu_added_device.id 
// 									WHERE nsu_added_group.uid = '$uid' 
// 									AND nsu_added_group.gid = '$gid'");

// 	}else if($dtid == 6 || $dtid == '6'){
// 		$get_query  = mysqli_query($conn,"SELECT nsu_added_group.gid,nsu_phase_programming.* 
// 									FROM nsu_added_group 
// 									INNER JOIN nsu_added_device 
// 									ON nsu_added_device.gid = nsu_added_group.gid 
// 									INNER JOIN nsu_phase_programming 
// 									ON nsu_phase_programming.adid = nsu_added_device.id 
// 									WHERE nsu_added_group.uid = '$uid' 
// 									AND nsu_added_group.gid = '$gid'");

// 	}else if($dtid == 7 || $dtid == '7'){
// 		$get_query  = mysqli_query($conn,"SELECT nsu_added_group.gid,nsu_wifi_programming.* 
// 									FROM nsu_added_group 
// 									INNER JOIN nsu_added_device 
// 									ON nsu_added_device.gid = nsu_added_group.gid 
// 									INNER JOIN nsu_wifi_programming 
// 									ON nsu_wifi_programming.adid = nsu_added_device.id 
// 									WHERE nsu_added_group.uid = '$uid' 
// 									AND nsu_added_group.gid = '$gid'");

// 	}else{//8
// 		$get_query  = mysqli_query($conn,"SELECT nsu_added_group.gid,nsu_wifi_programming.* 
// 									FROM nsu_added_group 
// 									INNER JOIN nsu_added_device 
// 									ON nsu_added_device.gid = nsu_added_group.gid 
// 									INNER JOIN nsu_wifi_programming 
// 									ON nsu_wifi_programming.adid = nsu_added_device.id 
// 									WHERE nsu_added_group.uid = '$uid' 
// 									AND nsu_added_group.gid = '$gid'");
// 	}

// 	$ret = Array();
// 	if(mysqli_num_rows($get_qeury)>0){
// 		$ret = mysqli_fetch_object($get_qeury);

// 		if(!empty($ret)){
// 			$return_data['type']= true;
// 			$return_data['data'] = $ret;
// 		}

// 	}else{
// 		$return_data['type']= false;
// 		$return_data['data'] = $ret;
// 	}

// 	echo json_encode($return_data);
// 	exit();

// }

if(isset($_GET['check_prog_type'])){
	$return_data= array();
	$uid = $_GET['u_id'];
	$group_id = $_GET['gid'];


	$get = mysqli_query($conn,"SELECT DISTINCT dtid from nsu_added_device 
								where uid= $uid AND gid =  $group_id");
	
	$ret = array();
	while($data = mysqli_fetch_array($get)){
		$id = $data['dtid'];
		$get2 = mysqli_query($conn,"SELECT * FROM nsu_device where id = $id");
		
		while($data2 = mysqli_fetch_array($get2)){
			$ret[] = array('id'=>$data2['id'],'name'=>$data2['name']);
		}

	}

	if(!empty($ret)){
		$return_data['type']= true;
		$return_data['check'] = $ret;

	}else{
		$return_data['type']= false;
		$return_data['check'] = $ret;
	}

	echo json_encode($return_data);
	exit();
}



?>