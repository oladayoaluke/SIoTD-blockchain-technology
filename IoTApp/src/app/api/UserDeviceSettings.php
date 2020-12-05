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


//fetch settings
if(isset($_GET['get_settings'])){
	$return_data = array();
	$u_id = $_GET['u_id'];
	$get_u_device_settings = mysqli_query($conn,"SELECT * FROM nsu_settings where uid = ".$u_id);

	$device_settings = mysqli_fetch_object($get_u_device_settings);

	 if(count($device_settings)>0){
	 	$return_data['type'] = true;
	 	$return_data['data'] = $device_settings;

	 }else{
	 		$return_data['type'] = false;
	 	$return_data['data'] = array();
	 }

	echo json_encode($return_data);
	exit();
}

// save settings
if(isset($_POST['saveSettings'])){
	$return_data= array();

	$u_id = $_POST['user_id'];
	$field = $_POST['field'];
	$value = $_POST['value'];
	

	$check_query =  mysqli_query($conn,"SELECT * FROM nsu_settings where uid =".$u_id);
	$check = mysqli_fetch_array($check_query);
	if(count($check)>0){
		$update = mysqli_query($conn,"UPDATE `nsu_settings` SET $field = '$value' where uid=".$u_id);
		
		if($update){
			$return_data['type'] = true;
			$return_data['message'] = "Successfully Updates Settings";
			$check_query =  mysqli_query($conn,"SELECT * FROM nsu_settings where uid =".$u_id);
			$return_data['data'] = mysqli_fetch_object($check_query);
		}else{
			$return_data['type'] = true;
			$return_data['message'] = "having Problem in Update Settings".mysqli_error($conn);
			$return_data['data'] = array();
		}
	}else{
		$insert = mysqli_query($conn,"INSERT INTO nsu_settings(`uid`,$field) VALUES($u_id,'$value')");
		if($insert){
			$return_data['type'] = true;
			$return_data['message'] = "Successfully Insert Settings";
			$check_query =  mysqli_query($conn,"SELECT * FROM nsu_settings where uid =".$u_id);
			$return_data['data'] = mysqli_fetch_object($check_query);


		}else{
			$return_data['type'] = true;
			$return_data['message'] = "having Problem in Insert Settings".mysqli_error($conn);
			$return_data['data'] = array();

		}
	}
	echo json_encode($return_data);
	exit();

}

if(isset($_GET['GetGroupDevices'])){
	$u_id  =$_GET['u_id'];
	$gid  =$_GET['gid'];

	$query = mysqli_query($conn,"SELECT nsu_added_device.*,
								nsu_device.name as device_type_name 
								FROM nsu_added_device 
								INNER JOIN nsu_added_group 
								ON nsu_added_device.gid = nsu_added_group.gid 
								INNER JOIN nsu_device 
								ON nsu_added_device.dtid = nsu_device.id 
								WHERE nsu_added_device.gid = '$gid' AND 
								nsu_added_device.uid = '$u_id'");
	$groupDevices = array();
	while($data = mysqli_fetch_array($query)){
		$groupDevices[] = array('id'=>$data['id'],'name'=>$data['nickname']);
	}
	echo json_encode($groupDevices);
	exit();
}
if(isset($_POST['DeviceMaintenance'])){
	$return_data = array();
	$data = json_decode($_POST['data']);
	$uid = $_POST['user_id'];
	$dtid = $_POST['device_id'];
	$date = date('Y/m/d H:i:s');
	$date = strtotime($date);

	$data_ = json_encode( array('temp'=>$data->temp,
					'sound'=>$data->sound,
					'vibration'=>$data->vibration,
					'gas'=>$data->gas,
					'oil'=>$data->oil));
	
	$insert = mysqli_query($conn,"INSERT INTO nsu_maintenance_request
		(`uid`, `dtid`, `data`, `status`, `created`) VALUES($uid,$dtid,'$data_',0,'$date')");

	if($insert){
		$return_data['type'] = true;
		$return_data['message'] = "Device Maintenance Request Send Successfully";

	}else{
		$return_data['type'] = false;
		$return_data['message'] = "Device Maintenance Request Sending Failed".mysqli_error($conn);
	}
	echo json_encode($return_data);
	exit();
}

if(isset($_GET['get_devices'])){
	$return_data  = array();
	$uid = $_GET['u_id'];
	if(isset($_GET['wifi_adpter'])){
		$device_type = $_GET['wifi_adpter'];
$query = mysqli_query($conn,"SELECT * FROM nsu_added_device
								WHERE uid = $uid AND dtid = $device_type");
	}else{
	$query = mysqli_query($conn,"SELECT * FROM nsu_added_device
								WHERE uid = $uid");
	}
	
	$added_device = array();
	if(mysqli_num_rows($query) >0  ){
	while($data = mysqli_fetch_array($query) ){
		$added_device[]=  array('id'=>$data['id'],
							'uid'=>$data['uid'],
							'dtid'=>$data['dtid'],
							'nickname'=>$data['nickname'],
							'prog_code'=>$data['prog_code']);
	}
}else{
		$return_data['type'] = false;
	$return_data['data'] = array();
}	
	if(count($added_device) >0){
	$return_data['type'] = true;
	$return_data['data'] = $added_device;

	}else{
		$return_data['type'] = false;
	$return_data['data'] = array();
	}
	echo json_encode($return_data);
	exit();
}

if(isset($_POST['EditDevice'])){
	$return_data = array();
	$id = $_POST['id'];
	$name = $_POST['name'];

	$update =  mysqli_query($conn,"UPDATE nsu_added_device SET nickname = '$name' WHERE id = $id ");
	if($update){
			$return_data['type']= true;
			$query = mysqli_query($conn,"SELECT * FROM nsu_added_device WHERE id =  $id");

			$return_data['data']= mysqli_fetch_object($query);

	}else{
		
			$return_data['type']= false;

			$return_data['data']= array();

	}
	echo json_encode($return_data);
	exit();

}

if(isset($_POST['deleting'])){
	$return_data = array();
	$id=  $_POST['id'];

	$delete  =  mysqli_query($conn,"DELETE  FROM nsu_added_device where id = $id");
	if($delete){
		$return_data['type']=  true;

	}else{
		$return_data['type'] = false;
	}
	echo json_encode($return_data);
	exit();
}


if(isset($_GET['GetHomeData'])){
	$return_data=array();
	$uid =$_GET['uid'];
	$gid =$_GET['gid'];

	$query1= mysqli_query($conn,"SELECT * FROM nsu_settings WHERE uid = '$uid'");

	$query2 = mysqli_query($conn,"SELECT * FROM nsu_added_group 
								WHERE uid = '$uid' 
								AND gid = '$gid' ");


	$return_data['currentGroup'] = mysqli_fetch_object($query2);

	$return_data['settings'] = mysqli_fetch_object($query1);

	echo json_encode($return_data);
	exit();
}
if(isset($_POST['SetHomeData'])){
	$return_data=array();
	$uid =$_POST['uid'];
	$field = $_POST['field'];
	$value =$_POST['value'];
	$query1= mysqli_query($conn,"SELECT * FROM nsu_settings WHERE uid = '$uid'");
	if(mysqli_num_rows($query1)>0){
		$update = mysqli_query($conn,"UPDATE `nsu_settings` SET
									`$field` = '$value'");
		if($update){
			$return_data['type'] = true;
		}else{
			$return_data['type'] = false;

		}
	}else{
		$insert =  mysqli_query($conn,"INSERT INTO `pq_settings`
										(`uid`,
										 `field`) 
										 VALUES('$uid','$value')");

		if($insert){
			$return_data['type'] = true;
		}else{
			$return_data['type'] = false;

		}
	}

	echo json_encode($return_data);
	exit();


}
?>