/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// SOME DESCRIPTIONS HERE!
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// SENSOR FUNCIONANDO COM ESTE CODIGO - 31/05/2018
/* This is a simple code to test BH1750FVI Light senosr
  communicate using I2C Protocol 
  this library enable 2 slave device address
  Main address  0x23 
  secondary address 0x5C 
  Written By : Mohannad Rawashdeh  */
 /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
 // First define the library
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#include <Wire.h>
#include <BH1750FVI.h>
//https://github.com/Genotronex/BH1750FVI_Master
// ATENCAO VEM DENTRO DE UMA SUBPASTA - TEM QUE TRAZER PARA UMA ANTES >--> 0K?
BH1750FVI LightSensor;
#define BAUD_RATE 115200
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// SECOND DEFINE GLOBAL VARIABLES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
uint16_t lux = 0; //  LUX INTENSITY LONG INTEGER
int TIME_BETWEEN_MEAS = 6000; //  TIME BETWEEN MEASUREMENTS
// TO PRODUCTION SYSTEMS DO NOT FORGET TO REMOVE ALL DELAY TIMES!!! 0K?
// AND TO REMOVE ALL SERIAL PRINTS THAT DECREASE I2C RELIABILITY!!! 0K?
unsigned COUNT = 0; // just a single measurements counter here!
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// SETUP FUNCTION
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void setup() {   // put your setup code here, to run once:
Serial.begin(BAUD_RATE);  
LightSensor.begin();
/*  Set the address for this sensor you can use 2 different address
 Device_Address_H "0x5C"  >---> Device_Address_L "0x23"
 you must connect Addr pin to A3.  */
//  LightSensor.SetAddress(Device_Address_H);//Address 0x5C
// To adjust the slave on other address , uncomment this line
LightSensor.SetAddress(Device_Address_L); //Address 0x23
/*  set the Working Mode for this sensor 
    Select the following Mode:
    Continuous_H_resolution_Mode
    Continuous_H_resolution_Mode2
    Continuous_L_resolution_Mode
    OneTime_H_resolution_Mode
    OneTime_H_resolution_Mode2
    OneTime_L_resolution_Mode
    The data sheet recommanded To use Continuous_H_resolution_Mode  */

  LightSensor.SetMode(Continuous_H_resolution_Mode);
  Serial.println("|> 0K It's running...");
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// LUX READINGS FUNCTION
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
uint16_t ReadLUX(){
lux = LightSensor.GetLightIntensity();// Get Lux value
delay(TIME_BETWEEN_MEAS);
return (lux);
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// MAIN LOOP FUNCTION
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void loop() {
ReadLUX();
Serial.println("|-----------------------------------------------------|");
Serial.print("|> Light: "); Serial.print(lux); Serial.println(" lux");
Serial.print("|> MEASUREMENT NUMBER: "); Serial.print(COUNT++);
Serial.print(" each one after: "); Serial.print(TIME_BETWEEN_MEAS/1000);
Serial.println(" seconds"); 
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FINAL
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/

