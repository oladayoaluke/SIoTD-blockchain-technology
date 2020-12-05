<?php

require __DIR__ . "/vendor/autoload.php";

use Mdanter\Ecc\EccFactory;
use Mdanter\Ecc\Serializer\PrivateKey\PemPrivateKeySerializer;
use Mdanter\Ecc\Serializer\PrivateKey\DerPrivateKeySerializer;

use Mdanter\Ecc\Serializer\PublicKey\PemPublicKeySerializer;
use Mdanter\Ecc\Serializer\PublicKey\DerPublicKeySerializer;

use Mdanter\Ecc\Serializer\Point\UncompressedPointSerializer;

$isPrivate = true;
$isPublic = true;


$iswrite = true;
$outputPath = __DIR__."//TESTRPC//Ganache//database//mykeystore//";


generate_ecc_key_pair();



$device  = array("master","siotd_a", "siotd_b", "siotd_c", "siotd_d");
for($i=0; $i < sizeof($device); $i++)
{

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


    ///////////////////////////////////////////////////////////////////////////////////
    //                             WRITE T0 FILE                                     //
    ///////////////////////////////////////////////////////////////////////////////////
    if(is_dir($outputPath) && $iswrite)
    {
        mkdir($outputPath.$device[$i]);
    
        $filename1 = $outputPath.$device[$i]."//openssl-secp256r1_pri_".$device[$i].".pem";
        $prifile = fopen($filename1, "w") or die("Unable to open file!");

        $filename2 = $outputPath.$device[$i]."//openssl-secp256r1_pub_".$device[$i].".pem";
        $pubfile = fopen($filename2, "w") or die("Unable to open file!");
    
        if($isPrivate)
        {
            fwrite($prifile, $priKey);
        }
        if($isPublic)
        {
            fwrite($pubfile, $pubkey);
        }
    
        fclose($prifile);
        fclose($pubfile);
    }
    else
    {
        echo __DIR__;
        die("Unable to find directory! ". $outputPath);
    }

}

