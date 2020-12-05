<?php 
include 'db.php';
include 'functions.php';
include 'CryptoFunctions.php';

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




if(isset($_GET['fetching'])  )
{
	$uid=  $_GET['u_id'];

	// $get_device_type
	$return_data = array();
	$get_device_type_query = mysqli_query($conn,"SELECT * FROM nsu_device");

	$types = array();
	while($device_type  = mysqli_fetch_array($get_device_type_query)){
		
		$types[] = array('id'=>$device_type['id'],'name'=> $device_type['name']);
	
	}

	//slice the data into two // TODO: 
	$offset = sizeof($types)/2;
	$types1 = array_slice($types, 0, $offset);//first half
	$types2 = array_slice($types, sizeof($types) - $offset);//second half
	

	// $get_equip_type

	$get_equip_query = mysqli_query($conn,"SELECT * FROM nsu_equipment_type ");
	

	$equip = array();
	while($equipments  = mysqli_fetch_array($get_equip_query)){
		
	$equip[] = array('id'=>$equipments['id'],'name'=> $equipments['name']);

	
	}


	$device_categorys_query = mysqli_query($conn,"SELECT * FROM nsu_added_category  where uid = $uid");
	$device_category = array();
	while($d_category  = mysqli_fetch_array($device_categorys_query)){
		$device_category[] = array('cid'=>$d_category['cid'],'name'=> $d_category['nickname']);
	}	

	$return_data['device_categorys']  =$device_category;
	$return_data['device_types'] = $types;
	$return_data['device_types1'] = $types1;
	$return_data['device_types2'] = $types2;
	$return_data['equipments'] = $equip;
	
	echo json_encode($return_data);
	exit();

}

if(isset($_POST['inserting']) )
{
	$return_data = array();
	$uid = $_POST['user_id'];
	$dtid = $_POST['device_type'];
	$nickname = $_POST['name'];
	$prog_code = $_POST['code'];
	$selected_category = $_POST['selected_category'];							

	$created = date('Y/m/d');

	// There can be only one adapter in a category according to current design
	// As a result, if one of the following ids is present, 1,2,3, 4, tell user to add new 
	// adapter under new category. Others, we will need different control pages for 1,2,3 and 4
	$isEligible = true;
	if($dtid <= 4){
		$check1 =  mysqli_query($conn,"SELECT * FROM nsu_added_device where uid = $uid AND cid = $selected_category AND dtid in (1,2,3,4)");
		if(mysqli_num_rows($check1)>0) {
			$return_data['type'] = false;
			$return_data['message'] = "Device type already exist in this category! Add to another/new category";
			$isEligible = false;
		}
	}else{
		$check2 =  mysqli_query($conn,"SELECT * FROM nsu_added_device where uid = $uid AND cid = $selected_category AND dtid = $dtid");
		if(mysqli_num_rows($check2)>0) {
			$return_data['type'] = false;
			$return_data['message'] = "Device type already exist in this category! Add to another/new category";
			$isEligible = false;
		}
	}
	
	
	if($isEligible){
		//get ticket
		$ticket ="";
		$ticket_query =  mysqli_query($conn,"SELECT ticket FROM nsu_added_category where uid = $uid AND cid = $selected_category ");
		if(mysqli_num_rows($ticket_query)>0) {
			$ticket_data = mysqli_fetch_array($ticket_query);
			$ticket = $ticket_data['ticket'];
		}

		if($ticket){

			$insert = mysqli_query($conn,"INSERT INTO `nsu_added_device`(`uid`,`cid`, `dtid`, `nickname`, `prog_code`, `ticket`, `created`) 
						VALUES($uid,$selected_category,$dtid,'$nickname','$prog_code','$ticket', '$created')");

			if($insert){
				//////////////////////////////////////////////////////////////////////////////
				// CRYPTO CREDENTIALS                                                      //
				/////////////////////////////////////////////////////////////////////////////
				$adid = mysqli_insert_id($conn);

				$crypto_data = generate_ecc_crypto_profile($conn, 'id', $adid, 'nsu_added_device');
				if($crypto_data['type']){
					$return_data['type'] = true;
					$return_data['message'] = "Successfully Added Device and credentials";
				}else{
					$return_data['message'] = "Failed to  add  " + $crypto_data['message'];
				}
			
			}else{
				$return_data['type'] = false;
				$return_data['message'] = "Having Problem In Adding Device".mysqli_error($conn).$adid;
			
			}
		}else{
			$return_data['type'] = false;
			$return_data['message'] = $ticket."Having Problem In Adding Device ticket".mysqli_error($conn);
		}
	}//end of eligible
		
	echo json_encode($return_data);
	exit();
}

if(isset($_GET['getAddedCategory']) )
{
	$data=array();
	$return_data=array();
	$u_id = $_GET['user_id'];
	$get_added_category = mysqli_query($conn,"SELECT * FROM nsu_added_category where uid =".$u_id);

	if(mysqli_num_rows($get_added_category)>0) {
		while($added_category = mysqli_fetch_array($get_added_category)){
			$data[] = array('cid'=>$added_category['cid'],
									'nickname'=>$added_category['nickname'],
									'address'=>$added_category['address']
									);
		}

		$return_data['type'] = true;
		$return_data['data']= $data;
		$return_data['message']= "Found ".sizeof($data)." categorys!";
	}else{
		$return_data['type'] = false;
		$return_data['data']= $data;
		$return_data['message']= "Found ".sizeof($data)." categorys!";
	}

	
	echo json_encode($return_data);
	exit();
}


if(isset($_GET['get_device_categorys']) )
{
	$uid=  $_GET['u_id'];
	$type_id = $_GET['type_id'];
	$device_category = array();
	$check =  mysqli_query($conn,"SELECT * FROM nsu_added_device where uid = $uid AND dtid = $type_id");
	
	$dataa = 0;
	$nsu_added_device = array();
	if(mysqli_num_rows($check)>0) {
		while($dataa = mysqli_fetch_array($check)){
			$nsu_added_device[] = array('id'=>$dataa['id'],'cid'=>$dataa['cid'],'dtid'=>$dataa['dtid']);
		}

		$cid_ = array();
		foreach ($nsu_added_device as $value) {

			$cid_[] =   $value['cid'];
			

		}
		$cid = implode($cid_, ',');

		$added_category_query  = mysqli_query($conn,"SELECT * FROM nsu_added_category 
											where uid  = $uid AND cid  NOT IN( $cid )");

		while($addd =  mysqli_fetch_array($added_category_query)){
			$device_category[] = array('cid'=>$addd['cid'],'name'=>$addd['nickname']);
		}

	}else{

		$added_category_query  = mysqli_query($conn,"SELECT * FROM nsu_added_category 
										where uid  = $uid ");

		while($addd =  mysqli_fetch_array($added_category_query)){
			$device_category[] = array('cid'=>$addd['cid'],'name'=>$addd['nickname']);
		}
	}

	echo json_encode($device_category);
	exit();

}


?>