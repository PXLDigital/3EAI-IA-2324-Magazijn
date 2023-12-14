import pymysql.cursors
import time
from opcua import Client

# Connect to the database
connection = pymysql.connect(host='192.168.1.220',
                             user='Bo',
                             password='Bo',
                             database='magazijnDatabase',
                             cursorclass=pymysql.cursors.DictCursor)

try:
    # Create a cursor object to interact with the database
    with connection.cursor() as cursor:
        # Execute a SQL query to select data from a table
        products = 'SELECT * FROM product'
        cursor.execute(products)
        # Fetch all the rows from the result set
        rowsProducts = cursor.fetchall()

        # Print the results
        for rowProduct in rowsProducts:
            print(rowProduct)

        racks = 'SELECT * FROM Rek'
        cursor.execute(racks)
        rowsRacks = cursor.fetchall()
        # Print the results
        for rowRack in rowsRacks:
            print(rowRack)
finally:
    # Close the database connection when done
    connection.close()

url = "opc.tcp://PF2ADX8V:4840/OPCUA/SimulationServer"

client= Client(url)

client.connect()
print("Client Connected")

while True:


    TIME = client.get_node("ns=3;s=RekID")
    Time = TIME.set_value(rowProduct["ProductID"])
    #print(Time)

    time.sleep(1)
