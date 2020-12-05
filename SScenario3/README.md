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


