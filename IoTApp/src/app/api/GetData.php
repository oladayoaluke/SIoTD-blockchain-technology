<?php
include ('db.php');
include ('functions.php');
/**
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'user id',
  `gid` int(11) DEFAULT NULL COMMENT 'group id(each group needs new gid)',
  `start_status` enum('0','1') DEFAULT '0' COMMENT 'equipment adapter on and off',
  `t_switch_status` enum('0','1') DEFAULT '0' COMMENT 'transfer switch on and off',
  `p_switch_status` enum('0','1') DEFAULT '0' COMMENT 'phase on and off',
  `t_switch_value` enum('0','1','2') DEFAULT '0' COMMENT 'equipment adapter on and off',
  `auto_tswitch` enum('0','1') DEFAULT '0' COMMENT 'auto transfer on and off',
  `p_switch_value` enum('0','1','2') DEFAULT '0' COMMENT 'equipment adapter on and off',
  `auto_pswitch` enum('0','1') DEFAULT '0' COMMENT 'auto transfer on and off',
  `auto_navigation` enum('0','1') DEFAULT '0' COMMENT 'toggled to 0 when manual is on.',
  `max_speed` int(11) NOT NULL DEFAULT '0' COMMENT 'robot max speed ',
  `a_battery_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'enable battery recharge ',
  `a_battery_level` int(11) NOT NULL DEFAULT '10' COMMENT 'battery recharge max level',
  `r_battery_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'enable battery recharge',
  `r_battery_level` int(11) NOT NULL DEFAULT '10' COMMENT 'battery recharge max level',
  `t_battery_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'enable battery recharge',
  `t_battery_level` int(11) DEFAULT '10' COMMENT 'battery recharge max level',
  `p_battery_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'enable battery recharge',
  `p_battery_level` int(11) DEFAULT '10' COMMENT 'battery recharge max level',
  `w_battery_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'enable battery recharge ',
  `w_battery_level` int(11) NOT NULL DEFAULT '10',
  `updated` int(11) DEFAULT NULL COMMENT 'last time updated',
  `created` int(11) DEFAULT NULL COMMENT 'date and time created',
  **/

//----------------------------------------------------------------------
//     VALIDATE API KEY
//----------------------------------------------------------------------
if(isset($_REQUEST["api_key"]))
{
	$in_api_key = protect($_GET["api_key"]);
	validate_api_key($conn, $in_api_key);// should return true
}
else
{
	return "Set Request API ";
}
//----------------------------------------------------------------------
//     END OF VALIDATE API KEY
//----------------------------------------------------------------------


if(isset($_GET['GetSetUpData']))
{
	$return_data = Array();
	$id = protect($_GET["id"]); 
	//add more as needed
	$get_qeury = mysqli_query($conn,"SELECT id,uid,dtid,gid,nickname,ticket FROM `nsu_added_device` WHERE uid=".$id);

	if(mysqli_num_rows($get_qeury)>0){	
		//Don't send nested json for microcontroller, it will fail to parse it
		echo json_encode(mysqli_fetch_object($get_qeury));
	}
	else
	{
		$return_data['message'] = "No Record!";
		echo json_encode($return_data);
	}
	
	
	exit();
}





?>