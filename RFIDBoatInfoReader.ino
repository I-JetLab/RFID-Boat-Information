/*
** Author(s): Arnav Sarin, Maxwell Newberry, Alex Province
** Date Modified: 08/07/2019
** Brunswick i-Jet (c) 2019
*/
 
// Includes
#include <SoftwareSerial.h>
#include <Ethernet.h>
#include <SPI.h>
#include <ArduinoJson.h>
#include "SparkFun_UHF_RFID_Reader.h"

// Define
#define BUZZER1 9
#define BUZZER2 10
#define delayMillis 30000UL

// Variables
unsigned long thisMillis = 0;
unsigned long lastMillis = 0;
byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
char server[] = "159.89.237.82";


// Ethernet setup
IPAddress ip(192, 168, 0, 177);
EthernetClient client;
SoftwareSerial softSerial(2, 3); //RX, TX
RFID nano;

void setup() {

  // Set up at 9600 Baud
  Serial.begin(9600);

  // Check ethernet connection
  if (Ethernet.begin(mac) == 0) {
    while (true);
  } else {
    Serial.println(Ethernet.localIP());
  }

  // Set buzzer outputs
  pinMode(BUZZER1, OUTPUT);
  pinMode(BUZZER2, OUTPUT);
  digitalWrite(BUZZER2, LOW);

  // Init
  while (!Serial);
  Serial.println();
  Serial.println("Initializing...");

  // Check module connection
  if (setupNano(38400) == false)
  {
    Serial.println("Module failed to respond. Please check wiring.");
    while (1); //Freeze!
  }

  // Max out the nano to at 500dBm
  nano.setRegion(REGION_NORTHAMERICA); //Set to North America
  nano.setReadPower(500);
  
}

void loop() {
  
  Ethernet.maintain();
  byte responseType = 0;
  byte myEPC[12]; //Most EPCs are 12 bytes
  byte myEPClength;
  String stringEPC = "";

  Serial.println(F("Scan a tag to set information for..."));

  while (responseType != RESPONSE_SUCCESS)
  {
    myEPClength = sizeof(myEPC); //Length of EPC is modified each time .readTagEPC is called
    responseType = nano.readTagEPC(myEPC, myEPClength, 500); //Scan for a new tag up to 500ms
  }

//  tone(BUZZER1, 2093, 150); //C
//  delay(150);
//  tone(BUZZER1, 2349, 150); //D
//  delay(150);
//  tone(BUZZER1, 2637, 150); //E
//  delay(150);

  // Print out the EPC and set value to stringEPC variable
  for (byte x = 0 ; x < myEPClength ; x++)
  {
    if (myEPC[x] < 0x10) {
      stringEPC = stringEPC + "0";
    }
    stringEPC = stringEPC + myEPC[x] + " ";
  }
  Serial.println("Setting information to: " + stringEPC);

  // Send EPC read to the database
  delay(1000);
  Serial.println(stringEPC);
  insertToDb(stringEPC);
}

void insertToDb(String value) {

  // Variable Setup
  int inChar;
  const int capacity = JSON_OBJECT_SIZE(11);
  StaticJsonDocument<capacity> doc;
  JsonObject root = doc.to<JsonObject>();

  // Set passed EPC to JSONObject
  root["epc"] = value;

  // POST request
  Serial.println("***************************************************************");
  Serial.println("Checking connection...");
  if (client.connect(server, 80)) {
    Serial.println("Successfully connected to " + String(server));
    client.println("POST /rfid/post/ HTTP/1.1");
    Serial.println("POST /rfid/post/ HTTP/1.1");
    client.print("Host: ");
    client.println(server);
    client.println("User-Agent: Arduino/1.0");
    client.println("Connection: close");
    client.println("Content-Type: application/x-www-form-urlencoded;");
    client.print("Content-Length: ");
    client.println(measureJson(root));
    client.println();
    serializeJson(root, client);
    Serial.println("POST Request completed, the following is response information:");
    Serial.println("***************************************************************");
  } else {
    Serial.println("Connection Failed.");
  }

  int connectLoop = 0;

  while(client.connected())
  {
    while(client.available())
    {
      inChar = client.read();
      Serial.write(inChar);
      connectLoop = 0;
    }

    delay(1);
    connectLoop++;
    if(connectLoop > 10000)
    {
      Serial.println();
      Serial.println(F("Timeout"));
      client.stop();
    }
  }
  Serial.println();
  Serial.println();
  Serial.println("***************************************************************");

  Serial.println();
  Serial.println(F("Disconnecting from server..."));
  Serial.println();
  
  Serial.println("***************************************************************");
  client.stop();
}



//Gracefully handles a reader that is already configured and already reading continuously
//Because Stream does not have a .begin() we have to do this outside the library
boolean setupNano(long baudRate)
{
  nano.begin(softSerial); //Tell the library to communicate over software serial port

  //Test to see if we are already connected to a module
  //This would be the case if the Arduino has been reprogrammed and the module has stayed powered
  softSerial.begin(baudRate); //For this test, assume module is already at our desired baud rate
  while (!softSerial); //Wait for port to open

  //About 200ms from power on the module will send its firmware version at 115200. We need to ignore this.
  while (softSerial.available()) softSerial.read();

  nano.getVersion();

  if (nano.msg[0] == ERROR_WRONG_OPCODE_RESPONSE)
  {
    //This happens if the baud rate is correct but the module is doing a ccontinuous read
    nano.stopReading();

    Serial.println(F("Module continuously reading. Asking it to stop..."));

    delay(1500);
  }
  else
  {
    //The module did not respond so assume it's just been powered on and communicating at 115200bps
    softSerial.begin(115200); //Start software serial at 115200

    nano.setBaud(baudRate); //Tell the module to go to the chosen baud rate. Ignore the response msg

    softSerial.begin(baudRate); //Start the software serial port, this time at user's chosen baud rate
  }

  //Test the connection
  nano.getVersion();
  if (nano.msg[0] != ALL_GOOD) return (false); //Something is not right

  //The M6E has these settings no matter what
  nano.setTagProtocol(); //Set protocol to GEN2

  nano.setAntennaPort(); //Set TX/RX antenna ports to 1

  return (true); //We are ready to rock
}
