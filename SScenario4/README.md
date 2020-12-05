|-----------------------------------------------------------------|
|-----------------------------------------------------------------|
|--------------------- IOT SIMULATION DEVICE----------------------|
|-----------------------------------------------------------------|
|-----------------------------------------------------------------|
[ARG 1] GROUP NAME      : "A"  #For example, A or B
[ARG 2] FROM NODE ID    : "1"  #For example, 1, 2,3, or 4
[ARG 3] TO NODE ID      : "2"  #For example, 2, or 2,3,4 for multiple
[ARG 4] CATEGORY ID     : "1"  #For example, 1
[ARG 5] MASTER FLAG     : "0"  #For example, 0 for slave 1 for master

#SCENARIO 
3

#TEST STATUS
Passed

#COMMANDS
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

