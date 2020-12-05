# -*- coding: utf-8 -*-
#!/usr/bin/env python3
"""
/* Script       :   Simulated Internet of Things Device (SIOTD) Script
*  Author       :   Oladayo Luke
*  School       :   Nova Southeastern Univerisity, Davies Florida
*  Program      :   Computer Science Doctoral Program
*  Professor    :   Wei Li
*  Course       :   ISEC-0740 Secure Systems Analysis and Design
*  Intent       :   The purpose of this project is to  simulated IoT device (using python) and
                    Ethereum blockchain network. As a result, i will demonstrate security for the simulated IoT devices. 
*  References   :   https://web3py.readthedocs.io/en/stable/quickstart.html
*                   https://solidity.readthedocs.io/en/v0.5.3/solidity-by-example.html
*                   https://web3py.readthedocs.io/en/stable/web3.eth.account.html#sign-a-contract-transaction
*                   https://readthedocs.org/projects/demo-enjoyqq-seo-kontes-hasil-uji-coba/downloads/pdf/latest/
*                   https://web3py.readthedocs.io/en/stable/examples.html#making-transactions
*                   https://solidity.readthedocs.io/en/v0.5.12/structure-of-a-contract.html
*                   https://www.trufflesuite.com/tutorials/how-to-install-truffle-and-testrpc-on-windows-for-blockchain-development

* IDE           :   Microsoft Visual Code
*               :   https://remix.ethereum.org/#optimize=false&evmVersion=null&version=soljson-v0.6.1+commit.e6f7d5a4.js
*
* Environment   :   https://www.trufflesuite.com/tutorials/how-to-install-truffle-and-testrpc-on-windows-for-blockchain-development
*                   https://www.trufflesuite.com/ganache
*                   https://chrome.google.com/webstore/category/extensions?hl=en-US
* Server   cmd:     ganache-cli -p 7545 --mnemonic "this is for class project" --networkId 5777 --db ./database --secure -u 0 -u 1 -u 2 -u 3 -u 4 -u 5 -u 6 -u 7 -u 8 -u 9
* Contract cmd:     ../../../../../../contract.data              
*
*
*  Notes      : 
*/
"""

import os
import json, codecs
import time
import socket
import selectors
import types
import requests 
from web3 import Web3
from random import randint  

import sys
#parentPath = os.path.dirname(os.getcwd())
parentPath = os.getcwd()
sys.path.append(parentPath)
from blockchain import Blockchain


"""
AFURA CLOUD CONFIGURATION PARAMETER
# infura_url = "" (NOT IMPLEMENTED)
# web3 = Web3.Web3.HTTPProvider(infura_url)

CONFIGURATION FILE NAME : config.data
BASIC  SCENARIO   CONFIGURATION :
                        GROUP A :
                                NODE 1:
                                    self.group      = 'A' #Group of the device
                                    self.nodeId     = 1   #Device unique id
                                    self.to         = [2]   #Another device in the same group
                                    self.category   = 1   #Device category(for classification)
                                NODE 2:
                                    self.group      = 'A'
                                    self.nodeId     = 2
                                    self.to         = [1] 
                                    self.category   = 1
                        GROUP B :
                                NODE 1
                                    self.group      = 'B'
                                    self.nodeId     = 3 
                                    self.to         = [4] 
                                    self.category   = 1
                                NODE 2:
                                    self.group      = 'B'
                                    self.nodeId     = 4 
                                    self.to         = [3] 
                                    self.category   = 1
"""


LOW = 0
HIGH = 1

class SIOTD(object):
    def __init__(self, group, nodeId, to_list, category, master):
        self.group = group #group name
        self.nodeId = int(nodeId) #node unique id

        if(len(to_list) > 0):
            self.node_list_cmd = list(  map(int, to_list.split(","))  )
            self.node_list_cmd = to_list.split(",")  #message receipients id.
            self.to = int(self.node_list_cmd[0])  #message receipient id.
        else:
            raise("Node List cannot be empty")
        self.category = int(category) #no enforcement on this variable(its for type of device)

        self.is_master = int(master)# flag to determine if a master role
        self.led_state = HIGH#flag for led state
        self.read_state = LOW#flag for blockchain read state
        self.status_interval = 1000000 #flag for controlling main loop
        self.is_factory_reset = 0#flag for reseting device
        self.is_test_contract = 0#flag for testing smart contract
        self.is_register_device = 0#flag for registering node to blockchain
        self.is_send_status = 1#flag for sending message to blockchain
        self.is_read_status = 0#should be on before simulation
        self.is_all_network_node = False#should be used to send message to all nodes on network


        #----------------------------------------------------------------
        # ASSIGN SOCKET CONFIGURATION PARAMS
        #----------------------------------------------------------------
        self.config = self.load_config()
        if(self.config):
                        
            #---------------------------------------------------------------
            #  NOTE: 
            #      For automation, we can get self.nodeId from the database and use it to 
            #       map to the siotd information in configuration file. This will require
            #       deploying the api and database to the cloud or starting the local server
            #       I believe, running simulation by semi auto configuration fullfill the
            #       requirement of this class project. Further research can perform full auto.
            #       The current state of art is that you manually configure device from config.data
            #       and you can use custom blockchain account on the fly with socket connect but
            #       restart and setting node_id manually is required in the device  *.py file 
            #
            #
            #  EXAMPLE OF API:
            #       self.api_setup_get_data = {"GetSetUpData":"true", "api_key":self.api_key,"uid":5} 
            #       setup_request = self.get(self, self.api_setup_get_data)
            #----------------------------------------------------------------
            # NODE ATRIBUTE PARAMS
            #----------------------------------------------------------------
            self.groupId = self.config['group'][self.group].get('id')
            self.groupTicket = self.config['group'][self.group].get('ticket')
            self.siotd_device_ids = self.config['siotd_ids']#contain id to name mapping
            
            self.api_key          = self.config.get('api_key')

            if(str(self.nodeId) in self.config['database_param'].keys() and 'adid' in self.config['database_param'][str(self.nodeId)].keys()):
                if(self.config['database_param'][str(self.nodeId)].get('adid')):
                    self.nodeId =  int(self.config['database_param'][str(self.nodeId)].get('adid'))#assign dynamic node id

            #use the node id to make to device name
            self.device_name = list(self.config['siotd_ids'].keys())[list(self.config['siotd_ids'].values()).index(self.nodeId)]
            if(self.device_name == ""):
                self.device_name = 'SIoTD_E'#give it one just in case!

            self.number_of_nodes = self.config['all_network_node']['active']
            if(len(self.node_list_cmd) != self.number_of_nodes):
                self.number_of_nodes = len(self.node_list_cmd)

            self.node_list = [x for x in range(1,self.number_of_nodes + 1)]
            run_all_nodes = self.config['all_network_node']['run_all'] #run all active nodes
            if(len(self.node_list_cmd) > 1 or run_all_nodes == 1):
                self.is_all_network_node = True#should be used to send message to all nodes on network

            if(self.config['all_network_node']['role'][self.device_name] != 0 and self.is_master == 0):#overwrite
                self.is_master = self.config['all_network_node']['role'][self.device_name]

            self.device_registration_name = self.device_name.lower()+'_registration'
            #self.is_register_device =  self.config[self.device_registration_name]
            self.siotd_account_id = self.config['siotd_ids'][self.device_name]#is the same as node id
            self.account          = self.config['accounts'][self.nodeId]
            self.is_factory_reset = int(self.config['factory_reset'].get(self.device_name))
            self.host             = self.config['host'] 
            #There will be no coliision since node_is is incremental from the database
            self.port             = self.config.get('siotd_ports') + self.nodeId   
            self.token            = self.config.get('token')

            if(str(self.nodeId) in self.config['database_param'].keys() and 'category' in self.config['database_param'][str(self.nodeId)].keys()):
                
                for key, value in self.config['database_param'][str(self.nodeId)].items():
                    #We don't want to overwrite data. Overwritten can be done in factory reset
                    if(key == 'uid' and self.config['database_param'][str(self.nodeId)]['uid'] != ''):
                        self.nodeId = value
                    if(key == 'gid' and self.config['database_param'][str(self.nodeId)]['gid'] != ''):
                        self.groupId = value
                    if(key == 'ticket' and self.config['database_param'][str(self.nodeId)]['ticket'] != ''):
                        self.groupTicket = value
                    if(key == 'adid' and self.config['database_param'][str(self.nodeId)]['adid'] != ''):
                        self.nodeId = value
                    if(key == 'category' and str(self.nodeId) in self.config['database_param'].keys() ):
                        if('category' in  self.config['database_param'][str(self.nodeId)].keys() and  \
                                    self.config['database_param'][str(self.nodeId)]['category'] !=''):
                            self.category = value
                    print("\n[INFO] Loading Dynamic Configuration Parameters! -----{} : {} ----{}".format(key, value, time.ctime(time.time())))

            #----------------------------------------------------------------
            # SET UP BLOCKCHAIN
            #----------------------------------------------------------------
            if(self.device_name+"_account" in self.config['blockchain'].keys()):
                self.account = self.config['blockchain'][self.device_name+"_account"]
                 #We don't want to overwrite data. Overwritten can be done in factory reset
            
            if(self.account):
                self.blockchain = Blockchain(self.account)
                self.account = self.blockchain.get_account()    

            if(self.blockchain):
                print("[INFO] Blockchain Account : {}".format(self.account))
                self.isBCConnected = self.blockchain.get_connection_status()
                print("[INFO] Blockchain is connected : {}".format(self.isBCConnected))
                self.balance  = self.blockchain.get_balance()
                print("[INFO] Blockchain Account Balance  : {}".format(self.balance))
                self.contract = self.blockchain.get_contract()
                self.contract_address = self.blockchain.get_contract_address()
                print("[INFO] Blockchain Contract Address  : {}".format(self.contract_address))
                print("[INFO] Node Master flag is  : {}".format(self.is_master))
                self.prive_key = self.config.get('private_key')[self.siotd_account_id]
                print("[INFO] Blockchain Private Key  : {}".format(self.prive_key))
                self.private_key = self.config.get('private_key')[self.siotd_account_id]

        #----------------------------------------------------------------
        # OTHER PARAMS
        #----------------------------------------------------------------
        self.sel = selectors.DefaultSelector()
        self.api_test_post_data = {
                     "TestAPI":"true","api_key":self.api_key,"uid":"5","adid":"24","tempc":"30","humid":"20","sound":"10","vibra":"25","lat":"30.000","long":"4.50000"
                    }
        self.api_test_get_data = {"GetControlData":"true", "api_key":self.api_key,"uid":5} 
        self.api_get_url = "http://dayorbit.com/porteqbot/api/GetData.php"
        self.api_post_url = "http://dayorbit.com/porteqbot/api/PostData.php"

        print("\n[INFO] Device registration status ------------ {}".format(self.is_register_device))

    def load_config(self):
        config = {}
        try:
            f_read = open(os.path.join(parentPath, "config.data"), 'rb')
            config = json.load(f_read)
            f_read.close()
        except OSError as err:
            print(err)
        return config

    def save_config(self):
        try:
            with open(os.path.join(parentPath, "config.data"), 'wb') as f:
                json.dump(self.config, codecs.getwriter('utf-8')(f), ensure_ascii=False)
            print("\n[INFO] Updated configuration file -------{}".format(time.ctime(time.time())))
            self.config = self.load_config()
            return True
        except OSError as err:
            print(err)
            self.config = self.load_config()
        return False

    def device_factory_reset(self):
        print("\n[INFO] Blockchain Record Reset Begins--------------{}".format(time.ctime(time.time())))

        self.blockchain.delete_record(self.nodeId, self.groupTicket)
        print("\n[INFO] Blockchain Record Reset Ends ---------------{}".format(time.ctime(time.time())))
        self.config['factory_reset'][self.device_name] = 0
        self.config[self.device_registration_name ] = 0

        #Save the configuration
        if(self.save_config()):
            print('##################################################################')
            print('##################################################################')
            print('##################### RESTART REQUIRED ###########################')
            print('####################### SHUTTING DOWN ############################')
            print('##################################################################')
            time.sleep(5)
            sys.exit()

    def register_node(self):
        print("\n[INFO] Blockchain Account Before Balance ----------{}".format(self.blockchain.get_balance()))
        print("\n[INFO] Blockchain Registration Begins--------------{}".format(time.ctime(time.time())))

        #print("=======> self.category: {}, self.groupId: {}, self.nodeId: {}, self.groupTicket: {}".format(self.category, self.groupId, self.nodeId, self.groupTicket))
        self.blockchain.add_node(self.category, self.groupId, self.nodeId, self.groupTicket, self.private_key)     
        
        print("\n[INFO] Blockchain Registration Ends----------------{}".format(time.ctime(time.time())))
        print("\n[INFO] Blockchain Account After Balance -----------{}".format(self.blockchain.get_balance()))
        #return receipt

    def send_msg(self, to, message):
        print("\n[INFO] Sending Blockchain Message -----------------{}".format(time.ctime(time.time())))
        print("\n[INFO] Blockchain Account Before Balance ----------{}".format(self.blockchain.get_balance()))
        print("\n[INFO] Blockchain Registration Begins--------------{}".format(time.ctime(time.time())))

        # send message to recipient node that is on the same grop.
        receipt = self.blockchain.send_MSG(self.nodeId, to, message, self.groupTicket, self.private_key)
        
        print("\n[INFO] Blockchain Registration Ends----------------{}".format(time.ctime(time.time())))
        print("\n[INFO] Blockchain Account After Balance -----------{}".format(self.blockchain.get_balance()))  
        return receipt     

    
    
    def read_msg(self):
        print("\n[INFO] Reading Blockchain Message -----------------{}".format(time.ctime(time.time())))
        print("\n[INFO] Blockchain Account Before Balance ----------{}".format(self.blockchain.get_balance()))
        print("\n[INFO] Blockchain Registration Begins--------------{}".format(time.ctime(time.time())))

        receipt = self.blockchain.read_MSG(self.nodeId, self.groupTicket, self.private_key)
        
        print("\n[INFO] Blockchain Registration Ends----------------{}".format(time.ctime(time.time())))
        print("\n[INFO] Blockchain Account After Balance -----------{}".format(self.blockchain.get_balance()))  
        return receipt     


    def testContract(self):
        print("\n[INFO] Blockchain Account After Balance -----------{}".format(self.blockchain.get_balance()))
        print("\n[INFO] Blockchain Test Begins----------------------{}".format(time.ctime(time.time())))
        
        response  = self.blockchain.test()

        print("\n[INFO] Blockchain Registration Ends----------------{}".format(time.ctime(time.time())))
        print("\n[INFO] Blockchain Account After Balance -----------{}".format(self.blockchain.get_balance()))
        return response

    def process_socket_data(self, json_str):
        """
            This method will on be used for initial configurtion data that comes from the control application(master)
            For example : when user add device on app, the app will transfer generated IDs such as user id, device id,
            and group ticket. Data such as private key, will be copied into device configuration file if the private key
            was generated by the control app(it should be never be transfered via unsecure socket). Another important
            data that can come in through this method is the blockchain accound ID or acoount number. The enssence of
            these data transmission via socket is to enable automatic configuration while not creating vulnerability to
            the system. As a result, measure is needed to types of data that can be transfered via the socket.
        """
        is_restart = False
        try:
            json_parsed_data = json.loads(json_str)
                
            if('adid' in json_parsed_data.keys() and 'token' in  json_parsed_data.keys()):
                if(json_parsed_data['token'] == self.token):
                    new_nodeId = 0
                    #--------------------------------------------------------------------------------
                    # PACKET JSON KEYS :  adid,uid,dtid,gid, nickname,ticket
                    #--------------------------------------------------------------------------------
                    for key, value in json_parsed_data.items():
                        print("\n[INFO] Received Configuration Parameters! ----------{} : {}".format(key, value))
                        #We don't want to overwrite data. Overwritten can be done in factory reset
                        if(key == "blockchain_account" and self.config['blockchain'][self.device_name+"_account"] == ''):                   
                            self.config['blockchain'][self.device_name+"_account"] = json_parsed_data["blockchain_account"]
                            is_restart = True

                        #We don't want to overwrite data. Overwritten can be done in factory reset
                        if(key == 'nickname' and self.device_name in self.config['siotd_ids'].keys()):
                            if(self.config['siotd_ids'][self.device_name] == ""):
                                #this is how we create unique device name for siot device
                                self.config['siotd_ids'][value] =  self.nodeId
                                is_restart = True

                        if(key == 'uid' and str(self.nodeId) in self.config['database_param']):
                            if('uid' in self.config['database_param'][str(self.nodeId)] and self.config['database_param'][str(self.nodeId)]['uid'] == ''):
                                self.config['database_param'][str(self.nodeId)]['uid'] = value
                                is_restart = True

                        if(key == 'gid' and str(self.nodeId) in  self.config['database_param']):
                            if('gid' in  self.config['database_param'][str(self.nodeId)] and self.config['database_param'][str(self.nodeId)]['gid'] == ''):
                                self.config['database_param'][str(self.nodeId)]['gid'] = value
                                is_restart = True

                        if(key == 'ticket' and str(self.nodeId) in self.config['database_param']):
                            if('ticket' in self.config['database_param'][str(self.nodeId)] and self.config['database_param'][str(self.nodeId)]['ticket'] == ''):
                                self.config['database_param'][str(self.nodeId)]['ticket'] = value
                                is_restart = True

                        if(key == 'adid' and str(self.nodeId) in self.config['database_param']):
                            if('adid' in  self.config['database_param'][str(self.nodeId)] and self.config['database_param'][str(self.nodeId)]['adid'] == ''):
                                self.config['database_param'][str(self.nodeId)]['adid'] = value
                                new_nodeId = value
                                is_restart = True

                        if(key == 'dtid' and str(self.nodeId) in self.config['database_param']):
                            if('dtid' in  self.config['database_param'][str(self.nodeId)] and self.config['database_param'][str(self.nodeId)]['dtid'] == ''):
                                self.config['database_param']['category'] = value
                                is_restart = True
                else:
                    print("[WARNING] Invalid Token Received! --------------------{}".format(json_parsed_data['token']))

                    
            #Save the configuration
            if(is_restart ):
                self.save_config()
                self.config = self.load_config()
                if(new_nodeId > 0  and str(new_nodeId) in self.config['database_param'].keys()):#confirms'registration
                    self.nodeId = self.config['database_param'][str(new_nodeId)]['adid']
                    print('##################################################################')
                    print('########### NODE_ID : {} SAVE TO SIOTD FILE MANUALLY  ############'.format(self.nodeId))
                    print('##################### RESTART REQUIRED ###########################')
                    print('####################### SHUTTING DOWN ############################')
                    print('##################################################################')
                    time.sleep(5)
                    sys.exit()
        except OSError as err:
            print(err)


    def turn_on_led(self):
        print("@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")
        print("@########################################################################@")
        print("@######################## {} LED   #################################@".format(self.device_name))
        print("@########################################################################@")
        print("@###########             #####      ########   ##########################@")
        print("@###########   #######   #####   ###  ######   ##########################@")
        print("@###########   #######   #####   ####  #####   ##########################@")
        print("@###########   #######   #####   #####  ####   ##########################@")
        print("@###########   #######   #####   ######  ###   ##########################@")
        print("@###########   #######   #####   #######  ##   ##########################@")
        print("@###########   #######   #####   ########  #   ##########################@")
        print("@###########             #####   #########     ##########################@")
        print("@########################################################################@")
        print("@########################################################################@")
        print("@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")


    def turn_off_led(self):
        print("@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")
        print("@########################################################################@")
        print("@########################  {} LED  #################################@".format(self.device_name))
        print("@########################################################################@")
        print("@###########             ###           ####           ###################@")
        print("@###########   #######   ###   ############   ###########################@")
        print("@###########   #######   ###   ############   ###########################@")
        print("@###########   #######   ###            ###           ###################@")
        print("@###########   #######   ###   ############   ###########################@")
        print("@###########   #######   ###   ############   ###########################@")
        print("@###########   #######   ###   ############   ###########################@")
        print("@###########             ###   ############   ###########################@")
        print("@########################################################################@")
        print("@########################################################################@")
        print("@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@")


    def get(self, params):
        # sending get request and saving the response as response object 
        r = requests.get(url = self.api_get_url, params = params) 
        
        # extracting data in json format 
        data = r.json() 
        
        print("[INFO] GET Response : {}".format(data))
        return data

    def post(self, params):
        result = {}
        print(params)
        
        header = {
            #"Content-type": 'application/json',
            "Accept": 'application/json'}

        # sending post request and saving response as response object 
        response = requests.post(url = self.api_post_url, 
                                data = params
                                ,headers = header
                                ) 
        
        # extracting response text  
        if(response.text):
            try:
                result = response.json() 
                print("[INFO] POST Response code : {} res_data : {} ".format( response.status_code, result))
                
            except OSError as err:
                print("[ERROR] err:{}".format(str(err)))
        return  result

    def get_status(self):
        return self.get(self.api_test_get_data)

    def accept_wrapper(self, sock):
        conn, addr = sock.accept()  # Should be ready to read
        print('[INFO] Accepted connection from', addr)
        conn.setblocking(False)
        data = types.SimpleNamespace(addr=addr, inb=b'', outb=b'')
        #print("Data Received : {}".format(data))#not the data
        events = selectors.EVENT_READ | selectors.EVENT_WRITE
        self.sel.register(conn, events, data=data)

    def service_connection(self, key, mask):
        sock = key.fileobj
        data = key.data
        if mask & selectors.EVENT_READ:
            recv_data = sock.recv(1024)  # Should be ready to read
            if recv_data:
                if(b'GET /socket.io/' not in recv_data):
                    data.outb += recv_data
                    print("[INFO] Data Received : {}".format(data.outb))
                    print()
                    self.process_socket_data(data.outb)
                else:
                    print("[INFO] Data Received : {}".format(recv_data))
            else:
                print('[INFO] Closing connection to', data.addr)
                print()
                self.sel.unregister(sock)
                sock.close()           

        if mask & selectors.EVENT_WRITE:
            if data.outb:
                print('[INFO] Sending', repr(data.outb), 'to', data.addr)
                print()
                sent = sock.send(data.outb)  # Should be ready to write
                data.outb = data.outb[sent:]
    


    def run(self):
       
        """
        ////////////////////////////////////////////////////////////
        //               SOCKET SETUP                             //
        ////////////////////////////////////////////////////////////
        """ 
        
        lsock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        lsock.bind((self.host, self.port))
        lsock.listen()
        print("[INFO] Socket Server Listening at {}:{} \n".format(self.host, self.port))

        lsock.setblocking(False)
        self.sel.register(lsock, selectors.EVENT_READ, data=None)
        print("....")
        
        #-----------------------------------------------------------
        # Counter : for controlling blockchain interval(set every  to 10 seconds)
        # node_select_count : for selecting receipient nodes
        #-----------------------------------------------------------

        counter  = 0
        node_select_count = 0

        while True:
            """
            ////////////////////////////////////////////////////////////
            //               WATCHDOG FOR ETHEREUM                   //
            ////////////////////////////////////////////////////////////
            """           
            response = ""
            in_cmd_data = ""
            out_cmd_data = ""
            counter = counter + 1
            if( counter > self.status_interval ):
                print("\n[INFO] Blockchain Transaction Begins--------------{}".format(time.ctime(time.time())))
                print("\n[INFO] Blockchain Account Balance ----------------{}".format(self.blockchain.get_balance()))
                counter = 0

                #----------------------------------------------------------------
                # TEST
                #----------------------------------------------------------------
                if(self.is_test_contract):
                    response = self.blockchain.test()
                    print(response['logs'][0].data)
                print("[INFO] Blockchain Transaction Ends ----------------{}".format(time.ctime(time.time())))
 
                #----------------------------------------------------------------
                # REGISTRATION
                #----------------------------------------------------------------
                if(self.is_register_device == 0):
                    self.register_node()
                    self.is_register_device = 1
                    self.is_send_status = 1#Then turn on reading

                if(self.is_all_network_node == True):
                    #-----------------------------------------------------------------
                    # NODE SELECTION CONTROL
                    #-----------------------------------------------------------------
                    # Select node in incremental sequence 1...4
                    self.to = int(self.node_list_cmd[randint(0, self.number_of_nodes - 1)])
                    if(self.to == self.nodeId):
                        self.to = self.node_list_cmd[0]

                #node is registered to blockchain network
                if(self.is_register_device == 1):  

                    #----------------------------------------------------------------
                    # TOGGLE SEND & LED
                    #----------------------------------------------------------------
                    if(self.led_state == LOW):
                        out_cmd_data = "turn on led"
                        self.turn_off_led()
                        print("\n[INFO] TOGGLE SEND: led_state : {}, out : {}, send_status : {}".format(self.led_state, out_cmd_data, self.is_send_status))
                    else:
                        out_cmd_data = "turn off led"
                        self.turn_on_led()
                        print("\n[INFO] SEND: led_state : {}, out : {}, send_status : {}".format(self.led_state, out_cmd_data, self.is_send_status))


                    #----------------------------------------------------------------
                    # BLOCKCHAIN TRANSMIT AND RECEIVE
                    #----------------------------------------------------------------
                    if(self.is_master == 1):
                        #----------------------------------------------------------------
                        # SEND TRANSACTION TO BLOCKCHAIN
                        #----------------------------------------------------------------
                        #out_cmd_data = "turn off led"
                        print('[INFO]-------------------------SENDING-----------------------{}'.format(out_cmd_data))
                        self.send_msg(self.to, out_cmd_data)
                        #out_cmd_data = "" #reset buffer
                        #----------------------------------------------------------------
                        # RECEIVE TRANSACTION FROM BLOCKCHAIN
                        #----------------------------------------------------------------
                        in_cmd_data = self.read_msg()#always listen
                        if(in_cmd_data):
                            print('[INFO]------------------------RECEIVED-----------------------{}'.format(in_cmd_data))
                    else:
                        #----------------------------------------------------------------
                        # RECEIVE TRANSACTION FROM BLOCKCHAIN
                        #----------------------------------------------------------------
                        in_cmd_data = self.read_msg()#always listen
                        if(in_cmd_data):
                            print('[INFO]------------------------RECEIVED-----------------------{}'.format(in_cmd_data))
                            #----------------------------------------------------------------
                            # SEND TRANSACTION TO BLOCKCHAIN
                            #----------------------------------------------------------------
                            #out_cmd_data = "turn on led"
                            print('[INFO]-------------------------SENDING-----------------------{}'.format(out_cmd_data))
                            self.send_msg(self.to, out_cmd_data)
                            out_cmd_data = "" #reset buffer
                            

                    if(in_cmd_data):
                        if(in_cmd_data == "turn on led"):
                            self.led_state = HIGH
                            out_cmd_data = "turn off led"#blink other device
                            in_cmd_data = ""
                            self.is_send_status = 1
                        if(in_cmd_data == "turn off led"):
                            self.led_state = LOW
                            out_cmd_data = "turn on led"
                            in_cmd_data = ""
                            self.is_send_status = 1


                    #----------------------------------------------------------------
                    # FACTORY RESET
                    #----------------------------------------------------------------
                    if(self.is_factory_reset):
                        self.device_factory_reset()
                        self.is_factory_reset = 0


                    #----------------------------------------------------------------
                    # TOGGLE READ AND SEND
                    #----------------------------------------------------------------
                    if(self.read_state == LOW):
                        self.read_state = HIGH
                        print("\n[INFO] TOGGLE READ: read_state : {}, is_read_status : {}".format(self.read_state, self.is_read_status))
                    else:
                        self.read_state = LOW
                        print("\n[INFO] TOGGLE READ: read_state : {}, is_read_status : {}".format(self.read_state, self.is_read_status))
                else:
                    pass
            
            #----------------------------------------------------------------
            # SOCKET SESSION
            #----------------------------------------------------------------
            events = self.sel.select(timeout=0)

            for key, mask in events:
                if key.data is None:
                    self.accept_wrapper(key.fileobj)
                else:
                    self.service_connection(key, mask)

if __name__ == "__main__":
    print("|-----------------------------------------------------------------|") 
    print("|-----------------------------------------------------------------|") 
    print("|--------------------- IOT SIMULATION DEVICE----------------------|") 
    print("|-----------------------------------------------------------------|") 
    print("|-----------------------------------------------------------------|") 
    print('[ARG 1] GROUP NAME      : "A"  #For example, A or B')
    print('[ARG 2] FROM NODE ID    : "1"  #For example, 1, 2,3, or 4')
    print('[ARG 3] TO NODE ID      : "2"  #For example, 2, or 2,3,4 for multiple')
    print('[ARG 4] CATEGORY ID     : "1"  #For example, 1')
    print('[ARG 5] MASTER FLAG     : "0"  #For example, 0 for slave 1 for master')

    #INSTANTIATE OBJECT
    app = SIOTD(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4], sys.argv[5])
    #RUN SCRIPT
    app.run()
        

