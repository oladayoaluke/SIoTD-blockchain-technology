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

#TEST STATUS
Passed

#COMMANDS
python siotd.py "A" "1" "2,3" "1" "1" > SScenario1/siotda.data
python siotd.py "A" "2" "1" "1" "0" > SScenario1/siotdb.data

#INTENT : 
The intent of this scenario is to demonstrate that two devices in the same group can
communicate successfully. In this scenation, device id 1 was able to talk to device id 2.
As a result, they could blink each other LED.
