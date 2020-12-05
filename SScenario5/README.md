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
5

#TEST STATUS
Passed

#COMMANDS
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


