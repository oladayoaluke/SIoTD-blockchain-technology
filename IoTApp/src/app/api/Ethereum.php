<?php


require __DIR__ . '/vendor/autoload.php';
use Ethereum\Ethereum;
use Ethereum\SmartContract;
use Ethereum\DataType\EthBytes;

use Ethereum\DataType\EthBlockParam;




define("SERVER_URL", "http://localhost:7574");
define("NETWORK_ID", 5777);

try {
	// Connect to Ganache
    $eth = new Ethereum('http://127.0.0.1:7545');
    // Should return Int 63
    echo $eth->eth_protocolVersion()->val();
    //echo "Mining (eth_mining)", $eth->eth_mining()->val();

    //$x = new EthBlockParam('0x3b8ed93a9cbde3cdb39be59f40c03d0893eeb26a');
    //echo var_dump($x);

    //$fileName = "C:\\Apps\\wamp64\\www\\nsu-project\\SIoTD\TESTRPC\\SmartContracts\\contracts\\SIoTDSC.sol";
    $fileName = "C:\\Apps\\wamp64\\www\\nsu-project\\SIoTD\\IoTApp\\src\\app\\api\\ethereum-php\\tests\\TestEthClient\\test_contracts\\build\contracts\\SIoTDSC.json";

    if (!file_exists($fileName)) {
        throw new Exception(
            'You need to compile and deploy the smartcontracts located in TestEthClient/test_contracts/contracts using truffle.'
            . ' (npm -i -g truffle && truffle compile && truffle migrate)'
        );
    }



    $ContractMeta = json_decode(file_get_contents($fileName));
    var_dump($ContractMeta);
    var_dump($ContractMeta->networks->{NETWORK_ID}->address);


    $contract = new SmartContract(
    $ContractMeta->abi,
    //$ContractMeta->networks->{NETWORK_ID}->address,
    $ContractMeta->networks->{NETWORK_ID}->address,
    new Ethereum(SERVER_URL)
    );
    //var_dump($contract);
    $someBytes = new EthBytes(9999999);
    $x = $contract->test();
    echo $x->val();
}
catch (\Exception $exception) {
    die ("Unable to connect.");
}




?>