<?php 
include "db.php";
require_once ("Blockchain.php");

//Code Example : https://www.php.net/manual/en/function.openssl-pkey-new.php
//Installation : https://tutorials.webencyclop.com/blog/install-ssl-on-windows-localhost-wamp-http-ssl-https/

use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Serializer\PrivateKey\PemPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PrivateKey\DerPrivateKeySerializer;

use Mdanter\Ecc\Serializer\PublicKey\PemPublicKeySerializer;
use Mdanter\Ecc\Serializer\PublicKey\DerPublicKeySerializer;

use Mdanter\Ecc\Serializer\Point\UncompressedPointSerializer;


function generate_ecc_crypto_profile($conn, $id, $id_value, $table, $public_field='', $private_field=''){
    // this function can be used by added_device and users table
    $return_data= array();
    $date = date('Y-m-d H:i:s');
    $date = strtotime($date);

    if(empty($public_field) || empty($private_field)){
        $public_field = 'public_key';
        $private_field = 'private_key';
    }

     ///////////////////////////////////////////////////////////////////////////////////
    //                    ENCRYPTION ALGORITHEM SELECTION                            //
    ///////////////////////////////////////////////////////////////////////////////////
    $adapter = EccFactory::getAdapter();
    //$generator = EccFactory::getNistCurves()->generator384();
    $generator  = EccFactory::getSecgCurves()->generator256k1();//MATCHES AUTHOR'S

    ///////////////////////////////////////////////////////////////////////////////////
    //                             PRIVATE KEY                                       //
    ///////////////////////////////////////////////////////////////////////////////////
    $private = $generator->createPrivateKey();

    $derSerializer = new DerPrivateKeySerializer($adapter);
    $der = $derSerializer->serialize($private);
    echo sprintf("DER encoding:\n%s\n\n", base64_encode($der));

    $pemSerializer = new PemPrivateKeySerializer($derSerializer);
    $priKey = $pemSerializer->serialize($private);
    echo sprintf("PEM encoding:\n%s\n\n", $priKey);

    $encodedPriv = hash('sha256', $priKey, false);//64 length
    echo  "Private Key hash ".  $encodedPriv. " len : ".strlen($encodedPriv)."\n";
    //echo '->'. base64_decode(hexdec(bin2hex($encodedPriv)));//bin2hex($encodedPriv)


    ///////////////////////////////////////////////////////////////////////////////////
    //                             PUBLIC KEY                                        //
    ///////////////////////////////////////////////////////////////////////////////////
    $public                 = $private->getPublicKey();
    $pubKeySerializer       = new PemPublicKeySerializer(new DerPublicKeySerializer($adapter));
    $pubkey                 = $pubKeySerializer->serialize($public);
    echo sprintf("Public PEM encoding:\n%s\n\n", $pubkey);

    $pointSerializer = new UncompressedPointSerializer($adapter);
    $encodedPub = base64_encode(hex2bin($pointSerializer->serialize($public->getPoint())));
    echo  " Encoded Public Key : ".  $encodedPub. " len : ".strlen($encodedPub)."\n";

    $encodedPub = hash('sha256',  $pubkey, false);//64 length
    echo  "Private Key hash ".  $encodedPub. " len : ".strlen($encodedPub)."\n";
     
	if(!empty($pubKey) && !empty($privKey)){ 

        $passphrase = generate_ticket();

        $account = generate_blockchain_account($privKey, $passphrase);

        if(!empty($passphrase) && !empty($account)){ 
            //update record with assymetric encryption keys
            $update = mysqli_query($conn,"UPDATE `$table` SET 
                                `$public_field`='$pubKey',
                                    `$private_field`='$privKey',
                                    `passphrase`=$passphrase,
                                    `blockchain_acct`:'$account'
                                    `updated`='$date'   WHERE `$id`= '$id_value' ");

            if($update){
                $return_data['type']= true;
                $return_data['message'] = 'Successfully generated crypto details!';
            }else{
                $return_data['type']= false;
                $return_data['message'] = 'Failed to update with crypto details!';
            }

        }
        else
        {
            $return_data['type']= false;
            $return_data['message'] = 'Failed to generate blockchain account!';
        }

	}else{
		$return_data['type']= false;
        $return_data['message'] = 'Failed to generate crypto details!';
	}

	return $return_data;
}



function generate_blockchain_account($priKey, $passphrase)
{
    $device_ethereum_acct ="";
    //////////////////////////////////////////////////////////////////////////////
    // ETHERUEM ACCOUNT                                                         //
    /////////////////////////////////////////////////////////////////////////////
    $owner_account = "";
    $check_query = mysqli_query($conn, "SELECT * FROM nsu_admin_info WHERE  title= 'ethereum_owner_account' ");
    if(mysqli_num_rows($check_query) > 0)
    {
        $owner_data = mysqli_fetch_array($ticket_query);
        $owner_account = $owner_data['ethereum_owner_account'];
    }

    $ethereum_host = "";
    $check_query = mysqli_query($conn, "SELECT * FROM nsu_admin_info WHERE  title= 'ethereum_host' ");
    if(mysqli_num_rows($check_query) > 0)
    {
        $ethereum_host_data = mysqli_fetch_array($ticket_query);
        $ethereum_host = $ethereum_host_data['ethereum_host'];
    }

    $contract_address = "";
    $check_query = mysqli_query($conn, "SELECT * FROM nsu_admin_info WHERE  title= 'contract_address' ");
    if(mysqli_num_rows($check_query) > 0)
    {
        $contract_address_data = mysqli_fetch_array($ticket_query);
        $contract_address =contract_address_data['contract_address'];
    }

    $ethereum_port = "";
    $check_query = mysqli_query($conn, "SELECT * FROM nsu_admin_info WHERE  title= 'ethereum_port' ");
    if(mysqli_num_rows($check_query) > 0)
    {
        $ethereum_port_data = mysqli_fetch_array($ticket_query);
        $ethereum_port =ethereum_port_data['ethereum_port'];
    }

    if(!empty(owner_account)  &&  !empty(ethereum_host)  && !empty(contract_address)  && !empty(ethereum_port) )
    {
        $blockchain = new Blockchain($ethereum_host, $ethereum_port);
        if($blockchain)
        {
            $blockchain->setMyExternalAddress($owner_account) ;
            $blockchain->setContractAddress($contract_address);
            $device_ethereum_acct = $blockchain->generate_account($priKey, $passphrase);

        }
    }

    return $device_ethereum_acct;
}

function encrypt_w_private_key($conn, $id, $id_value, $table,$message, $private_field=''){
	$return_data= array();

    if(empty($private_field)){
        $private_field = 'private_key';
    }

    $get_qeury = mysqli_query($conn,"SELECT `$private_field` from `$table` 
                                where `$id`= $id_value");

    $privKey = mysqli_fetch_object($get_qeury);
             
    if(openssl_private_encrypt($message , $encrypted, $privKey->$private_field) ){
        $return_data['type']= true;
        $return_data['check'] = $encrypted;
        $return_data['message']= 'Successfully performed private encryption';
    }
                              
	return $return_data;
}

function encrypt_w_public_key($conn, $id, $id_value, $table,$message, $public_field=''){
    $return_data= array();
    if(empty($public_field)){
        $public_field = 'public_key';
    }

    $get_qeury = mysqli_query($conn,"SELECT `$public_field` from `$table` 
                                                where `$id`= $id_value");

    $privKey = mysqli_fetch_object($get_query);            
    
    // Encrypt the data to $encrypted using the public key
    if(openssl_public_encrypt($message , $encrypted, $pubKey->$public_field) ){
        $return_data['type']= true;
        $return_data['check'] = $encrypted;
        $return_data['message'] = 'Successfully performed public encryption';
    }                      

	return $return_data;
}

function decrypt_w_private_key($conn, $id, $id_value, $table,$message, $private_field='')
{
    $return_data= array();
    
    if(empty($private_field)){
        $private_field = 'private_key';
    }

    $get_query = mysqli_query($conn,"SELECT `$private_field` from `$table` 
                                where uid= $uid");

    $privKey = mysqli_fetch_object($get_query);               
    
    if(openssl_private_decrypt($message , $decrypted, $privKey->$private_field) ){
        $return_data['type']= true;
        $return_data['check'] = $decrypted;
        $return_data['message']= 'Successfully performed private encryption';
    }

	return $return_data;
}

function decrypt_w_public_key($conn, $id, $id_value, $table, $message, $public_field=''){
	$return_data= array();

    if(empty($public_field)){
        $public_field = 'public_key';
    }

    $get_query = mysqli_query($conn,"SELECT `$public_field` from `$table` 
                                where uid= $uid");

    $privKey = mysqli_fetch_object($get_query);
             
    
    if(openssl_public_decrypt($message , $decrypted, $privKey->$public_field) ){
        $return_data['type']= true;
        $return_data['check'] = $decrypted;
        $return_data['message']= 'Successfully performed public encryption';
    }

	echo json_encode($return_data);
	exit();
}


function generate_ticket(){
    $ticket ='';
    $length = 32;
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
    return $ticket;
}

function assign_ticket_to_device($conn, $uid, $gid, $adid, $field=''){
    $return_data= array();
    $date = date('Y-m-d H:i:s');
    $date = strtotime($date);

    if(empty($field)){
        $field = 'ticket';
    }

    $get = mysqli_query($conn,"SELECT * from nsu_added_device 
                            where uid= '$uid' AND  gid =  '$gid' ");

    if( mysqli_num_rows($get)>0 ){
        $check = mysqli_fetch_object($get);
        var_dump($check);
        // get exisiting group ticket
        $ticket = '';
        $ticket = $check->$field;
        if($ticket){            
            $update = mysqli_query($conn,"UPDATE nsu_added_device SET 
                    `$field`='$ticket',
                    `updated`='$date' 
                    WHERE uid= '$uid' AND id =  '$adid' AND gid  = '$gid' ");
        }else{
            $ticket = generate_ticket();

            $update = mysqli_query($conn,"UPDATE nsu_added_device SET 
                    `$field`='$ticket',
                    `updated`='$date' 
                    WHERE uid= '$uid' AND id =  '$adid' AND gid  = '$gid' ");
            if($update){
                $return_data['type']= true;
                $return_data['message']= 'Successfully added ticket for group '+ $gid;
            }else{
                $return_data['type']= false;
                $return_data['message']= 'Failed to add ticket for group '+ $gid;
            }
        }


    }else{
        $return_data['type'] = false;
        $return_data['message']= 'No record of group '+ $gid + ' and adid '+ $adid;
    }

    return $return_data;
}

function get_private_key($conn, $id, $id_value, $table, $field=''){
    $return_data= array();
    
    if(empty($field)){
        $field = 'private_key';
    }

    $get_qeury = mysqli_query($conn,"SELECT `$field` from `$table` 
                                where $id = $id_value");

    $privKey = mysqli_fetch_object($get_qeury);
             
    if($privKey){
        $return_data['type']= true;
        $return_data['check'] = $privKey->$field;
        $return_data['message']= 'Successfully extracted private key';
    }
                              
	return $return_data;
}

function get_ticket($conn, $uid, $gid, $field=''){
    $return_data= array();
    
    if(empty($field)){
        $field = 'ticket';
    }

    $get_qeury = mysqli_query($conn,"SELECT `$field` from `nsu_added_group` 
                                where uid= $uid AND gid =  $gid");

    $ticket = mysqli_fetch_object($get_qeury);
             
    if($ticket){
        $return_data['type']= true;
        $return_data['check'] = $ticket->$field ;
        $return_data['message']= 'Successfully extracted ticket';
    }
                              
	return $return_data;
}




?>