|---------------------------------------------------------------------------------------------------|
|---------------------------------------------------------------------------------------------------|
|------------------------------------- IOT SIMULATION DEVICE----------------------------------------|
|---------------------------------------------------------------------------------------------------|
|---------------------------------------------------------------------------------------------------|

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
			
		
#How blockchain works!
1. Ganache-CLI : The local or private blockchain run on ganache on port 7545. The following command is used to start the server.
				ganache-cli -p 7545 --mnemonic "this is for class project" --networkId 5777 --db ./database --secure -u 0 -u 1 -u 2 -u 3 -u 4 -u 5 -u 6 -u 7 -u 8 -u 9
				
				To ensure that ganache-cli server will run, path to its root directory must be include system environmental variable. The command -p stands for port,
				--mnomonic is like a passphrase for the connection, truffle (provider) uses mnemonic to connect to ganache-cli. These enables to deploy smart contracts
				to ganache-cli using truffle framework for smart contract development and compilation. Truffle file can be found at  
				C:\Apps\wamp64\www\project\SIoTD\IoTApp\src\app\api\TESTRPC\SmartContracts\truffle-config.js 

2. Smart contract : A key element in Etheruem blockchain is the smart contract technology. Smart contracts a basically set of code or instructions
					that executes on Etheruem block chain. They are writting in programming languages such as solidty(js), get converted to
					byte codes, gets sent to blockchain network and has a address that can be used to communucate with it. The smart contract 
					programming structures has extra functionality that allows smarter programming. An example is the modifiers in solidy. 
					It basically allows to execute some code before actually getting into the function. For examples, i require a condition to be 
					true before hitting payable function.

					Once a smart contract is written, the compiler checks for error, and convert it to bytes code. Two elements are needed
					to fully deply a smart contract. The ABI ( written in JSON format) which are data structures, function signatures, and return values of the smart contract, 
					the smart contract address and or the bytecode. I found combining visual studio code IDE and Remix web broswer IDE useful in 
					developing a smart contract. Below links are the resources for smart contract and blockchain development.
					
					Visual Studio IDE : https://davidburela.wordpress.com/2016/11/18/configuring-visual-studio-code-for-ethereum-blockchain-development/
					Truffle and Visual Studio : https://www.trufflesuite.com/tutorials/how-to-install-truffle-and-testrpc-on-windows-for-blockchain-development
					Truffle Configuration : https://www.trufflesuite.com/docs/truffle/reference/configuration
					Understaing ABI : https://solidity.readthedocs.io/en/develop/abi-spec.html
					
					Signing a Contract Transaction : https://web3py.readthedocs.io/en/stable/web3.eth.account.html#sign-a-contract-transaction
                    Structure of a Smart Contract : https://solidity.readthedocs.io/en/v0.5.12/structure-of-a-contract.html
					Examples : https://solidity.readthedocs.io/en/v0.5.3/solidity-by-example.html
					
					
					Deploying Smart Contract :
											In the terminal, cd to C:\Apps\wamp64\www\project\SIoTD\IoTApp\src\app\api\TESTRPC\SmartContracts root directory at 
											Run "truffle migrate 2 > ../../../../../../contract.data"
											The cd back to C:\Apps\wamp64\www\project\SIoTD to run siotd.py
3. Experiment : 
				In my experiment, the most significant of success with blockchain was accomplished in python using Ethereum web3 library. I attempted PHP ethereum and i 
				running into unique problem such as no connection. However, i was able to communicate with ganache-cli to get version number. When i attempt to perform
				or execute further commands, it got no connection. I migrated to using pure JSON RPC library which entails almost writing my own library. Due to the 
				limited amount of time on the project, i am limited. I converted BCTrust Blockchain class to PHP to help with encoding function arguments to match up
				with smart contract ABI, however, i was only able to execute few commands such as creating blockchain Ethereum account, unlocking and locking accounts.
				It appears that dedicating more time and effort on PHP JSON RPC, can give better productivity. Since, new accounts will need funding to transact,
				this experiment could have been more successful with PHP if sendRaw_Transaction or send_transaction command works. However, I was able to get this to work
				using web3 python library
				
				
					
					
					
					
#-------------------------------------------------------------------------------------------------------------------------|
#-------------------------------------------------------------------------------------------------------------------------|
#------------------------------------------- SCENARIOS -------------------------------------------------------------------|
#-------------------------------------------------------------------------------------------------------------------------|
#-------------------------------------------------------------------------------------------------------------------------|
#How to run simulations : 
CD to C:\Apps\wamp64\www\project\SIoTD and execute command for each node a terminal.
Example of commands are : 	python siotd.py "A" "1" "2,3" "1" "1" > SScenario1/siotda.data
							python siotd.py "A" "2" "1" "1" "0" > SScenario1/siotdb.data 
							
							Above configured SIoTD to group A with node id 1, recipients node id 2 and 3,
							category 1, and finally activating it as master(Master will enable immediate
							transmission, 0 will only listen)

#Scenario 1 Command
python siotd.py "A" "1" "2,3" "1" "1" > SScenario1/siotda.data
python siotd.py "A" "2" "1" "1" "0" > SScenario1/siotdb.data

#INTENT : 
The intent of this scenario is to demonstrate that two devices in the same group can
communicate successfully. In this scenation, device id 1 was able to talk to device id 2.
As a result, they could blink each other LED.

#Scenarion 2 Command
python siotd.py "A" "1" "2,3" "1" "1" > SScenario2/siotda.data
python siotd.py "A" "2" "1,3" "1" "1" > SScenario2/siotdb.data
python siotd.py "A" "3" "1,2,3" "1" "0" > SScenario2/siotdc.data


#INTENT : 
The intent of this scenario is to demonstrate that multiple devices in the same group on
Ethereum blockchain network can communicate with each other successfully. The scenario
also demonstrated that master device can communicate with slave multiple slave devices
as long as they are all on the same group. These demonstrate successfully implemention
of IoT security against rogue nodes or devices joining the network to conduct malicious
activities on IoT devices.

#Scenarion 3 Command
python siotd.py "A" "1" "2" "1" "1" > SScenario3/siotda.data
python siotd.py "A" "2" "1" "1" "0" > SScenario3/siotdb.data
python siotd.py "B" "3" "2,1" "1" "1" > SScenario3/siotdc.data

#INTENT : 
The intent of this scenario is to demonstrate that device(s) that is not in the
same group with recipient cannot send a message. In this scenario, SIoTA and SIoTC 
violated the network rule. In this scenario, SIoTDSAS launched an attack to control 
on the SIOTDs through socket connection. However, it was unsuccessfully because
we separated control network from configuration connection. Control code listens to the
blockchain while configuration is done with socket connection. Attack script was able to
guess network token but led control was unsuccessfully. Since the token changes, 
the significance and no configuration data was altered,  penetration can be assumed 
to be minimal. These demonstrate successfully implemention
of IoT security against rogue nodes or devices joining the network to conduct malicious
activities on IoT devices through socket communication. Security was added by separating
control network from administration network.

#Scenarion 4 Command
python siotd.py "A" "1" "2" "1" "1" > SScenario4/siotda.data
python siotd.py "A" "2" "1" "1" "0" > SScenario4/siotdb.data
python siotd.py "B" "3" "4" "1" "1" > SScenario4/siotdc.data
python siotd.py "B" "4" "3" "1" "0" > SScenario4/siotdd.data

#INTENT : 
The intent of this scenario is to demonstrate that device(s) that are in the
same group with recipient can send a message to each other. In this scenario, SIoTA and SIoTB 
and SIoTC and SIoTD violated the network no rule. These demonstrate successfully implemention
of IoT security implementation towards access control of devices in network to perform legitimate
activities on the network. While communicating, all transactions are recording and can be mined on
Etheruem blockchain.

#Scenarion 5 Command
python siotd.py "A" "1" "3" "1" "1" > SScenario5/siotda.data
python siotd.py "A" "2" "4" "1" "1" > SScenario5/siotdb.data
python siotd.py "B" "3" "1" "1" "1" > SScenario5/siotdc.data
python siotd.py "B" "4" "2" "1" "0" > SScenario5/siotdd.data

#INTENT : 
The intent of this scenario is to demonstrate violation of security policy. The scenario
is setup to test the same group violation. SIoTA, SIoTB, SIoTC and SIoTD sent message to
other devices that are not in the same group. As a result, no LED off or blinking was expected
in this scenerio. SIoTA, SIoTB and SIoTC were configured a master and as a result, they could
send messages out and got terminated. SIoTD is expected to run as it wasn't configured to
send messages out and it doesn't receive a message to trigger its transmission. As a result,
the scenarion file has more data than the others.

#Scenarion 6 Command
python siotd.py "B" "3" "1" "1" "1" > SScenario5/siotdc.data
python siotd.py "B" "4" "2" "1" "0" > SScenario5/siotdd.data

#INTENT : 
The intent of this scenario is to test creating blockchain object on the fly.
Compared to other scenarios, this scenario runs a separated simulated script. 
The difference is, it only create blockchain object only when needed
and then destroy's after use. The essence of this
is to test if Etheruem can maintain and data transmission status even when device is not connect.
The result, confirmed that devices can create object to connect on the fly. My
smart contract algorithm allows multiple attempts to add node to the network with
valid credentials and signatures. If it already, exisit then the request still gets
a green light. As a result, no error to trigger runtime error on devices compare to
security violations that are enforced with smart contract modifiers.

#ABSTRACT
In this project, I demonstrated that the simulated IoT device (SIoTD) is resilient to man-in-the-middle attack, denial of service, and other rogue attacks using blockchain technology and asymmetric encryption. I performed a programming implementation of blockchain based technology for Internet of Things (IoT) devices using PHP, Python and Ionic framework. I designed and developed a control Ionic application to interact with the simulated IoT devices. I also simulated the IoT device by creating script using python. In the Ionic Application (App), PHP API and Elliptic Curve Cryptography (ECC) library was used to create the asymmetric key encryption key pairs and Ethereum blockchain account for SIoT devices. The IoT device simulation script (SIoTDSS) which is the server, was employed to create multiple master and slave SIoT devices. As a result, they took commands from each other within the Ethereum blockchain and through the Ethereum smart contract. The control App server installed and implemented OpenSSL library Secure Sockets Layer (SSL) to enable secure communication. An IoT device simulation attack script (SIoTDSAS) was used to launch a rogue attack against SIoTD device TCP socket connections. In the end, we evaluated the data based on the six scenario results and effectiveness of using Ethereum blockchain network for SIoTD. This project demonstrated that SIoTDs within the blockchain network are not vulnerable to man-in-the-middle attack and all sorts of password attacks that allow an attacker to perform a rogue takeover of IoT devices.

#PROJECT REPORT
https://drive.google.com/file/d/1QIKxcJsk_0SLO3AS92WML20m0rTLU8bb/view?usp=sharing

#VIDEO
https://drive.google.com/file/d/1gKTNwLxOOm94RUKuk_B_rjs-kXpvpxCT/view?usp=sharing

#FURTHER WORK
Finished the socket communication between app and SIoTD. Currently, they are connecting but data exchange needs to be more defined for device configuration. Feel free to make contributions and pull up your branch code. Thank you.









