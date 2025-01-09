```bash
AUTHOR : OLADAYO LUKE
PUBLICATION DATE: 12/5/2020

#SCRIPT ARGUMENTS
[ARG 1] GROUP NAME      : "A"  #For example, A, B or C
[ARG 2] FROM NODE ID    : "1"  #For example, 1, 2,3, or 4
[ARG 3] TO NODE ID      : "2"  #For example, 2, or 2,3,4 for multiple recipients
[ARG 4] CATEGORY ID     : "1"  #For example, 1
[ARG 5] MASTER FLAG     : "0"  #For example, 0 for slave 1 for master

#Terms : 
		SIoTD : Simulated Internet of Things Device
		SIoTDSAS : SIoTD Simulated Attack Script
		API : Application Programming Interface
		App/APP : Application(or mobile application)
		
PROJECT SCREENSHOTS 
Go to SIoTD-blockchain-technology/Documentation
		
#Independencies
1. Wamp Server 3 with PHP 7.1(Please, ensure php is added to environment variable and it could be convinient to install to C:\Apps\)
	I am including all project modules to decrease the number of dependencies needed to be installed.
	In the php api folder is a composer.json file that contain the dependencies for php. However,
	these dependencies will be shipped with the software as result, no need to reinstall them.
	Important:
			Run this project in www directory of wampserver because the php api needs a server to run on.
			Alternative, will be to deploy the api to host server account which is beyond the scope of this project.
2. Python 3.5+
3. Ionic 4 and it dependences (npm version 6.11 and nodesjs version 10.16). Please use the following commands
	Step 1 : npm install
	Step 2 : ionic 4 command : npm install -g ionic@4
	Step 3 : nodejs : It is better to use installer at https://nodejs.org/en/download/
	
	Step 4 : Cd to APP root directory where there is packake.json, run "npm install" to install all dependences: https://docs.npmjs.com/cli/install
			 For me, App root is at C:\Apps\wamp64\www\project\SIoTD\IoTApp
	Step 5 : cd C:\Apps\wamp64\www\project\SIoTD\IoTApp\src\app\api where there is a composer.json, run "composer install"
			 to install all php dependences.
	Step 6 : Cd to C:\Apps\wamp64\www\project\SIoTD\IoTApp\src\app\api\TESTRPC and run "npm install" to install blockchain dependencies.
	Step 7 : Cd to C:\Apps\wamp64\www\project\SIoTD\IoTApp\src\app\api\TESTRPC\SmartContracts and run "composer install" to install truffle dependencies
	
	Step 8 : Install Ganache into C:\Apps\wamp64\www\project\SIoTD\IoTApp\src\app\api\TESTRPC
	
	
	Important Note : Step 4, 5, 6, 7 and 8 are important incase module packages becomes too large to upload. 
	
	Reseources:
		https://www.youtube.com/watch?v=S0EecZTQygg
		https://ionicframework.com/docs/intro/cli
	 
3b  Blockchain
	npm install -g truffle@4 (Truffle v4.1.17) : https://www.trufflesuite.com/docs/truffle/getting-started/installation
	npm install solc (Solidity v0.4.26)
	
	Resources : Visual Studio IDE : https://davidburela.wordpress.com/2016/11/18/configuring-visual-studio-code-for-ethereum-blockchain-development/
				Truffle and Visual Studio : https://www.trufflesuite.com/tutorials/how-to-install-truffle-and-testrpc-on-windows-for-blockchain-development
				Truffle Configuration : https://www.trufflesuite.com/docs/truffle/reference/configuration

4. To enable SSL for Ionic application: 
	1. please change http to https in C:\Apps\wamp64\www\project\SIoTD\IoTApp\src\app\custom.service
	2. Generate certificate and Install openssl on your local machine, otherwise, run with non secure connection(http)
		The steps are as follows:
								Step 1: Download and Install WampServer. ...
								Step 2: Download and Install OpenSSL. ...
								Step 3: Create Your Key and Certificate. ...
								Step 4: Move Your Key and Certificate. ...
								Step 5: Edit Your httpd.conf File. ...
								Step 6: Edit Your httpd-ssl.
	    In addition, i am attaching a tutorial link on how to install openssl on wampserver server. I hope it helps!
		https://zuziko.com/tutorials/how-to-enable-https-ssl-on-wamp-server/
		
5.  Script:
		The siotd.py sumulated IoT device and it contain a led that can be switched on and off. The default state of led is HIGH
		The the script executes, the device host and port is automatically configured to a unique port number start at 65430.
		Since node is must be using, the script basically add node id to 65430 and use it to create a socket connection. The socket
		connect enable mobile application to send configuration commands to the SIoTD to configure for blockchain communication. An
		example configuration parameters are the created blockchain account, group number, group ticket, node id etc. In the root
		directory of the siotd.py, it exist of the simulation configuration file. The configuration contain several parameters to 
		unique configuration the SIoT devices. The configuration file name is config.data. I stored the SIoTD and blockchain server(Ganache-CLI) gets
		host name, port, network id, contract address,group ticket, api key etc in the config.data to allow preconfiguration of the simulation. Other 
		parameters in the config.data file are database parameters, number of nodes, number of active nodes, registration status, reset flags for each 
		simuation device etc. 
		
		The siodsas.py simulated attack script exist in same directory as siotd.py (For me : C:\Apps\wamp64\www\project\SIoTD)
		The script basically scans port address for socket connections, connects to it and attempt to guess the socket connection token 
		in order to send rogue data to devices. This script can find vulnerability in the devices through socket connection. Example of
		vulnerability is altering configuration parameters. The countermeasure for this was to make configuration data alteration one attempt 
		will only be successfully if an attacker can guess all the configuration parameters at once. Since devices listens to control commands are
		from blockchain only, it seem impossible for attack script to take over any of the SIoTDs. 

5b Connecting SIoTD to siotd.py:
		Change the port address in config.data to 65429, start up the script with "A" "1" "2" "1" "0", start up the app using 
		ionic cordova run browser --livereload  --target=chrome --address  localhost --port 8000
		It should connec to the SIoTD. The app socket targets 65430. When the SIoTD starts, since node id is equal to 1, it starts with poer = 65429 + 1
		After configuring the SIoTD, change port number back to  65430 before performing further simulation. App to SIoT is not fully implement by atleast
		there is communication between App and SIoT and App can perform node data generation such as generating ECC assymetric encryption key pair, 
		Ethereum account create using the generated ECC private key, custom node id creation using MYSQL database incremental table primary key, group
		ticket generation, group id generation, ECC private key passphrase generation and other useful IoT device management parameters.
		
6. Building 
		Ionic 4 Application and viewing on browser : ionic cordova build browser
		Ionic 4 application for andriod : ionic cordova build andriod

7  Running Ionic 4 command : 
						HTTPS : ionic cordova run browser --livereload --ssl --target=chrome --address  localhost --port 8100
						HTTP  : ionic cordova run browser --livereload  --target=chrome --address  localhost --port 8000
						
8  Using Ionic App : 
	    1. Install Wampserver 3.0(Comes with PHP 5.6, 7.0 and 7.1)
		2. Turn on the server by double clicking on the icon
		3. After all services started and icon is green from being yellow or red,
		4. Click on the service icon on task tab to select -> version - 7.1
		5. In the browser, go to http:localhost, select myphpadmin, create a database "nsu_iot_app", import C:\Apps\wamp64\www\project\SIoTD\IoTApp\database\nsu_iot_app.sql only!
			Webserver MYSQL and myphpadmin comes with no password. If you installed with a password and or your username is not root, configure db.php to match your credentials.
			db.php can be found at C:\Apps\wamp64\www\project\SIoTD\IoTApp\src\app\api\*
		5. After bulding the app using "ionic cordova build browser" command, run it with ionic cordova run browser --livereload  --target=chrome --address  localhost --port 8000
			the app should automatically starts in browswer.
		6. Use the following credentials to login into the mobile app:
			 Username : demo1
			 Password : demo		
```

    
# How Blockchain Works!

## 1. Ganache-CLI: Local or Private Blockchain
Ganache-CLI is used to run a local or private blockchain on port 7545. Use the following command to start the server:

```bash
ganache-cli -p 7545 --mnemonic "this is for class project" --networkId 5777 --db ./database --secure -u 0 -u 1 -u 2 -u 3 -u 4 -u 5 -u 6 -u 7 -u 8 -u 9
```
### Key Details:
- Port (-p): Specifies the port number.
- Mnemonic (--mnemonic): A passphrase used to connect to Ganache. Truffle uses this to interact with Ganache-CLI.
- Truffle Framework: Deploy smart contracts to Ganache using Truffle. Ensure the path to Ganache's root directory is included in the system's environment variables.
- 
The Truffle configuration file can be found here:
```bash
C:\Apps\wamp64\www\project\SIoTD\IoTApp\src\app\api\TESTRPC\SmartContracts\truffle-config.js
```

## 2. Smart Contracts: Ethereum Blockchain Core
Smart contracts are self-executing code written in programming languages like Solidity. They:

- Are compiled into bytecode.
- Deployed to the blockchain network with an address for interaction.
- Offer advanced features like modifiers to execute preconditions before a function runs (e.g., requiring a condition to be true before accessing a payable function).
## Development Steps:
1. Write and compile the smart contract.
2. Verify and convert it to bytecode.
3. Deploy using:
- ABI (Application Binary Interface): JSON structures detailing data, function signatures, and return values.
- Bytecode and Smart Contract Address.
## Recommended Tools:
- Visual Studio Code IDE: Setup Guide
- Remix IDE: Browser-based Solidity IDE.
## Useful Resources:
- Truffle and Visual Studio Integration
- Truffle Configuration
- Understanding ABI
- Signing Contract Transactions
- Smart Contract Structure
- Examples of Solidity
## Deploying a Smart Contract:
1. Navigate to the root directory:
```bash
cd C:\Apps\wamp64\www\project\SIoTD\IoTApp\src\app\api\TESTRPC\SmartContracts
```
2. Run the deployment command:
```bash
truffle migrate 2 > ../../../../../../contract.data
```
3. Return to the project directory and start the application:
```bash
cd C:\Apps\wamp64\www\project\SIoTD
python siotd.py
```
## 3. Experimentation with Blockchain
During experimentation, Ethereum's Web3 Python library proved most effective. Here are key observations:
- Python Web3 Library: Successfully executed commands and transactions with Ganache-CLI.
- PHP Integration Challenges:
- - While basic connections like fetching the version number worked, executing advanced commands (e.g., send_transaction) failed.
- - Switching to pure JSON-RPC required creating custom libraries, which proved time-intensive.
## PHP Experimentation:
- Limited success was achieved with encoding function arguments for the smart contract ABI.
- Commands like account creation, locking, and unlocking were functional but limited.
- PHP's JSON-RPC potential requires further exploration, particularly for commands like sendRawTransaction.
## Summary:
For effective blockchain development:
- Python Web3 is recommended for robust functionality.
- PHP integration shows promise but requires significant time investment for advanced functionality.
  
# How to Run Simulations
Navigate to the project directory:  
`C:\Apps\wamp64\www\project\SIoTD`  

Run the following commands in separate terminals for each node:  
```bash
python siotd.py "A" "1" "2,3" "1" "1" > SScenario1/siotda.data
python siotd.py "A" "2" "1" "1" "0" > SScenario1/siotdb.data
```

### Explanation:
Group: "A"
Node ID: "1" or "2"
Recipients: "2,3" or "1"
Category: "1"
Master/Slave: "1" (Master) or "0" (Slave)

Scenario 1
Commands:

```bash
python siotd.py "A" "1" "2,3" "1" "1" > SScenario1/siotda.data
python siotd.py "A" "2" "1" "1" "0" > SScenario1/siotdb.data
```

Intent:
Demonstrates communication between two devices in the same group. Device 1 communicates with device 2, enabling LED blinking.

Scenario 2
Commands:

```bash
python siotd.py "A" "1" "2,3" "1" "1" > SScenario2/siotda.data
python siotd.py "A" "2" "1,3" "1" "1" > SScenario2/siotdb.data
python siotd.py "A" "3" "1,2,3" "1" "0" > SScenario2/siotdc.data
```

Intent:
Demonstrates multiple devices in the same group successfully communicating. Master devices communicate with multiple slaves, ensuring IoT security against rogue devices.

Scenario 3
Commands:

```bash
python siotd.py "A" "1" "2" "1" "1" > SScenario3/siotda.data
python siotd.py "A" "2" "1" "1" "0" > SScenario3/siotdb.data
python siotd.py "B" "3" "2,1" "1" "1" > SScenario3/siotdc.data
```

Intent:
Tests communication restrictions. Devices not in the same group cannot communicate. Demonstrates resistance to attacks like socket-based rogue takeovers by separating control and administration networks.

Scenario 4
Commands:

```bash
python siotd.py "A" "1" "2" "1" "1" > SScenario4/siotda.data
python siotd.py "A" "2" "1" "1" "0" > SScenario4/siotdb.data
python siotd.py "B" "3" "4" "1" "1" > SScenario4/siotdc.data
python siotd.py "B" "4" "3" "1" "0" > SScenario4/siotdd.data
```

Intent:
Validates communication between devices in the same group while recording all transactions on the Ethereum blockchain. Demonstrates IoT security and access control.

Scenario 5
Commands:

```bash
python siotd.py "A" "1" "3" "1" "1" > SScenario5/siotda.data
python siotd.py "A" "2" "4" "1" "1" > SScenario5/siotdb.data
python siotd.py "B" "3" "1" "1" "1" > SScenario5/siotdc.data
python siotd.py "B" "4" "2" "1" "0" > SScenario5/siotdd.data
```

Intent:
Tests security policy violations. Devices in different groups attempt communication but fail. Demonstrates enforcement of security through Ethereum smart contracts.

Scenario 6
Commands:

```bash
python siotd.py "B" "3" "1" "1" "1" > SScenario6/siotdc.data
python siotd.py "B" "4" "2" "1" "0" > SScenario6/siotdd.data
```

Intent:
Tests creating and destroying blockchain objects on the fly. Verifies Ethereum's ability to maintain data transmission even when devices are intermittently connected.

## Abstract
This project demonstrates the resilience of simulated IoT devices (SIoTD) against attacks like man-in-the-middle and denial of service using blockchain technology and asymmetric encryption. Key highlights include:

- Implementation of blockchain-based IoT security with Ethereum and smart contracts.
- Development of a control application using Ionic, PHP, Python, and elliptic curve cryptography.
- Evaluation of scenarios demonstrating security against rogue attacks.

## Project Report
View Project Report[https://drive.google.com/file/d/1QIKxcJsk_0SLO3AS92WML20m0rTLU8bb/view?usp=sharing]

## Video
Watch Demonstration Video[https://drive.google.com/file/d/1gKTNwLxOOm94RUKuk_B_rjs-kXpvpxCT/view]

## Further Work
Continue improving socket communication between the application and SIoTD. While connections are established, data exchange needs further definition for device configuration. Contributions are welcome! Feel free to make pull requests and collaborate.
