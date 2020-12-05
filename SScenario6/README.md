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
6

#TEST STATUS
Passed

#COMMANDS
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


