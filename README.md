# :boat: RFID Boat Information 
This project includes hardware and software components that demonstrates the speed, accessibility, and production value of RFID technology for Brunswick. This project will provide a solid foundation and example of how Brunswick could in corporate this technology in the manufacturing or engineering lines.

## :question: Problem Statement
Brunswick would like an easy, fast, and durable way of reading information about a boat without having to manually type in hull id numbers, etc. The current manufacturing and repairing process for boats that come in or boats that are processed is not as quick or painless as Brunswick would like. While RFID technology has been explored in the past, Brunswick has not posessed the technical ability to completely work with this technology to its full extent. When using this technology, Brunswick would like to be able to create a chip that can withstand extremely high temperatures. 

## :exclamation: Solution Statement
Scanning RFID chips containing information about a boat will allow for a more automated process and better communication between front end hardware and backend. The cost of each chip varies, however, buying items in bulk would allow the chip to cost anywhere from [less than 0.25c per chip](https://www.barcodegiant.com/zebra/part-10002055r.htm?aw&adtype=pla&utm_medium=pla&utm_campaign=PLA_Topaz&gclid=EAIaIQobChMIjbqXvpHx4wIVDJ-fCh0StQznEAQYBCABEgIvAPD_BwE&gclsrc=aw.ds) or in [this example](https://www.barcodegiant.com/zebra/part-10010028-r.htm?aw&adtype=pla&utm_medium=pla&utm_campaign=PLA_Topaz&gclid=EAIaIQobChMIjbqXvpHx4wIVDJ-fCh0StQznEAQYAiABEgJY2vD_BwE&gclsrc=aw.ds), it costs about 0.0075 (less than a cent) per chip. The cost ranges pending on the frequency or the RFID chip and the quality.

## :computer: Source Breakdown
The source is separated in two parts, where the hardware includes an Arduino, Ethernet Sheild (for now), and RFID sheild which can read RFID tags simultaneously. The communication between the two parts is singular meaning the hardware sends data to the web server, however, the web server does not send anything back to the hardware.

### :spider_web: Web Server
The web server is hosted and a live demo can be found [here](http://159.89.237.82/rfid/). The web page features a begin button. When clicked, the web server will begin to search for and wait for communication from the hardware in which a tag must be scanned. Once a tag is scanned, the web page updates itself with informaton about the boat that is connected to the tag that is scanned.

The backend portion of the web server is written in PHP. It features two plugins, created by Maxwell Newberry. One is sent GET requests from the main web page, `plugin_check.php`, which checks the SQL database to see if an RFID tag has been scanned within a 3 second (+/-) range. The other page, `/post/`, is the endpoint which the hardware sends data to. It receives the RFID tag EPC and logs the EPC with a timestamp.

### :wrench: Hardware
The hardware source code is written in Arduino-C. The reader will continously looking for tags - once a tag is scanned, the EPC is generated and sent to the server using JSON and a HTTP POST request (*see information about how server handles this request above*).

## :page_with_curl: TO DO
1. Fix Arduino Code to adjust for memory consumption leaks
2. Disconnect Arduino through USB and connect external battery source
3. Unsolder connection to allow RFID reader board to read chips and now external antenna
4. Replace Redboard with Wifi capable Arudino
5. Create casing that will still work with RFID reader
