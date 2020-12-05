<?php


require __DIR__ . "/vendor/autoload.php";// for ECC
require_once ("Blockchain.php");
require_once ("CryptoFunctions.php");
require_once ("db.php");


use Mdanter\Ecc\Crypto\Signature\SignHasher;
use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Crypto\Signature\Signer;
use Mdanter\Ecc\Serializer\PrivateKey\PemPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PrivateKey\DerPrivateKeySerializer;
use Mdanter\Ecc\Serializer\Signature\DerSignatureSerializer;

//define('DEBUG', true);

define('KECCAK_HASH_SIZE', 32) ;
define('PRIVKEY_SIZE',     32) ;
define('PUBKEY_SIZE',      64) ;
define('ADDR_SIZE',        20)  ;
define('TICKET_SIZE',      40)  ;
define('SIG_SIZE',         64);


////////////////////////////////////////////////////////////////
//    FILL IN BLOCKCHAIN DETAILS                             //
///////////////////////////////////////////////////////////////


$ip = '127.0.0.1';
$port = '7545';
$contractAddress = '0x97bc13ee377ea31baafed12fb6b54428055b0060';
$publicAddress   = '0xa819e3f81dee85eb2abfdc9ae67b3d96d43e5c27';
$category = 0; //0 is master and 1 is follower
$uid = 5;
$groupId = '000110';//111
$nodeId = '000023'; //23 & 24 //$adid
//$groupId = '000110';//110
//$nodeId = '000029'; //29 & 30
//if (strlen($privateKey) != PRIVKEY_SIZE)  {

    
    
// Public PEM encoding:
// -----BEGIN PUBLIC KEY-----
// MFYwEAYHKoZIzj0CAQYFK4EEAAoDQgAEC8VMMOHxbHtp74/9ht5AgwhPKu0KSia7
// Wwk+nh/mVkoYqKFZn1oUlB/pidUzeT0QxC2z/Na/NiLrV22ZLcN7qw==
// -----END PUBLIC KEY-----

$masterPrivateKey = '-----BEGIN EC PRIVATE KEY-----
MHQCAQEEIA9xSkAZ7XZ7imJpljC+OXZQ1OK5txQ9oTznCDHoMCsXoAcGBSuBBAAK
oUQDQgAEC8VMMOHxbHtp74/9ht5AgwhPKu0KSia7Wwk+nh/mVkoYqKFZn1oUlB/p
idUzeT0QxC2z/Na/NiLrV22ZLcN7qw==
-----END EC PRIVATE KEY-----';//$eccPrivateKey;//'f43fa2f51de02abf2661a0e5a5990f048841d20fea304b34580965c4b1e68a49';





//function get_ticket($conn, $uid, $gid, $field=''){
$ticket4mDb = get_ticket($conn, $uid, intval($groupId), 'ticket');
//echo '=============>'.$ticket4mDb['check'] ;

$groupTicket = substr($ticket4mDb['check'], 0, TICKET_SIZE);
echo  $groupTicket; 

$signature_data = sign_transaction_data($masterPrivateKey, $groupId, $nodeId, $groupTicket);
$signature = $signature_data['r'].$signature_data['s'];


$blockchain = new Blockchain($ip, $port);



$test = array(0,1,2, 3, 4,5,6,12, 13,14);

function test_result($testnum, $flag)
{
    if($flag)
    {
        print "Test case ".$testnum." passed!\n";
    }
    else
    {
        throw new Exception("Test case ".$testnum." failed!\n");
    }

    
}

//Test 0
$test_case = 0;
if (in_array($test_case, $test)) {
    $transactionHash ;
    $warnings = array() ;
    $res = 0 ;

    
    if (strlen($ip) < 7)
       $warnings[] = "Check the validity of your IP address..\n"      ;
    if (empty($port ))
       $warnings[] = ("Check the validity of your port value..\n"     ) ;
    if (strlen($contractAddress) < 40 || strlen($contractAddress) > 42)
       $warnings[] = ("Check the validity of the contract address..\n") ;
    if (empty($groupId) || strcmp($groupId, "00000000") == 0)
       $warnings[] = ("Check the validity of your group ID..\n"       ) ;
    if (empty($nodeId) || strcmp($nodeId, "00000000") == 0)
       $warnings[] = ("Check the validity of your node ID..\n"        ) ;
    if (strlen($publicAddress) < 40  || strlen($publicAddress) > 42)
       $warnings[] = ("Check the validity of your public address..\n" ) ;
    if ((strlen($signature) != 0) && (strlen($signature) != 128))
       $warnings[] = ("Check the validity of your ticket <length should = 64 bytes or none>..\n" );

    if (!empty($warnings)) {
        var_dump($warnings) ;
        test_result(3, 0);
        return ;
    }

    if(strlen($contractAddress) == 42)
    {
        $contractAddress = substr($contractAddress, 2, strlen($contractAddress));
    }

    if(strlen($publicAddress) == 42)
    {
        $publicAddress = substr($publicAddress, 2, strlen($publicAddress));
    }

    //$blockchain->EncodeString($param);


    $blockchain->setMyExternalAddress($publicAddress) ;
    $blockchain->setContractAddress($contractAddress);



    $paramsTypes = array("uint8","uint8","uint8","uint256","uint256") ;
    $paramsValues = array();

    $paramsValues[] = ($blockchain->EncodeUint8 ($category)) ;              // category
    $paramsValues[] = ($blockchain->EncodeUint8(intVal($groupId)))  ;       // groupe ID
    $paramsValues[] = ($blockchain->EncodeUint8(intval($nodeId)))  ;       // device ID

    $paramsValues[] = ($blockchain->EncodeUint256(substr($signature, 0, 64)))  ;  // r --> (signature parameter 1)
    $paramsValues[] = ($blockchain->EncodeUint256(substr($signature, 65, strlen($signature))))  ;  // s --> (signature parameter 2)

    $transactionHash = $blockchain->CallFunction($blockchain->getMyExternalAddress(),
                                                $blockchain->getContractAddress(),
                                                $blockchain->EncodeFunction($blockchain->EncodeFunctionSelector("SIoTDSC__AddNode(uint8,uint8,uint8,uint256,uint256)"),
                                                                           $paramsTypes,
                                                                           $paramsValues),
                                                "eth_sendTransaction"
                                                ) ;

    if(defined('DEBUG'))
    {
        print " Test case ".$test_case." transaction receipt : ".$transactionHash."\n";
    }
    print " Test case ".$test_case." transaction receipt : ".$transactionHash->getCode()."\n";
    print " Test case ".$test_case." transaction receipt : ".var_dump($transactionHash->getMessage())."\n";

    //if($param == $value2) { test_result($test_case , 1); }else{ test_result($test_case , 0); };

}


//Test 1
$test_case = 1;
if (in_array($test_case , $test)) {
    $param = 401;
    $value1 = $blockchain->EncodeUint8($param);
    $value2 = $blockchain->DecodeUint8($value1);
    if(defined('DEBUG'))
    {
        print "Encoded : ".$blockchain->EncodeUint8(401)."\n";
        print "Decoded : ".$blockchain->DecodeUint8('00000000000000000000000110010001')."\n";
    }
    if($param == $value2) { test_result($test_case , 1); }else{ test_result($test_case , 0); };
}

//Test 2
$test_case = 2;
if (in_array(2, $test)) {
    $param = 'f43fa2f51de02abf2661a0e5a59966048841d20fea304b34580965c4b1e68a49';

    

    $value1 = $blockchain->EncodeString($param);
    $value2 = $blockchain->DecodeString($value1);

    if(defined('DEBUG'))
    {
        print " Test case 2 param : ".$param;
        print " Encoded : ".$value1;
        print " Decoded : ".$value2."<==\n";
        $sim = similar_text($param, trim($value2), $perc);
        print "similarity: $sim ($perc %)\n";
        print "      /// inputdata =>  ".strlen($param);
    }
    //if(strcmp($param, $value2) == 0 ) { test_result($test_case , 1); }else{ test_result($test_case , 0); };
    
        
}
    

//Test 3
//signarure r and s check. TODO: add verification
$test_case = 3;
if (in_array($test_case, $test)) {
    
    //TODO :
    // call ECC_Verify($signature, $document, $pubKey)
    
    //if(strcmp($actual, $expected) == 0) { test_result($test_case, 1); }else{ test_result($test_case, 0); };
}



//Test 4
$test_case = 4;
if (in_array($test_case, $test)) {
    $param = '0x2d0149f21a1581d8EE061f385cC890B84E33ad18';
    $value1 = $blockchain->EncodeAddress($param);
    $value2 = $blockchain->DecodeAddress($value1);
    if(defined('DEBUG'))
    {
        print "Encoded : ".$value1."\n";
        print "Decoded : ".$value2."\n";
    }
    if($param == $value2) { test_result($test_case, 1); }else{ test_result($test_case, 0); };
}

//Test 5
$test_case = 5;
if (in_array($test_case, $test)) {
    $param = '0x2d0149f21a1581d8EE061f385cC890B84E33ad18';
    $value1 = $blockchain->EncodeAddress($param);
    $value2 = $blockchain->DecodeAddress($value1);
    if(defined('DEBUG'))
    {
        print "Encoded : ".$value1."\n";
        print "Decoded : ".$value2."\n";
    }
    if($param == $value2) { test_result($test_case, 1); }else{ test_result($test_case, 0); };
}

//define('DEBUG', true);
//Test 6
// Encode and decode array data
$test_case = 6;
if (in_array($test_case, $test)) {
    $value1 = array();
    $paramArray = array();
    $address = '2d0149f21a1581d8EE061f385cC890B84E33ad18';
    $param = '0x2d0149f21a1581d8EE061f385cC890B84E33ad18';
    $value1 = '0x';
    for ($i = 0; $i < 10; $i++) {
        //$param.= $address;
        $value1  .= $blockchain->EncodeAddress($param);
    }

    //$value2 = $blockchain->DecodeAddressArray($value1);

    if(defined('DEBUG'))
    {
        print "Encoded : ".var_dump($value1)."\n";
        print "Decoded : ".var_dump($value2)."\n";
    }
    if($param == $value2) { test_result($test_case, 1); }else{ test_result($test_case, 0); };
}

//Test 7
//Check Ethereum accounts and validated index 0
$test_case = 7;
if (in_array($test_case, $test)) {
    $account1Index = 0;
    $account1 = $publicAddress;
    $paramsValues = array(); //array("id"=>1);
    
    $response = $blockchain->CallEthereum("eth_accounts", $paramsValues);

    //ar_dump($response);

    if(defined('DEBUG'))
    {
        ar_dump($response);
    }
    if(strcmp(strtoupper($account1), strtoupper($response[$account1Index])) == 0) { test_result($test_case, 1); }else{ test_result($test_case, 0); };
}

//Test 8
//Check Ethereum accounts private key and validated index 0
$test_case = 8;
if (in_array($test_case, $test)) {
    $account1Index = 0;
    $account1 = '0x2d0149f21a1581d8ee061f385cc890b84e33ad18';
    $privateKey = '0xf43fa2f51de02abf2661a0e5a5990f048841d20fea304b34580965c4b1e68a49';

    $out = $blockchain->EncodeString($privateKey);
    $paramsValues = array($out); //array("id"=>1);
    
    $response = $blockchain->CallEthereum("shh_hasIdentity", $paramsValues);

    //var_dump($response);

    if(defined('DEBUG'))
    {
        ar_dump($response);
    }
    //if(strcmp($account1, $response[$account1Index]) == 0) { test_result($test_case, 1); }else{ test_result($test_case, 0); };
}

//Test 9
//Check Ethereum accounts private key and validated index 0
$test_case = 9;
if (in_array($test_case, $test)) {
    $account2 = '0x31aF21349CF7E2a978676C8Aa92A43258fFE299a';

    $paramsValues = array($account2, 'latest'); //array("id"=>1);
    
    $response = $blockchain->CallEthereum("eth_getBalance", $paramsValues);
    
    $balance = $blockchain->DecodeEtheruemDecimal($response);

    $ethBalance = number_format((float)$balance, 0, '.', '');

    if(defined('DEBUG'))
    {
        print "ETH Balance : ".$ethBalance."\n";
    }
    if(strcmp($ethBalance,"100000000000000000000") == 0) { test_result($test_case, 1); }else{ test_result($test_case, 0); };
}

//Test 10
//Test contract method Check Ethereum accounts private key and validated index 0
$test_case = 10;
if (in_array($test_case, $test)) {
    $account2 = '0x2d0149f21a1581d8EE061f385cC890B84E33ad18';

    $paramsTypes = array("uint8","uint8","uint8","uint256","uint256") ;
    $paramsValues = array();

    $paramsValues[] = ($blockchain->EncodeUint8 ($category)) ;              // category
    $paramsValues[] = ($blockchain->EncodeUint8(intVal($groupId)))  ;       // groupe ID
    $paramsValues[] = ($blockchain->EncodeUint8(intval($nodeId)))  ;       // device ID

    $paramsValues[] = ($blockchain->EncodeUint256(substr($signature, 0, 64)))  ;  // r --> (signature parameter 1)
    $paramsValues[] = ($blockchain->EncodeUint256(substr($signature, 65, strlen($signature))))  ;  // s --> (signature parameter 2)


    $params = [
        "from"=> $blockchain->getMyExternalAddress(),
        "to"=> $blockchain->getContractAddress(),
        "gas"=> "0x76c0", // 30400
        "gasPrice"=> "0x9184e72a000", // 10000000000000
        "value"=> "0x9184e72a", // 2441406250
        "data"=>  $blockchain->EncodeFunction($blockchain->EncodeFunctionSelector("SIoTDSC__AddNode(uint8,uint8,uint8,uint256,uint256)"),
                                                                                $paramsTypes,
                                                                                $paramsValues)
    ];
    
    $response = $blockchain->CallEthereum("eth_sendTransaction", $params);
    
    var_dump($response);

    if(defined('DEBUG'))
    {
        print "ETH Balance : ".$ethBalance."\n";
    }
    //if(strcmp($ethBalance,"100000000000000000000") == 0) { test_result($test_case, 1); }else{ test_result($test_case, 0); };
}

//Test 11
//Test contract test method with Ethereum accounts
$test_case = 11;
if (in_array($test_case, $test)) {
    $account2 = '0x2d0149f21a1581d8EE061f385cC890B84E33ad18';

    $paramsTypes = array() ;
    $paramsValues = array();

    $data = $blockchain->EncodeFunction($blockchain->EncodeFunctionSelector("test()"),
                                        $paramsTypes,
                                        $paramsValues);

    //print strlen($data)."\n";


    // $params = array(
    //     "from"=> $blockchain->getMyExternalAddress(),
    //     "to"=> $blockchain->getContractAddress(),
    //     "gas"=> "0x76c0", // 30400
    //     "gasPrice"=> "0x9184e72a000", // 10000000000000
    //     "value"=> "0x9184e72a", // 2441406250
    //     "data" => $data
    // );   
     $fromValue = $blockchain->getMyExternalAddress();
     $toValue = $blockchain->getContractAddress();
     $gasValue = "0x76c0";
     $gasPriceValue = "0x9184e72a000";
     $valuekey = "0x9184e72a";
     $dataValue = $blockchain->EncodeFunction($blockchain->EncodeFunctionSelector("test()"),
                                                    $paramsTypes,
                                                    $paramsValues);

    $data .= "from:{$fromValue},";
    $data .= "to:{$toValue},";
    $data .= "gas:{$gasValue},";
    $data .= "gasPrice:{$gasPriceValue},";
    $data .= "value:{$valuekey},";
    $data .= "data:{$dataValue}";

    // $authentication = base64_encode("{$username}:wrong_password");
    // $headers = ['Authorization' => "Basic {$authentication}"];

    $encoded_data = base64_encode($data);

    $md_data = hash('sha256', $data, false);//64 length


    $accountPrivateKey = '0xf43fa2f51de02abf2661a0e5a5990f048841d20fea304b34580965c4b1e68a49';
    $signatureArray = ECC_sign($md_data, $masterPrivateKey);
    //$signatureArray = ECC_sign($md_data, $accountPrivateKey);

    //ar_dump($signatureArray);

    $signature = $signatureArray['signature']; 
    $t_r = $signatureArray['r']; 
    $t_s = $signatureArray['s']; 

    // $data .= "signature:{$signature},";
    // $data .= "r:{$t_r}";
    // $data .= "s:{$t_s}";

    $signatureArray['data'] = $encoded_data;
    $signed_tx =  base64_encode($data);

    $response = $blockchain->CallEthereum("eth_sign", [$fromValue, $data]);
    
    var_dump($response);

    if(defined('DEBUG'))
    {
        print "ETH Balance : ".$ethBalance."\n";
    }
    //if(strcmp($ethBalance,"100000000000000000000") == 0) { test_result($test_case, 1); }else{ test_result($test_case, 0); };
}


//Test 12
//Created Ethereum accounts with imported private key
$test_case = 12;
if (in_array($test_case, $test)) 
{
    $account = '';
    $paramsValues = array();
   
    $passphrase = "this is for class project";
    $keyPath = __DIR__."//TESTRPC//Ganache//database//mykeystore//testcase//siotd_a//openssl-secp256r1_siotd_a.pem";
    $keyData = file_get_contents($keyPath);

    $keyDataHash = "0x".hash('sha3-256', $keyData, false);//64 length
    $passphraseHash = "0x".hash('sha3-256', $passphrase, false);//64 length 

    if(defined('DEBUG'))
    {
        echo $keyData." len :".strlen($keyData)."\n";
        echo $keyDataHash." len :".strlen($keyDataHash)."\n";
    }

    $fromValue = $blockchain->getMyExternalAddress();
    
    $paramsValues[] = $keyDataHash;
    $paramsValues[] = $passphrase;

    //////////////////////////////////////////////////////////////////////////////////////
    //        API INFORMATION                                                           //
    //        --------------------------------------------------------------------------//
    //        parameters : {"method": "personal_importRawKey", "params": [string, string]}
    //        Internal method : personal.importRawKey(keydata, passphrase)              //
    //        Note : This passed see next test for validation                           //
    //////////////////////////////////////////////////////////////////////////////////////
    $response = $blockchain->CallEthereum("personal_importRawKey", [$keyDataHash, $passphraseHash]);
    //returned the address of new account = 0xa819e3f81dee85eb2abfdc9ae67b3d96d43e5c27
    //$newaccount = "0xa819e3f81dee85eb2abfdc9ae67b3d96d43e5c27";// no need to hash it

    //{"method": "personal_listAccounts", "params": []}
    $response = $blockchain->CallEthereum("personal_listAccounts", []);

    //var_dump($response);

    if(defined('DEBUG'))
    {
        print "personal_importRawKey response : ".$response."\n";
    }

    $pass = false;
    for($i=0; $i < sizeof($response); $i++)
    {
        if(strcmp(strtoupper($response[$i]), strtoupper($newaccount)) == 0 )
        {
            $pass = true;
        }
    }
    
    if($pass){test_result($test_case, 1); }else{ test_result($test_case, 0); };
}

//Test 13
//Lock and unclocked created account from test case 12
$test_case = 13;
if (in_array($test_case, $test)) 
{
    $account = '';
    $paramsValues = array();
   
    $passphrase = "this is for class project";
    $passphraseHash = "0x".hash('sha3-256', $passphrase, false);

    $paramsValues[] = $newaccount;
    $paramsValues[] = $passphraseHash;
    $paramsValues[] = 30;

    $response1 = $blockchain->CallEthereum("personal_unlockAccount", $paramsValues);

    $response2 = $blockchain->CallEthereum("personal_lockAccount", [$newaccount]);


    if(defined('DEBUG'))
    {
        print "personal_unlockAccount : ".$ethBalance."\n";
    }
    if($response1 && $response2) { test_result($test_case, 1); }else{ test_result($test_case, 0); };
}

//Test 14
//unlock, sign and locked created account from test case 12
$test_case = 14;
if (in_array($test_case, $test)) 
{
    $paramsValues = array();
   
    $passphrase = "this is for class project";
    $passphraseHash = "0x".hash('sha3-256', $passphrase, false);


    //data to sign
    $fromValue = $newaccount;
    $toValue = '0x'.$blockchain->getContractAddress();
    $gasValue = "0x76c0";
    $gasPriceValue = "0x9184e72a000";

    // $paramsValues[] = "0x".hash('sha3-256', $category, false);     // category
    // $paramsValues[] = "0x".hash('sha3-256', $groupId,  false);     // groupe ID
    // $paramsValues[] = "0x".hash('sha3-256', $nodeId,   false);     // device ID 
    // $paramsValues[] = "0x".hash('sha3-256', $groupTicket,   false);
    // $dataTypes = "uint8,uint8,uint8,uint256,uint256";
    // $functionData = "SIoTDSC__AddNode(uint8,uint8,uint8,uint256,uint256), {$dataTypes}, {$paramsValues}";

    $dataTypes = "";
    $functionData = "test(), {$dataTypes}, {}";
    
    $data  = "";
    $data .= "from:{$fromValue},";
    $data .= "to:{$toValue},";
    $data .= "gas:{$gasValue},";
    $data .= "gasPrice:{$gasPriceValue},";
    $data .= "value:0x1,";

    

    $keyPath = __DIR__."//TESTRPC//Ganache//database//mykeystore//testcase//siotd_a//openssl-secp256r1_siotd_a.pem";
    $privatekeyData = file_get_contents($keyPath);

    $signedData = ECC_sign($functionData, $privatekeyData);
    
    
    $signed = sign_transaction_data($privatekeyData, $groupId, $nodeId, $groupTicket);
    echo "groupId = ".$groupId." LEN :".strlen($groupId)."\n"; 
    echo "nodeId = ".$nodeId." LEN :".strlen($nodeId)."\n";
    echo "groupTicket = ".$groupTicket." LEN :".strlen($groupTicket)."\n"; 
    echo "Sig = ".$signed['signature']." LEN :".strlen($signed['signature'])."\n";
    echo "R = ".$signed['r']." LEN :".strlen($signed['r'])."\n"; 
    echo "S = ".$signed['s']." LEN :".strlen($signed['s'])."\n";
    echo "R = ".$signed['r_dec']." LEN :".strlen($signed['r_dec'])."\n"; 
    echo "S = ".$signed['s_dec']." LEN :".strlen($signed['s_dec'])."\n";

    //print "Data out : ".$data."\n";


    //unlock account!
    $paramsUnlockValues[] = $newaccount;
    $paramsUnlockValues[] = $passphraseHash;
    $paramsUnlockValues[] = 30;
    $response = $blockchain->CallEthereum("personal_unlockAccount", $paramsUnlockValues);
    if($response)//should return true
    {

        $dataHash = "0x".hash('sha3-256', $functionData, false);
        //$data .= "data:{$dataHash}";
        $data .= "data:{$signedData['signature']}";
        $paramsValues[] = array("{$data}", $passphraseHash);
        //echo "pass : ".$passphraseHash."\n";
        // // personal.sign(message, account, [password])
        // // {"method": "personal_sign", "params": [message, account, password

        //$blockchain->coinbase;
        //$response = $blockchain->CallEthereum("eth_call ", ["{$data}"]); 
        //$response = $blockchain->CallEthereum("eth_sendRawTransaction", ["{$signedData}"]); 
        //$response = $blockchain->CallEthereum("eth_call", ["{$signedData}"]); 
        var_dump($response);
    }

    if(defined('DEBUG'))
    {
        print "eth_sendTransaction : ".$ethBalance."\n";
    }
    if($response) { test_result($test_case, 1); }else{ test_result($test_case, 0); };
}

?>