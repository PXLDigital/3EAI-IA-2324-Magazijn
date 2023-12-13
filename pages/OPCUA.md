



OPC-UA




---


* OPC UA Integration
OPC UA (Object Linking and Embedding for Process Control Unified Architecture) is used for seamless communication between different components in the smart stock system.


OPC UA supports two communication paradigms: Publish-Subscribe (Pub/Sub) and Client-Server.


**Components Using OPC UA:**

+ Import, Picker, Rek, Auto Restock, Production Hal
These components act as OPC UA Clients or Servers to exchange information about product movement, orders, and processing requests.

+ Local DB, Storage DB, Order DB
These databases implement OPC UA Servers to provide and receive data related to product storage, orders, and inventory.

+ Website
The website integrates an OPC UA client to interact with the databases, providing real-time information on stored products, manual export options, and order placement.


OPC UA MOM (Message Oriented Middleware) facilitates the Pub/Sub functionality, enabling efficient and scalable communication.





---


The storage can be simulated using the XYZ-table, with the head moving between Import, Storage, and Export locations, controlled by OPC UA commands.


For databases, MariaDB is used on Raspberry Pi instances, each serving as an OPC UA Server. The website, hosted on local host, interacts with these databases through OPC UA.


With OPC UA facilitating communication, the system achieves interoperability, real-time data exchange, and efficient control of product flow within the smart stock environment.




