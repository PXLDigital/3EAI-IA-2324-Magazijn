import time
from opcua import Client

url = "opc.tcp://PF2ADX8V.STUD.PXL.LOCAL:53530/OPCUA/SimulationServer"

client= Client(url)

client.connect()
print("Client Connected")

while True:
    Temp = client.get_node("ns=2;i=2")
    Temperature = Temp.get_value()
    print(Temperature)

    Press = client.get_node("ns=2;i=3")
    Pressure = Press.get_value()
    print(Pressure)

    TIME = client.get_node("ns=2;i=4")
    Time = TIME.get_value()
    print(Time)

    time.sleep(1)