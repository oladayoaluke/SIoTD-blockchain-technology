
# -*- coding: utf-8 -*-
#!/usr/bin/env python3
"""
/* Author  : Oladayo Luke
*  School  : Nova Southeastern Univerisity, Davies Florida
*  Program : Computer Science Doctoral Program
*  Course  : ISEC-0740 Secure Systems Analysis and Design
*  Intent  : The purpose of this class is to control server as an interface class with
*            etheruem blockchain for simulated IoTD (SIoTD). As a result,
*            will provide security to the simulated IoT devices. 
*  References : https://web3py.readthedocs.io/en/stable/quickstart.html
*
*  Documentation : 
*/
"""

import os
import sys
import json
import time
import pprint
from web3 import Web3
from eth_account.messages import encode_defunct

GAS_LIMIT = 200000
#parantPath = os.path.dirname(os.getcwd())
parantPath = os.getcwd()
f_read = open(os.path.join(parantPath, "config.data"), 'rb')
CONFIG_DATA = json.load(f_read)
f_read.close()
#GANACHE_URL = CONFIG_DATA['host'] + ":"+ CONFIG_DATA['ganache_port']
web3 = Web3(Web3.HTTPProvider("http://" + CONFIG_DATA['host'] + ":"+ CONFIG_DATA['ganache_port']))
ABI_PARAM = '[{"inputs":[],"stateMutability":"nonpayable","type":"constructor"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"uint256","name":"id","type":"uint256"},{"indexed":false,"internalType":"uint8","name":"sender","type":"uint8"},{"indexed":false,"internalType":"uint8","name":"receiver","type":"uint8"},{"indexed":false,"internalType":"uint8","name":"grpId","type":"uint8"},{"indexed":false,"internalType":"string","name":"message","type":"string"}],"name":"MessageCreated","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"uint256","name":"id","type":"uint256"},{"indexed":false,"internalType":"address","name":"owner","type":"address"},{"indexed":false,"internalType":"string","name":"message","type":"string"},{"indexed":false,"internalType":"bool","name":"status","type":"bool"}],"name":"TransactionCompleted","type":"event"},{"anonymous":false,"inputs":[{"indexed":false,"internalType":"uint256","name":"id","type":"uint256"},{"indexed":false,"internalType":"address","name":"owner","type":"address"},{"indexed":false,"internalType":"string","name":"message","type":"string"},{"indexed":false,"internalType":"bool","name":"status","type":"bool"}],"name":"TransactionCreated","type":"event"},{"inputs":[{"internalType":"uint8","name":"","type":"uint8"}],"name":"SIoTDSCMember","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"uint8","name":"_category","type":"uint8"},{"internalType":"uint8","name":"_grpId","type":"uint8"},{"internalType":"uint8","name":"_id","type":"uint8"},{"internalType":"uint256","name":"_grpticket","type":"uint256"},{"internalType":"uint256","name":"_r","type":"uint256"},{"internalType":"uint256","name":"_s","type":"uint256"}],"name":"SIoTDSC__AddNode","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"uint8","name":"_id","type":"uint8"},{"internalType":"uint256","name":"_grpTicket","type":"uint256"}],"name":"SIoTDSC__DeleteRecord","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"uint8","name":"sender","type":"uint8"},{"internalType":"uint256","name":"_grpTicket","type":"uint256"},{"internalType":"uint256","name":"_r","type":"uint256"},{"internalType":"uint256","name":"_s","type":"uint256"}],"name":"SIoTDSC__ReadMSG","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"uint8","name":"sender","type":"uint8"},{"internalType":"uint8","name":"receiver","type":"uint8"},{"internalType":"string","name":"msgStr","type":"string"},{"internalType":"uint256","name":"_grpTicket","type":"uint256"},{"internalType":"uint256","name":"_r","type":"uint256"},{"internalType":"uint256","name":"_s","type":"uint256"}],"name":"SIoTDSC__SendMSG","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[{"internalType":"uint256","name":"","type":"uint256"}],"name":"Transactions","outputs":[{"internalType":"uint256","name":"id","type":"uint256"},{"internalType":"address","name":"owner","type":"address"},{"internalType":"string","name":"message","type":"string"},{"internalType":"bool","name":"status","type":"bool"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"address","name":"","type":"address"}],"name":"addr_ids","outputs":[{"internalType":"uint8","name":"","type":"uint8"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"uint8","name":"","type":"uint8"}],"name":"boxes","outputs":[{"internalType":"uint256","name":"id","type":"uint256"},{"internalType":"uint8","name":"sender","type":"uint8"},{"internalType":"string","name":"message","type":"string"},{"internalType":"uint8","name":"grpId","type":"uint8"},{"internalType":"bool","name":"status","type":"bool"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"uint256","name":"","type":"uint256"}],"name":"grpTicketMaster","outputs":[{"internalType":"uint256","name":"tid","type":"uint256"},{"internalType":"uint8","name":"grpId","type":"uint8"}],"stateMutability":"view","type":"function"},{"inputs":[{"internalType":"uint8","name":"num","type":"uint8"},{"internalType":"uint8[]","name":"numbers","type":"uint8[]"}],"name":"isPresent","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"pure","type":"function"},{"inputs":[{"internalType":"uint8","name":"sender","type":"uint8"},{"internalType":"uint8","name":"receiver","type":"uint8"},{"internalType":"uint8[]","name":"numbers","type":"uint8[]"}],"name":"isSameGroup","outputs":[{"internalType":"bool","name":"","type":"bool"}],"stateMutability":"pure","type":"function"},{"inputs":[],"name":"owner","outputs":[{"internalType":"address","name":"","type":"address"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"test","outputs":[],"stateMutability":"nonpayable","type":"function"},{"inputs":[],"name":"transCount","outputs":[{"internalType":"uint256","name":"","type":"uint256"}],"stateMutability":"view","type":"function"},{"inputs":[],"name":"transactionSignature","outputs":[{"internalType":"string","name":"","type":"string"}],"stateMutability":"view","type":"function"}]'

BYTE_CODE = CONFIG_DATA['bytecode']

CONTRACT_FILE = os.path.join(os.getcwd(), "//IoTApp//src//app//api//TESTRPC//SmartContracts//SIoTDSC.sol")


class Blockchain(object):
    def __init__(self, _account):
        self.__sender   = 0
        self.__receiver = 0 
        self.__message = ""
        # self.connect_contract()
        # if(web3.isConnected()):
        #     print("\n[INFO] Blockchain Object Initializing Begins---{}".format(time.ctime(time.time())))

        
        if(_account in list(web3.eth.accounts)):
            print("\n[INFO] Blockchain Object Initializing Begins---{}".format(time.ctime(time.time())))
            
            self.__account = web3.toChecksumAddress(_account)#only lower case
            count = 1
            for account in web3.eth.accounts:
                print("[INFO] Blockchain Account {} : {}".format(count, account))
                count+=1
            self.__participants_accounts = web3.eth.accounts
            web3.eth.defaultAccount = self.__account

            self.__isBCConnected = web3.isConnected()
            self.__contract_address = self.parse_contract_address()
            self.connect_contract()
            
            
            print("[INFO] Blockchain Regis Account : {}".format(_account))
            print("[INFO] Blockchain Object Initializing Emds---{}".format(time.ctime(time.time())))
        else:
            print("[INFO] Blockchain Account {} Doesn't Exist!---{}".format(_account, time.ctime(time.time())))
            sys.exit()
    
    
    def connect_contract(self):
        self.abi = json.loads(ABI_PARAM)
        #read from compile file first
        self.__contract = web3.eth.contract(address=self.__contract_address, abi=self.abi)

        #old code
        # compiled_sol = self.compile_source_file(CONTRACT_FILE)

        # self.contract_id, abi = compiled_sol.popitem()
        # print(abi)
        # self.__contract_address = self.deploy_contract(contract_interface)
        #self.__contract = web3.eth.contract(address=self.__contract_address, abi=abi['abi'])
        # print("[INFO] Blockchain Deployed {} to: {}\n".format(self.contract_id, self.__contract_address)



    def add_node(self, category, grpId, nodeId, groupTicket, private_key):
        """
             #Reference : https://web3py.readthedocs.io/en/stable/examples.html#making-transactions
             Purpose    : Is to sign outgoing transactions to contract and to add nodes to network
             For Event  : I used self.__contract.events.TransactionCompleted().processReceipt(receipt)
        """
        r = 0
        s = 0
       
        msg = ""
        print("[INFO] Blockchain add_node category = {}".format(category))
        msg += self.to_32byte_hex(category)

        print("[INFO] Blockchain add_node grpId = {}".format(grpId))
        msg += self.to_32byte_hex(grpId)

        print("[INFO] Blockchain add_node nodeId = {}".format(nodeId))
        msg += self.to_32byte_hex(nodeId)

        print("[INFO] Blockchain add_node groupTicket = {}".format(groupTicket))
        msg += self.to_32byte_hex(web3.toInt(hexstr=groupTicket))
        print("[INFO] Blockchain add_node groupTicket int = {}".format(web3.toInt(hexstr=groupTicket)))

        message = encode_defunct(text=msg)
        signed_message = web3.eth.account.sign_message(message, private_key=private_key)

        #extract r and s
        event_message =""
        if(signed_message.r and signed_message.s):
            r = signed_message.r
            s = signed_message.s
            v = signed_message.v
            signature = signed_message.signature
            #message_hash = signed_message.messageHash
        

            print("\n[INFO] Blockchain Signed Transaction Information")
            print("[INFO] r : {}".format(r))
            print("[INFO] r : {}".format(r))
            print("[INFO] s : {}".format(v))
            print("[INFO] signature : {}".format(web3.toHex(signature)))
            verified  = web3.eth.account.recover_message(message, signature=signature)
            print("[INFO] verified signature : {}".format(verified))

            nonce = web3.eth.getTransactionCount(self.__account)  
            gas_estimate  = self.__contract.functions.SIoTDSC__AddNode(
                                                                    category, 
                                                                    grpId, 
                                                                    nodeId, 
                                                                    web3.toInt(hexstr=groupTicket), 
                                                                    r,
                                                                    s
                                                                    # signature
                                                                    ).estimateGas()
            if gas_estimate < GAS_LIMIT:
                print("\n[INFO] Sending transaction to SIoTDSC__AddNode(*) Nonce : {}. Gas : {}".format(gas_estimate, nonce ))
                try:
                    tx_hash  = self.__contract.functions.SIoTDSC__AddNode(
                                                                        category, 
                                                                        grpId, 
                                                                        nodeId, 
                                                                        web3.toInt(hexstr=groupTicket), 
                                                                        r, 
                                                                        s
                                                                        # signature
                                                                        ).transact()
                    receipt = web3.eth.waitForTransactionReceipt(tx_hash)
                    if(receipt):
                        print("\n[INFO]  --------- TRANSACTION RECEIPT BEGINS ------------ ")
                        print(receipt)
                        print("[INFO]  --------- TRANSACTION RECEIPT ENDS   -------------- ")
                        if(receipt['logs']):
                            readable_receipt = self.__contract.events.TransactionCompleted().processReceipt(receipt)
                            print("\n[INFO]  --------- TRANSACTION LOGS BEGINS --------------- ")
                            print(readable_receipt)
                            print("[INFO]  --------- TRANSACTION LOGS ENDS   ----------------- ")
                            event_message = readable_receipt[0].args.message
                            print("\n[INFO] Message : {}".format(event_message))

                            print("\n[INFO] Node Registered Successfully. Transaction Receipt Status  : {}".format(receipt['status']))
                except RuntimeError as err:
                    print("\n[ERROR] {}".format(err))
            else:
                print("\n[INFO] Gas cost exceeds 100000 : {}".format(gas_estimate))
        return event_message

	# function SIoTDSC__SendMSG (uint8 sender,
	# 							uint8 receiver,
	# 							string memory msgStr,
	# 							uint256  _grpticket,
	# 						   	uint256 _r,
	# 						   	uint256 _s
	# 							) public AccessControlEnforcer(sender, receiver, _grpticket)
    def send_MSG(self, sender, receiver, _message, grpTicket, private_key):
        r = 0
        s = 0

        if(_message == ""):
            print("\n[DEBUG] Blockchain received an empty message!")
            return 0
        #https://web3py.readthedocs.io/en/stable/web3.eth.account.html
        #https://web3py.readthedocs.io/en/stable/web3.eth.account.html#sign-a-contract-transaction
        #nouce = web3.eth.getBlockTransactionCount(self.__account)#for sending ether

        msg = ""
        print("[INFO] Blockchain send_MSG sender     = {}".format(sender))
        msg += self.to_32byte_hex(sender)

        print("[INFO] Blockchain send_MSG receiver   = {}".format(receiver))
        msg += self.to_32byte_hex(receiver)

        print("[INFO] Blockchain send_MSG _message   = {}".format(_message))
        #uint256_message = self.stringToUnit256(_message)
        msg += _message #self.to_32byte_hex(uint256_message)

        print("[INFO] Blockchain send_MSG grpTicket  = {}".format(grpTicket))
        
        msg += self.to_32byte_hex(web3.toInt(hexstr=grpTicket))
        print("[INFO] Blockchain send_MSG Ticket int = {}".format(web3.toInt(hexstr=grpTicket)))

        message = encode_defunct(text=msg)
        signed_message = web3.eth.account.sign_message(message, private_key=private_key)

        #extract r and s
        event_message = ""
        if(signed_message.r and signed_message.s):
            r = signed_message.r
            s = signed_message.s
            v = signed_message.v
            signature = signed_message.signature
            print("[INFO] Blockchain Signed Transaction Information")
            print("[INFO] r : {}".format(r))
            print("[INFO] r : {}".format(r))
            print("[INFO] s : {}".format(v))
            print("[INFO] signature : {}".format(signature))

            nonce = web3.eth.getTransactionCount(self.__account)  
            gas_estimate  = self.__contract.functions.SIoTDSC__SendMSG(
                                                                        sender, 
                                                                        receiver, 
                                                                        _message,
                                                                        web3.toInt(hexstr=grpTicket), 
                                                                        r, 
                                                                        s
                                                                        ).estimateGas()
            if gas_estimate < GAS_LIMIT:
                print("\n[INFO] Sending transaction to SIoTDSC__SendMSG(from:{},to:{}, msg:{} Nonce : {}. Gas : {}".format(sender, receiver, _message, gas_estimate, nonce ))
                try:
                    tx_hash  = self.__contract.functions.SIoTDSC__SendMSG(
                                                                        sender, 
                                                                        receiver, 
                                                                        _message,
                                                                        web3.toInt(hexstr=grpTicket), 
                                                                        r, 
                                                                        s
                                                                        ).transact()

                                                                            
                    receipt = web3.eth.waitForTransactionReceipt(tx_hash)
                    if(receipt):
                        print("\n[INFO]  --------- TRANSACTION RECEIPT BEGINS ------------ ")
                        print(receipt)
                        print("[INFO]  --------- TRANSACTION RECEIPT ENDS   -------------- ")
                        
                        if(receipt['logs']):
                            readable_receipt = self.__contract.events.TransactionCompleted().processReceipt(receipt)
                            print("\n[INFO]  --------- TRANSACTION LOGS BEGINS --------------- ")
                            print(readable_receipt)
                            print("[INFO]  --------- TRANSACTION LOGS ENDS   ----------------- ")
                            event_message = readable_receipt[0].args.message
                            print("\n[INFO] Message : {}".format(event_message))
                            print("\n[INFO] Node Successfully Sent Message. Transaction Receipt Status  : {}".format(receipt['status']))
                except RuntimeError as err:
                    print("\n[ERROR] {}".format(err))
            else:
                print("\n[INFO] Gas cost exceeds 100000 : {}".format(gas_estimate))
        return event_message

	# function SIoTDSC__ReadMSG (
	# 							uint8 sender,
	# 							uint256  _grpticket,
	# 						   	uint256 _r,
	# 						   	uint256 _s
	# 							) public OnlyConcernedObject(sender, _grpticket)
    def read_MSG(self, sender, grpTicket, private_key):
        r = 0
        s = 0
        #https://web3py.readthedocs.io/en/stable/web3.eth.account.html
        #https://web3py.readthedocs.io/en/stable/web3.eth.account.html#sign-a-contract-transaction
        #nouce = web3.eth.getBlockTransactionCount(self.__account)#for sending ether
        msg = ""
        print("[INFO] Blockchain read_MSG sender          = {}".format(sender))
        msg += self.to_32byte_hex(sender)

        print("[INFO] Blockchain read_MSG groupTicket     = {}".format(grpTicket))
        msg += self.to_32byte_hex(web3.toInt(hexstr=grpTicket))
        print("[INFO] Blockchain read_MSG groupTicket int = {}".format(web3.toInt(hexstr=grpTicket)))


        message = encode_defunct(text=msg)
        signed_message = web3.eth.account.sign_message(message, private_key=private_key)

        #extract r and s
        event_message = ""
        if(signed_message.r and signed_message.s):
            r = signed_message.r
            s = signed_message.s
            v = signed_message.v
            signature = signed_message.signature
            print("[INFO] Blockchain Signed Transaction Information")
            print("[INFO] r : {}".format(r))
            print("[INFO] r : {}".format(r))
            print("[INFO] s : {}".format(v))
            print("[INFO] signature : {}".format(web3.toHex(signature)))

            nonce = web3.eth.getTransactionCount(self.__account)  
            gas_estimate  = self.__contract.functions.SIoTDSC__ReadMSG(
                                                                        sender, 
                                                                        web3.toInt(hexstr=grpTicket), 
                                                                        r, 
                                                                        s
                                                                        ).estimateGas()
            if gas_estimate < GAS_LIMIT:
                print("\n[INFO] Sending transaction to SIoTDSC__ReadMSG(id : {}) Nonce : {}. Gas : {}".format(sender, gas_estimate, nonce ))

                try:
                    tx_hash  = self.__contract.functions.SIoTDSC__ReadMSG(
                                                                        sender, 
                                                                        web3.toInt(hexstr=grpTicket), 
                                                                        r, 
                                                                        s
                                                                        ).transact()
                    receipt = web3.eth.waitForTransactionReceipt(tx_hash)

                    if(receipt):
                        print("\n[INFO]  --------- TRANSACTION RECEIPT BEGINS ------------ ")
                        print(receipt)
                        print("[INFO]  --------- TRANSACTION RECEIPT ENDS   -------------- ")
                        if(receipt['logs']):
                            readable_receipt = self.__contract.events.MessageCreated().processReceipt(receipt)
                            print("\n[INFO]  --------- TRANSACTION LOGS BEGINS --------------- ")
                            print(readable_receipt)
                            print("[INFO]  --------- TRANSACTION LOGS ENDS   ----------------- ")
                            event_message = readable_receipt[0].args.message
                            self.__sender =  readable_receipt[0].args.sender
                            self.__receiver =  readable_receipt[0].args.receiver
                            self.__message  =  event_message
                            print("\n[INFO] Message : {}".format(event_message))
                            print("\n[INFO] Node Successfully Read Message. Transaction Receipt Status  : {}".format(receipt['status']))
                        
                except RuntimeError as err:
                    print("\n[ERROR] {}".format(err))
               
            else:
                print("\n[INFO] Gas cost exceeds 100000 : {}".format(gas_estimate))
        return event_message


	# function SIoTDSC__DeleteRecord(uint8 _id,
	# 								uint256 _grpTicket) public OnlyConcernedObject(_id, _grpTicket)
    def delete_record(self, sender, grpTicket):
            msg = ""
            print("[INFO] Blockchain SIoTDSC__DeleteRecord sender = {}".format(sender))
            msg += self.to_32byte_hex(sender)

            print("[INFO] Blockchain SIoTDSC__DeleteRecord groupTicket = {}".format(grpTicket))
            msg += self.to_32byte_hex(web3.toInt(hexstr=grpTicket))
            print("[INFO] Blockchain SIoTDSC__DeleteRecord groupTicket int = {}".format(web3.toInt(hexstr=grpTicket)))
            
            tx_hash = self.__contract.functions.SIoTDSC__DeleteRecord(sender, web3.toInt(hexstr=grpTicket)).transact()
            receipt = web3.eth.waitForTransactionReceipt(tx_hash)#wait for result
            if(receipt):
                print("\n[INFO]  --------- TRANSACTION RECEIPT BEGINS ------------ ")
                print(receipt)
                print("[INFO]  --------- TRANSACTION RECEIPT ENDS   -------------- ")
                if(receipt['logs']):
                    readable = self.__contract.events.TransactionCompleted().processReceipt(receipt)
                    print("\n[INFO]  --------- TRANSACTION RECEIPT BEGINS ------------ ")
                    print(readable)
                    print("[INFO]  --------- TRANSACTION RECEIPT ENDS   -------------- ")

                    print("\n[INFO] Node Successfully Deleted Records. Transaction Receipt Status  : {}".format(receipt['status']))
            return 


    # ecrecover in Solidity expects v as a native uint8, but r and s as left-padded bytes32
    # Remix / web3.js expect r and s to be encoded to hex
    # This convenience method will do the pad & hex for us:
    # https://web3py.readthedocs.io/en/stable/web3.eth.account.html#verify-a-message
    def to_32byte_hex(self, val):
        return Web3.toHex(Web3.toBytes(val).rjust(32, b'\0'))

    def to_32byte_hex_2(self, val):
        return Web3.toHex(Web3.toBytes(val).rjust(32, b'\0'))

    def string_to_bytes32(self, data):
        if len(data) > 32:
            myBytes32 = data[:32]
        else:
            myBytes32 = data.ljust(32, '0')
        return bytes(myBytes32, 'utf-8')
    
    def stringToUnit256(self, message):
        print("-------------------------------------{}".format(message))
        hex_message = message.encode("utf-8").hex()
        print("-------------------------------------{}".format(hex_message))
        int_message = web3.toInt(hexstr=hex_message)
        print("-------------------------------------{}".format(int_message))
        return int_message


    def uinit256ToString(self, int_message):
        text_message = web3.toText(int_message)
        return text_message

    def decode_string(self, hex_message):
        int_message = web3.toInt(hexstr=hex_message)
        return web3.toText(int_message)

    def encode_string(self, message):
        encode = message.encode("utf-8").hex()
        int_val = web3.toInt(hexstr=encode)
        encode_text = Web3.toBytes(int_val).rjust(32, b'\0')
        return web3.toHex(encode_text)


    def test(self):
        tx_hash = self.__contract.functions.test().transact()
        receipt = web3.eth.waitForTransactionReceipt(tx_hash)#wait for result
        
        return self.__contract.events.TransactionCompleted().processReceipt(receipt)

    def get_contract(self):
        return self.__contract

    def get_account(self):
        return self.__account

    def participants_accounts(self):
        return self.__participants_accounts

    def get_balance(self):
        return web3.eth.getBalance(self.__account)

    def get_connection_status(self):
        return self.__isBCConnected

    def get_contract_address(self):
        return self.__contract_address

    def get_receive_message(self):
        return {"sender":self.__sender, "receiver":self__receiver, "message" : self.__message}

    def parse_contract_address(self):
        filename = os.path.join(parantPath, 'contract.data')
        if(len(CONFIG_DATA['contract_address']) == 42):
            return web3.toChecksumAddress(CONFIG_DATA['contract_address'])
        elif(os.path.isfile(filename)):
            address = ""
            
            f = open(filename,'r')
            lines = f.readlines()
            for line in lines:
                if('SIoTDSC:' in line):
                    address =line.split(":")[1].strip()
                    #print(address)

            f.close()
            if(len(address) != 42):
                raise("Invalid contract address! Check compilation!")
            return web3.toChecksumAddress(address)
        else:
            raise("No contract address found!")

    # def compile_source_file(self, file_path):
    #     with open(file_path, 'r') as f:
    #         source = f.read()
    #     return compile_source(source)

    # def deploy_contract(self, contract_interface):
    #     tx_hash = web3.eth.contract(
    #         abi=contract_interface['abi'],
    #         bytecode=contract_interface['bin']).deploy()

    #     address = w3.eth.getTransactionReceipt(tx_hash)['contractAddress']
    #     return address

    # def add_node2(self, category, grpId, id, groupTicket, private_key):
    #     """Note : this function generates transaction receipts but did not add node
    #                 as a result, i believe it can used to fund accounts only.
    #                 I am keeping it here for further research
    #     """
    #     r = 0
    #     s = 0
    #     #https://web3py.readthedocs.io/en/stable/web3.eth.account.html
    #     #https://web3py.readthedocs.io/en/stable/web3.eth.account.html#sign-a-contract-transaction
    #     #nouce = web3.eth.getBlockTransactionCount(self.__account)#for sending ether
    #     msg = ""
    #     msg += self.to_32byte_hex(category)
    #     msg += self.to_32byte_hex(grpId)
    #     msg += self.to_32byte_hex(id)
    #     msg += groupTicket

    #     message = encode_defunct(text=msg)
    #     signed_message = web3.eth.account.sign_message(message, private_key=private_key)

    #     #extract r and s
    #     if(signed_message.r and signed_message.s):
    #         r = signed_message.r
    #         s = signed_message.s
    #         v = signed_message.v
    #         signature = signed_message.signature
    #         print("[INFO]       Outgoing Message Signed Transaction Information")
    #         print("[INFO] r : {}".format(r))
    #         print("[INFO] r : {}".format(r))
    #         print("[INFO] s : {}".format(v))
    #         print("[INFO] signature : {}".format(signature))

    #         nonce = web3.eth.getTransactionCount(self.__account)  
    #         unicorn_txn = self.__contract.functions.SIoTDSC__AddNode(
    #             category, 
    #             grpId, 
    #             id, 
    #             web3.toInt(hexstr=groupTicket), 
    #             r, 
    #             s
    #             # signature
    #             ).buildTransaction({
    #                                 'chainId': 1,
    #                                 'gas': 700000,
    #                                 'gasPrice': web3.toWei('1', 'gwei'),
    #                                 'nonce': nonce,
    #                                 })
    #         #print(unicorn_txn)
    #         signed_txn = web3.eth.account.sign_transaction(unicorn_txn, private_key=private_key)
    #         # receipt = web3.eth.sendRawTransaction(signed_txn.rawTransaction)
            
    #         signed_tx_hash = web3.toHex(signed_txn.hash)
    #         verified_tx = web3.toHex(web3.keccak(signed_txn.rawTransaction))

    #         # print("[INFO] Add Node  TX Hash  1 : {}".format(signed_tx_hash))
    #         # print("[INFO] Add Node  TX Hash verification  : {}".format(verified_tx))


    #         # When you run sendRawTransaction, you get the same result as the hash of the transaction:
    #         if(verified_tx.upper() == signed_tx_hash.upper()):
    #             print("[INFO] Node Registered Successfully. Transaction Receipt  : {}".format(verified_tx))
    
        #return self.__contract.events.TransactionCompleted().processReceipt(receipt)


    # def add_node_with_verified_signature_example(self, category, grpId, id, groupTicket, private_key):
    #     #TODO : add nonce to prevent against replay attack!
    #     r = 0
    #     s = 0
    #     msg = ""
    #     #https://web3py.readthedocs.io/en/stable/web3.eth.account.html
    #     #https://web3py.readthedocs.io/en/stable/web3.eth.account.html#sign-a-contract-transaction
    #     #nouce = web3.eth.getBlockTransactionCount(self.__account)#for sending ether

    #     #nonce = web3.eth.getTransactionCount(self.__account)  
    #     # Build a transaction that invokes this contract's function, called transfer
    #     #SIoTDSC__AddNode (uint8 _category,
	# 						#    uint8 _grpId,
	# 						#    uint8 _id,
	# 						#    address _grpticket,
	# 						#    uint256 _r,
	# 						#    uint256 _s)
    #     msg = ""
    #     #msg += self.to_32byte_hex(category)
    #     msg += self.to_32byte_hex(grpId)
    #     msg += self.to_32byte_hex(id)
    #     #msg += self.to_32byte_hex(groupTicket) 
    #     message = encode_defunct(text=msg)

    #     signed_message = web3.eth.account.sign_message(message, private_key=private_key)

    #     #extract r and s
    #     if(signed_message.r and signed_message.s):
    #         r = signed_message.r
    #         s = signed_message.s
    #         v = signed_message.v
    #         signature = web3.toHex(signed_message.signature)
    #         print("[INFO] Outgoing Message Signed Transaction Information")
    #         print("[INFO] r : {}".format(r))
    #         print("[INFO] r : {}".format(r))
    #         print("[INFO] s : {}".format(v))
    #         #print("[INFO] signature : {}".format(web3.toHex(signature)))

    #         verify = web3.eth.account.recover_message(message, signature=signed_message.signature)

    #         print("[INFO] Add_node Signature {}".format(signature))
    #         print("[INFO] Add_node Signature Verified :  {}".format(verify))
    #         sig = Web3.toBytes(hexstr=signature)
    #         v, hex_r, hex_s = Web3.toInt(sig[-1]), Web3.toHex(sig[:32]), Web3.toHex(sig[32:64])
