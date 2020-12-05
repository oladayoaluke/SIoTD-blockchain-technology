<?php 
include "db.php";

//https://www.php.net/manual/en/function.openssl-pkey-new.php
//Working example:
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

if(isset($_GET['generate'])){
    // this function can be used by added_device and users table
    
    $return_data= array();
    $id = $_GET['id_field'];
    $table = $_GET['table'];
    $public_field = $_GET['public_field'];
    $private_field = $_GET['private_field'];
    $date = date('Y-m-d H:i:s');
    $date = strtotime($date);

    if(empty($public_field) || empty($private_field)){
        $public_field = 'public_key';
        $private_field = 'private_key';
    }

    $config = array(
        "digest_alg" => "sha512",
        "private_key_bits" => 4096,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );

    // Create the private and public key
    $res = openssl_pkey_new($config);

    // Extract the private key from $res to $privKey
    openssl_pkey_export($res, $privKey);

    // Extract the public key from $res to $pubKey
    $pubKey = openssl_pkey_get_details($res);
    $pubKey = $pubKey["key"];

	if(!empty($pubKey) && !empty($privKey)){       

        $update = mysqli_query($conn,"UPDATE `$table` SET 
		`$public_field`='$pubKey',
        `$private_field`='$privKey',
        `updated`='$date' 
        WHERE uid= '$uid' AND eid  = '$device_id' AND adid = '$added_device_id' ");

        if($update){
            $return_data['type']= true;
            $return_data['message'] = 'Successfully generated crypto details!';
        }

	}else{
		$return_data['type']= false;
        $return_data['message'] = 'Failed to generate crypto details!';
	}

	echo json_encode($return_data);
	exit();
}

if(isset($_GET['encrypt_w_private_key'])){
	$return_data= array();
	$uid = $_GET['u_id'];
    $message = $_GET['message'];

    $get_qeury = mysqli_query($conn,"SELECT private_key from nsu_users 
                                where uid= $uid");

    $privKey = mysqli_fetch_object($get_qeury);
             
    if(openssl_private_encrypt($message , $encrypted, $privKey) ){
        $return_data['type']= true;
        $return_data['check'] = $encrypted;
        $return_data['message']= 'Successfully performed private encryption';
    }
                              
	echo json_encode($return_data);
	exit();
}

if(isset($_GET['encrypt_w_public_key'])){
	$return_data= array();
	$uid = $_GET['u_id'];
    $message = $_GET['message'];

    $get_query = mysqli_query($conn,"SELECT public_key from nsu_users 
                                where uid= $uid");

    $privKey = mysqli_fetch_object($get_query);            
    
    // Encrypt the data to $encrypted using the public key
    if(openssl_public_encrypt($message , $encrypted, $pubKey) ){
        $return_data['type']= true;
        $return_data['check'] = $encrypted;
        $return_data['message'] = 'Successfully performed public encryption';
    }                      

	echo json_encode($return_data);
	exit();
}

if(isset($_GET['decrypt_w_private_key'])){
	$return_data= array();
	$uid = $_GET['u_id'];
    $type = $_GET['public'];
    $message = $_GET['message'];	

    $get_query = mysqli_query($conn,"SELECT private_key from nsu_users 
                                where uid= $uid");

    $privKey = mysqli_fetch_object($get_query);               
    
    if(openssl_private_decrypt($message , $decrypted, $privKey) ){
        $return_data['type']= true;
        $return_data['check'] = $decrypted;
        $return_data['message']= 'Successfully performed private encryption';
    }

	echo json_encode($return_data);
	exit();
}

if(isset($_GET['decrypt_w_public_key'])){
	$return_data= array();
	$uid = $_GET['u_id'];
    $message = $_GET['message'];	

    $get_query = mysqli_query($conn,"SELECT private_key from nsu_users 
                                where uid= $uid");

    $privKey = mysqli_fetch_object($get_query);
             
    
    if(openssl_public_decrypt($message , $decrypted, $privKey) ){
        $return_data['type']= true;
        $return_data['check'] = $decrypted;
        $return_data['message']= 'Successfully performed public encryption';
    }

	echo json_encode($return_data);
	exit();
}

if(isset($_GET['generate_ticket'])){
    $return_data= array();
    $uid = $_GET['uid'];
    $gid = $_GET['gid'];
    $field = $_GET['field'];

    if(empty($field)){
        $field = 'ticket';
    }

    $ticket = '';
    $length = 64;
    if (function_exists("random_bytes")) {
        $bytes = random_bytes(ceil($length / 2));
        $ticket = substr(bin2hex($bytes), 0, $length);
    }else if(function_exists("bin2hex")) {
        $bytes = openssl_random_pseudo_bytes($length);
        $ticket = bin2hex($bytes);
    }else if(function_exists("base64_encode")){
        $bytes = openssl_random_pseudo_bytes($length);
        $ticket = base64_encode($bytes);
    }

    if($ticket){
        $get = mysqli_query($conn,"SELECT * from nsu_added_device 
								where uid= $uid AND gid =  $gid");
    
        $i=0;
        $ret = array();
        while($data = mysqli_fetch_array($get)){
            $id = $data['id'];
            $update = mysqli_query($conn,"UPDATE nsu_added_device SET 
                    `$field`='$ticket',
                    `updated`='$date' 
                    WHERE id= '$id' AND gid  = '$gid' ");
            $i=$i+1;           

        }
    

        
        if($update){
            $return_data['type']= true;
            $return_data['message'] = 'Successfully generated ticket for '+$i+' devices!';
        }else{
            $return_data['type']= false;
            $return_data['message'] = 'Failed to generate tickets!';
        }
    }
	

	echo json_encode($return_data);
	exit();
}

if(isset($_GET['get_private_key'])){
	$return_data= array();
    $id = $_GET['id_field'];
    $id_value = $_GET['id_value'];
    $table = $_GET['table'];


    $get_qeury = mysqli_query($conn,"SELECT private_key from `$table` 
                                where $id = $id_value");

    $privKey = mysqli_fetch_object($get_qeury);
             
    if($privKey){
        $return_data['type']= true;
        $return_data['check'] = $privKey ;
        $return_data['message']= 'Successfully extracted private key';
    }
                              
	echo json_encode($return_data);
	exit();
}

if(isset($_GET['get_ticket'])){
	$return_data= array();
    $uid = $_GET['uid'];
    $gid = $_GET['gid'];
    $field = $_GET['field'];

    if(empty($field)){
        $field = 'ticket';
    }

    $get_qeury = mysqli_query($conn,"SELECT `$field` from `nsu_added_device` 
                                where uid= $uid AND gid =  $gid");

    $ticket = mysqli_fetch_object($get_qeury);
             
    if($ticket){
        $return_data['type']= true;
        $return_data['check'] = $ticket ;
        $return_data['message']= 'Successfully extracted private key';
    }
                              
	echo json_encode($return_data);
	exit();
}
















?>