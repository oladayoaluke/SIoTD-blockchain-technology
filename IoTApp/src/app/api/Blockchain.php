<?php

/* Author : Oladayo Luke
*  School  : Nova Southeastern Univerisity, Davies Florida
*  Program : Computer Science Doctoral Program
*  Course  : ISEC-0740 Secure Systems Analysis and Design
*  Intent  : The purpose of this smart contract is to control simulated
*            IoTD (SIoTD) wihin and from various networks. As a result,
*            will provide security to the simulated IoT devices. It is
			 important to mention that this code was original written is cpp
			 by below publication.
*  References : Hammi, M., Bellot, P & Serhrouchni A (2018). BCTrust: A decentralized authentication
*				blockchain-based mechanism. 2018 IEEE Wireless Communications and Networking Conference (WCNC). IEEE.
                https://github.com/MohamedTaharHAMMI/BubblesOfTrust-BBTrust-
*
*  Documentation : Function parameters Types : https://solidity.readthedocs.io/en/v0.5.3/abi-spec.html
*				   RSA signature verification : 
*                   JSON-RPC : https://github.com/datto/php-json-rpc-http
*/

require __DIR__ . '/vendor/autoload.php';


use Datto\JsonRpc\Http\Client;
use Datto\JsonRpc\Http\Exceptions\HttpException;
use Datto\JsonRpc\Responses\ErrorResponse;
use Datto\JsonRpc\Responses\ErrorException;

define ("CONTRACT_PATH", "//ethereum-php//tests//TestEthClient//test_contracts//contracts//SIoTDSC.sol");
define ("CONTRACT_DATA", "//TESTRPC//SmartContracts//build//contractsSIoTDSC.json");


    
define ("_32Bytes",       64);
define ("_AddrTypeLen",   (20 * 2));
define ("_AddrPaddLen",   (_32Bytes - _AddrTypeLen));

class Blockchain 
{

    /**
     * @var members
     */

    private $ipAddress         = "";
    private $port              = "";
    public $coinbase          = "";
    private $contractAddress   = "";
    private $myExternalAddress = "";
    private $ContractMeta      = "";
    private $client;//  = Client::factory("http://127.0.0.1:80", ['rpc_error'=>true]);


    /**
     * Blockchain constructor.
     * @param string ip address
     * @param string port
     */
    public function __construct(string $_ipAddress, string $_port)
    {
        $this->ipAddress = $_ipAddress;
        $this->port = $_port;

        // Create the client with the `rpc_json`
        $host = "http://".$_ipAddress.":". $_port;
        print "Blochchain is Connecting to ".$host."\n";

        $this->client = new Client($host);

        // Send the request
        try {
            $this->coinbase = $this->getCoinbaseFromTheBlockChain() ;
            if(strlen($this->coinbase) == 0) {
                if (defined('DEBUG'))
                    echo "Error:Blockchain::Blockchain, getCoinbaseFromTheBlockChain call failed\n" ;
                return ;
            }

            // Create a request
            //$request = $client->request(123, 'method', ['key'=>'value']);
            //$client->send($request);
        } catch (RequestException $e) {
            die($e->getResponse()->getRpcErrorMessage());
        }

        //TODO: FIX PATH
        // if (!file_exists(CONTRACT_DATA)) {
        //     throw new Exception(
        //         'You need to compile and deploy the smartcontracts located in TestEthClient/test_contracts/contracts using truffle.'
        //         . ' (npm -i -g truffle && truffle compile && truffle migrate)'
        //     );
        // }

        // $this->ContractMeta = json_decode(file_get_contents(CONTRACT_DATA));
    
    
    
        

    }

    /**
     * Blockchain destructor.
     * @param array $key
     */
    function __destruct() {
        //echo '  Called Blockchain Destructor!';
    }
    
    
    /**
     * 
     * @return  array 
     */
    public function getCoinbaseFromTheBlockChain ()
    {

        $this->client->query('eth_coinbase', [], $result);

        try {
            $this->client->send();
            return $result;
        } catch (HttpException $exception) {
            $response = $exception->getResponse();
            $code = $response->getCode();
            $message = $response->getMessage();
        }

        return array();
    }

    /**
     * 
     * @return  sarray
     */
    public function CallEthereum(string $eth_method, array $paramsValues)
    {
        $result = array();
        $this->client->query($eth_method, $paramsValues, $result);

        try {
            $this->client->send();
            return $result;
        } catch (HttpException $exception) {
            $response = $exception->getResponse();
            $code = $response->getCode();
            $message = $response->getMessage();
        }

        return array();
    }
    
    
    /**
     * 
     * @return  string 
     */
    public function getCoinbase()
    {
        return $this->coinbase ;
    }
    
    /**
     * 
     * @return  string 
     */    
    public function getContractAddress ()
    {
        return $this->contractAddress ;
    }
    
    /**
     * 
     *  
     */
    public function setContractAddress (string $_contractAddress)
    {
        $this->contractAddress = $_contractAddress ;
    }
    
    /**
     * 
     * @return  string 
     */
    public function getMyExternalAddress ()
    {
        return $this->myExternalAddress ;
    }

    /**
     * 
     * 
     */
    public function setMyExternalAddress (string $_myExternalAddress)
    {
        $this->myExternalAddress = $_myExternalAddress ;
    }
    
    /**
     * @param $values string
     *
     * @return response string 
     * @throws \Exception
     */

     /*
    public function compilesmartContract()
    {
        $reqest_data = array() ;

        $content = file_get_contents (CONTRACT_PATH) ;
        var_dump($http_response_header);
        if( !empty($content) )
        {
            $reqest_data[] = json_decode($content);

            if ( json_last_error() !== JSON_ERROR_NONE ) 
            {
                print (" JSON incorrect data");
            }
        }

        
        
        $this->client->query('eth_compileSolidity', $reqest_data, $result);

        try {
            $this->client->send();
            $this->contractData = $result['code'];
        } catch (HttpException $exception) {
            $response = $exception->getResponse();
            $code = $response->getCode();
            $message = $response->getMessage();
        }
    

        $reqest_data = array("from"=> coinbase, "data" => contractData, ) ;
        $this->client->query('eth_compileSolidity', $reqest_data, $result);

        try {
            $this->client->send();
            $this->contractGasEstimate = $result;
        } catch (HttpException $exception) {
            $response = $exception->getResponse();
            $code = $response->getCode();
            $message = $response->getMessage();
        }


        $reqest_data = array("from"=> coinbase, "gas" => contractGasEstimate, "data" => contractData, ) ;
        $this->client->query('eth_sendTransaction', $reqest_data, $result);

        $transactionHash = "";
        try {
            $this->client->send();
            $transactionHash = $result;
        } catch (HttpException $exception) {
            $response = $exception->getResponse();
            $code = $response->getCode();
            $message = $response->getMessage();
        }

    

        
        $reqest_data = array($transactionHash) ;
        $this->client->query('eth_getTransactionReceipt', $reqest_data, $result);

        try {
            $this->client->send();
            $this->contractAddress =  $result["contractAddress"] ;
        } catch (HttpException $exception) {
            $response = $exception->getResponse();
            $code = $response->getCode();
            $message = $response->getMessage();
            print "Error : ".$message;
        }
    }
*/

    /**
     * @param $values string
     *
     * @return response string 
     * @throws \Exception
     */
    public function CallFunction (string $from,
                                    string $to,
                                    string $data,
                                    string $eth_methodName)
    {



        $this->client->query( $eth_methodName,   ['from'=>$from,
                                                'to'=>$to,
                                                'data'=>$data], $result);

        try {
            $this->client->send();
            return $result;
        } catch (HttpException $exception) {
            $response = $exception->getResponse();
            $code = $response->getCode();
            $message = $response->getMessage();
            echo  "Exception:Blockchain::CallFunction,".($message);
        }


    
        return "";
    }
    
     /**
     * @param $functionSelector string
     *
     * @return  string 
     */   
    public function EncodeFunctionSelector(string $functionSelector)
    {
        $v = utf8_encode($functionSelector);// using this append the Unicode functionName is converted into 8-bit characters using QString::toAscii()
        
        $md = hash('sha256', $v, false);//64 length

        //$hashToHex = bin2hex($md) ; // 128 length

        return $md;
    }
    
    /**
     * @param $IsDynamicType string
     *
     * @return  bool 
     */     
    public function IsDynamicType(string $type) {
        if (substr($type, strlen($type)-1, strlen($type)) == ']')
            return true ;
        if      ( gettype($type) == "string") return true ;
    
        return false ;
    }
    
    /**
     * @param $functionSelector string
     * @param $paramsType array
     * @param $paramsValues array
     *
     * @return  bool 
     */    
    public function EncodeFunction(string $functionSelector, array $paramsTypes, array $paramsValues)
    {
        $value   = "";
        $dP      = 0   ;
    
        if (empty($paramsTypes)) {
            $value = "0x".$functionSelector ;
            $padding = str_repeat('0',  _32Bytes);
    
            return $value.$padding ;
        }

    
        $dataPosList = array(); // for the dynamic types
    
        if (sizeof($paramsTypes) != sizeof($paramsValues)) 
        {
            echo "Error: ExecuteFunction, paramsTypes.length()".sizeof($paramsTypes)."!= paramsValues.length()".sizeof($paramsValues) ;
            exit (0) ; // todo change it by another one
        }
    
        $value = "0x".$functionSelector ;
    
        for ($i = 0; $i < sizeof($paramsTypes); $i++) {
            if ($this->IsDynamicType($paramsTypes[$i])) {
                $dP += 32 * sizeof($paramsTypes) ;
    
                for ($j = 0; $j < $i; $j++) 
                {
                    if ($this->IsDynamicType($paramsTypes[$j])) {
                        $dynamicLen = $this->DecodeUint64(substr($paramsValues[$j], 0, _32Bytes) ) ;
                        $dP += 32 + $dynamicLen + (32 - ($dynamicLen % 32)) ; // 32 for the dynamicTypeLen of prec dynamicType + ...
                    }
                }
                $dataPosList[] = $dP ;

                $dP = 0 ;
            }
        }
    
        $dP = 0 ;
        $data = '';
        for ($i = 0; $i < sizeof($paramsTypes); $i++) 
        {
            if ($this->IsDynamicType($paramsTypes[$i])) 
            {
                $data = $data.$this->EncodeUint64($dataPosList[$dP]);
                $dP++;
            } else {
                $data = $data.$paramsValues[$i];
               
            }
        }
        $value = $data  ;
    
        for ($i = 0; $i < sizeof($paramsTypes); $i++) 
        {
            if ($this->IsDynamicType($paramsTypes[$i]))
            {
                $data = $data.$paramsValues[$i];
            }
                
        }
        $value = $data  ;
    
        return $value ;
    }
    
    /**
     * @param $values string
     *
     * @return EncodeUint8 string 
     * @throws \Exception
     */
    public function EncodeUint8(int $value)
    {

        $v = dechex($value) ; // we should convert the DEC to HEX
        $v = base_convert($v, 16, 2);//convert to bases 16
        
        $paddingLen = _32Bytes - (strlen($v) % _32Bytes);
        $padding="";
        if($paddingLen > 0)
        {
            $padding = str_repeat('0', $paddingLen);
        }    
    
        return $padding.$v;

    }

    /**
     * @param $values string
     *
     * @return decimal
     */
    public function DecodeUint8(string $value)
    {
        return $decodedValue = bindec($value) ; // we should convert the DEC to HEX

    }

    /**
     * @param $values int
     *
     * @return string
     */
    public function EncodeUint64(int $value64)
    {
        $v = dechex($value64) ; // we should convert the DEC to HEX
        $v = base_convert($v, 16, 2);//convert to bases 16
        
        $paddingLen = _32Bytes - (strlen($v)  % _32Bytes);
        $padding = str_repeat('0', $paddingLen);

        return $padding.$v;
    }

    /**
     * @param $values string
     *
     * @return decimal
     */
    public function DecodeUint64(string $value64)
    {
        $decodedValue = bindec($value64);
        if (defined('DEBUG'))
            echo '====<>'.$decodedValue;
        return $decodedValue; // we should convert the DEC to HEX

    }

    /**
     * @param $values int
     *
     * @return string
     */
    public function EncodeInt64(int $value)
    {
    /*uint<M>: enc(X) is the big-endian encoding of X, padded on the higher-order (left) side with zero-bytes such that the length is a multiple of 32 bytes*/
        $paddingValue = '0' ;
        if ($value < 0) {
            $value *= -1 ;
            $paddingValue = 'f' ;
        }
        $v =  (dechex(strval($value))) ;
        $paddingLen = _32Bytes - (strlen($v2) % _32Bytes);
        $padding = str_repeat($paddingValue, $paddingLen);
    
        return $padding.$v ;
    }

    /**
     * @param $value string
     *
     * @return decimal
     */
    public function DecodeInt64(string $value)
    {
        $right_16_bytes = substr($value, -16) ;
        $decodedValue = bindec($right_16_bytes) ;
        if ($decodedValue < 0)
            if (defined('DEBUG'))
                echo "Error, DecodeInt64, Conversion failed!";
    
        return $decodedValue;
    }

    /**
     * @param $value bool
     *
     * @return strinf
     */
    public function EncodeBoolean(bool $value)
    {
    /*uint<M>: enc(X) is the big-endian encoding of X, padded on the higher-order (left) side with zero-bytes such that the length is a multiple of 32 bytes*/
        if ($value == false)
            return "0000000000000000000000000000000000000000000000000000000000000000" ;
        else
            return "0000000000000000000000000000000000000000000000000000000000000001" ;
    }

    /**
     * @param $values string
     *
     * @return bool
     */
    public function DecodeBoolean(string $value)
    {
        $v  = -1 ;
        $v = bindec($value) ;
        if ($v < 0)
            if (defined('DEBUG'))
                echo "Error: DecodeChunkString, Conversion failed!";
    
        return boolval(v) ;
    }

    /**
     * @param $value string
     *
     * @return string
     */
    public function EncodeString(string $value)
    {
        // dynamic sized unicode string assumed to be UTF-8 encoded
        // Note that the length used in this subsequent encoding is the number of bytes of the utf-8 encoded string, not its number of characters
        // len(a) is the number of bytes in a binary string a. The type of len(a) is assumed to be uint256

        $v = bin2hex(utf8_encode($value));
        
        $paddingLen = _32Bytes - (strlen($v) % _32Bytes);
        $rightPadding = "";
        if($paddingLen > 0)
        {
            $rightPadding = str_repeat('0', $paddingLen);
        }    


        $out = $this->EncodeUint64(strval(strlen($value)/2)).$v.$rightPadding;
        
        return $out;
    }

    /**
     * @param $value string
     *
     * @return string
     */
    public function EncodeAddress(string $value)
    {
    // This is just the right representation (we does not require an encode operation)
        $paddingValue = "" ;
        $encodedValue = "" ;
        
        if (substr($value, 0, 2) == "0x")
            $encodedValue = substr($value, 2, strlen($value)) ;
        else
            $encodedValue = $value ;
    
        if (strlen($encodedValue) != _AddrTypeLen) {
            if (defined('DEBUG'))
                echo  "Error:Blockchain::EncodeAddress, the address is not correct (length should be equal to 20 bytes".strlen($encodedValue)." != "._AddrTypeLen ;
            $encodedValue = "" ;
            return $encodedValue ;
        }
    
        $paddingValue = str_repeat('0', _AddrPaddLen) ;// .fill('0', _AddrPaddLen) ;
        $encodedValue = $paddingValue.substr($encodedValue, -1*_AddrTypeLen);

        return $encodedValue ;
    }


       /**
     * @param $values string
     *
     * @return decimal
     */
    public function DecodeEtheruemDecimal(string $value)
    {
       return hexdec($value);

    }


    /**
     * @param $value string
     *
     * @return string
     */  
    public function DecodeAddress(string $value)
    {
    // This is just the right representation (we does not require an encode operation)
        return "0x".substr($value, -1*_AddrTypeLen) ;
    }

    /**
     * @param $value string
     *
     * @return array
     */  
    public function  DecodeAddressArray(string $value)
    {
    // T[] where X has k elements (k is assumed to be of type uint256)
    // enc(X) = enc(k) enc([X[1], ..., X[k]])
    
        $list = array();
        $dataPos   = 0 ;
        $eltNumber = 0 ;
        $addr      = "";
    
        $dataPos = ($this->DecodeUint64(substr($value, 0, 2 + _32Bytes)) * 2) + 2;  // +2 for "0x", *2 because 1Byte represented by 2Chars
        $eltNumber = $this->DecodeUint64(substr($value, $dataPos, $dataPos+_32Bytes)) ; // mid(position, bytesNumber)  
        $dataPos   += _32Bytes ;
        if (strlen(substr($value, $dataPos, strlen($value))) != (_32Bytes * $eltNumber)) {
            if (defined('DEBUG'))
                echo "Error:Blockchain::DecodeAddressArray the received adresses length is not correct" ;
            return array() ;
        }
    
        for ($i = 0; $i < $eltNumber; $i++) {
            $addr = substr($value, dataPos, dataPos+_32Bytes) ;
            $list[] = $this->DecodeAddress(addr) ;
            $dataPos += _32Bytes ;
        }
    
        return $list ;
    
    }

    /**
     * @param $values string
     *
     * @return string
     */
    private function DecodeChunkString(string $value)
    {
        $ok = true ;
        $decodedValue = 0 ;

        $decodedValue = strlen($value); //value.toULongLong(&ok, 16) ;
        //echo ' $decodedValue == > '. $decodedValue;

        if ($decodedValue <= 0)
            if (defined('DEBUG'))
                echo "Error: DecodeChunkString, Conversion failed!";

        $utf8_decode =  utf8_decode($value)."\n\n";

        $decodedStr = hex2bin($value);
        
        $utf8_decode =  utf8_decode($decodedStr)."\n\n";
        //echo ' $UT decodedStr == > '. $utf8_decode."\n";

        //echo ' $decodedStr == > '. $decodedStr."\n";

        return $utf8_decode ;
    }

    /**
     * @param $values string
     *
     * @return string
     */
    public function DecodeString(string $value)
    {
        $dataPos     = 0  ;
        $valueLen    = 0  ;
        $v           = "" ;
        $vRes        = "" ;
        $decodedStr  = "" ;
        $chunkNumber = 0  ;
        $chunkLen    = 0  ;

        $str_length = strlen($value)/2;
        $left_value  = substr($value, 0, $str_length);
        
        $left_value_minus_0x = substr($left_value, 2, strlen($left_value));
        $dataPos = strlen($left_value_minus_0x); //DecodeUint64(substr($left_value, 2, strlen($left_value))) ;  // 0x = 2 characters
        if ($dataPos <= 0) {
            if (defined('DEBUG'))
                echo "Error: DecodeString, dataPos <= 0 " ;
            return "";
        }
        $right_value = substr($value, -$str_length); 
        if (defined('DEBUG'))
        {
            echo ' r===> '.strlen($right_value);
            echo ' l===> '.strlen($left_value);
            echo ' pos===> '.strlen($left_value_minus_0x);
        }

        $v = $right_value ;// we dont need to shift in this case comp. to cpp

        $valueLen = strlen($value); //DecodeUint64($v) * 2 ;      // Byte = 2 characters
        if ($valueLen <= 0) {
            if (defined('DEBUG'))
                echo "Error: DecodeString, valueLen <= 0 " ;
            return "";
        }

        $vRes =  $left_value_minus_0x.$right_value ;                   // dataPos*2 -> because we talk bytes // todo to check this info

        // 8 bytes

        $chunkNumber += ($valueLen / 8) ;
        $chunkNumber += ($valueLen % 8) ? 1 : 0 ;
        $chunkLen    = 0 ;

        if (defined('DEBUG'))
        {
            echo "   ////\  ";
            echo strlen($value);
            echo "   ////\  ";
        }

        for ($i = 0; $i < $chunkNumber; $i++) 
        {
            $chunkLen   = ($valueLen > 8) ? 8 : $valueLen ;
            $valueLen   -= $chunkLen ;
            $vRes_len = strlen($vRes)/2;
            $vRes_mid = substr($vRes, -$vRes_len); 
            //echo strval($i * 8);
            // echo "   //  ";
            // echo $valueLen;
            
            // echo "   //  ";
            // echo strlen($value);
            //$decodedStr += DecodeChunkString(substr($vRes_mid, $i*8, $chunkLen)) ;
            $data = $this->DecodeChunkString(substr($vRes, $i*8, $chunkLen)) ;
            $decodedStr = $decodedStr.$data;
            $chunkLen   = 0 ;
        }

        if ($valueLen != $chunkLen) {
            if (defined('DEBUG'))
                echo "Error: DecodeString, valueLen(".$valueLen.") != chunkLen(".$chunkLen.")" ;
            return "";
        }

        return $decodedStr ;
    }

    /**
     * @param $values array of int
     * @param $values length int
     *
     * @return string
     */
    public function EncodeUint8Array (array $value, int $valueLen)
    {

        $encValue = "" ;

        if ($value == NULL) {
            echo "Error:Blockchain::EncodeStatTab (value == NULL)" ;
            return "" ;
        }

        if ($valueLen <= 0) {
            echo "Error:Blockchain::EncodeStatTab (valueLen <= 0)" ;
            return "" ;
        }

        $encValue = $this->EncodeInt64($valueLen) ;//todo debug
        $data = '';
        for ($i =0 ; $i < $valueLen; $i++) 
        {
            $data = $data.($this->EncodeUint8(value[i])) ;
        }
        $encValue = $data;

        return encValue ;
    }

    /**
     * @param $values string
     *
     * @return string
     */
    public function EncodeUint256(string $value)
    {
        if (strlen($value) != 64)  // (256x2)/8
        {
            if (defined('DEBUG'))
                echo "Blockchain::EncodeUint256, strlen(value) != 64" ;
            return NULL;
        }
        return $value;
    }

    function generate_account($keyData, $passphraseHash)
    {
        $paramsValues = array();

        $keyDataHash = "0x".hash('sha3-256', $keyData, false);//64 length
        $passphraseHash = "0x".hash('sha3-256', $passphrase, false);//64 length 
    
        if(defined('DEBUG'))
        {
            echo $keyData." len :".strlen($keyData)."\n";
            echo $keyDataHash." len :".strlen($keyDataHash)."\n";
        }
    
        $fromValue = $this->getMyExternalAddress();
        
        $paramsValues[] = $keyDataHash;
        $paramsValues[] = $passphrase;
    
        //////////////////////////////////////////////////////////////////////////////////////
        //        API INFORMATION                                                           //
        //        --------------------------------------------------------------------------//
        //        parameters : {"method": "personal_importRawKey", "params": [string, string]}
        //        Internal method : personal.importRawKey(keydata, passphrase)              //
        //        Note : This passed see next test for validation                           //
        //////////////////////////////////////////////////////////////////////////////////////
        $newaccount = $blockchain->CallEthereum("personal_importRawKey", [$keyDataHash, $passphraseHash]);

        $paramsValues[] = $newaccount;
        $paramsValues[] = $passphraseHash;
        $paramsValues[] = 30;

        $response1 = $blockchain->CallEthereum("personal_unlockAccount", $paramsValues);


        return $newaccount;
    }

    /**
     * Checks if the given string is an address
     *
     * @method isAddress
     * @param {String} $address the given HEX adress
     * @return {Boolean}
    */
    // function isAddress($address) {
    //     if (!preg_match('/^(0x)?[0-9a-f]{40}$/i',$address)) {
    //         // check if it has the basic requirements of an address
    //         return false;
    //     } elseif (!preg_match('/^(0x)?[0-9a-f]{40}$/',$address) || preg_match('/^(0x)?[0-9A-F]{40}$/',$address)) {
    //         // If it's all small caps or all all caps, return true
    //         return true;
    //     } else {
    //         // Otherwise check each case
    //         return isChecksumAddress($address);
    //     }
    // }

    /**
     * Checks if the given string is a checksummed address
     *
     * @method isChecksumAddress
     * @param {String} $address the given HEX adress
     * @return {Boolean}
    */
    // function isChecksumAddress($address) {
    //     // Check each case
    //     $address = str_replace('0x','',$address);
    //     $addressHash = hash('sha3',strtolower($address));
    //     $addressArray=str_split($address);
    //     $addressHashArray=str_split($addressHash);

    //     for($i = 0; $i < 40; $i++ ) {
    //         // the nth letter should be uppercase if the nth digit of casemap is 1
    //         if ((intval($addressHashArray[$i], 16) > 7 && strtoupper($addressArray[$i]) !== $addressArray[$i]) || (intval($addressHashArray[$i], 16) <= 7 && strtolower($addressArray[$i]) !== $addressArray[$i])) {
    //             return false;
    //         }
    //     }
    //     return true;
    // }


}//endofclass

?>