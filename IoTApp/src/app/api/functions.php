<?php


//----------------------------------------------------------------------
//     VALIDATE GROUP TICKET
//----------------------------------------------------------------------
function validate_group_ticket($conn, $in_api_key, $uid, $adid)
{
	$return_data  = array();
	if( isset($in_api_key) && isset($uid) && isset($adid) )
	{	
		$check_query = mysqli_query($conn, "SELECT * FROM nsu_added_device WHERE id = '$adid' AND uid = '$uid' AND ticket = '$in_api_key' ");
		if(mysqli_num_rows($check_query)>0)
		{
			return True;
			//Do nothing
		}
		else
		{
			$return_data['type'] = false;
			$return_data['message'] = "API Is Wrong";
			$return_data['data'] = array("in_api_key"=>$in_api_key,"uid"=>$uid,"adid"=>$adid);
			echo json_encode($return_data);
			exit(); //exit only when its invalid
		}
		
	}
	else
	{
		$return_data['type'] = false;
		$return_data['message'] = "Authorization data not set!";
		$return_data['data'] = array();
		echo json_encode($return_data);
		exit(); //exit only when its invalid
	}
	
	
	
}

//----------------------------------------------------------------------
//     VALIDATE API KEY
//----------------------------------------------------------------------
function validate_api_key($conn, $in_api_key)
{
	$return_data = array();

	if(isset($in_api_key))
	{
		$check_query = mysqli_query($conn, "SELECT * FROM nsu_admin_info WHERE  title= 'api_key' AND value = '$in_api_key' ");
		if(mysqli_num_rows($check_query) > 0)
		{
			return True;
		}
		else
		{
			$return_data = array('message'=>$in_api_key." API Authorization failed");
			echo json_encode($return_data);
			exit(); //exit only when its invalid
		}
		
	}
	else
	{
		$return_data = array('message'=>"Invalid or expired API key!");	
		echo json_encode($return_data);
		exit(); //exit only when its invalid
	}
	
	
}


function limit_record($conn, $table, $uid, $adid)
{
	$MAXIMUM_SENSOR_RECORD = 200;
	$isdelete = False;
	//limit record
	$check_record = mysqli_query($conn, "SELECT * FROM `$table`  where uid = '$uid' AND adid = '$adid' ");
	if(mysqli_num_rows($check_record) > $MAXIMUM_SENSOR_RECORD)
	{
		$isdelete = mysqli_query($conn, "DELETE FROM `$table` where uid = '$uid' AND adid = '$adid' ");		
	}
	return  $isdelete;
}

function validate_password($password){
    $results = true;
    //check for all digits
    if(strlen(preg_replace("/[^0-9]/", '', $password)) == strlen($password)){
        $results = false;
    }
    return $results;
}

function protect($string) {
	$protection = htmlspecialchars(trim($string), ENT_QUOTES);
	return $protection;
}

function randomHash($lenght = 7) {
	$random = substr(md5(rand()),0,$lenght);
	return $random;
}

function isValidURL($url) {
	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

function toDbPhoneFormat($phone){
    //return implode(array_filter(str_split('"'.$phone.'"', 1), "is_numeric"));
   return preg_replace("/[^0-9]/", "", $phone);
}


?>