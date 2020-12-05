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
2

#TEST STATUS
Passed

#COMMANDS
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


