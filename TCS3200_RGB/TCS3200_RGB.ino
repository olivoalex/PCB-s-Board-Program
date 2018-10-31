/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// >---> PRINT A3 PAGE FORMAT | 12 SIZED FONT | MAGINS 10 + 5 + 5+ 5 |
// http://arduino.esp8266.com/stable/package_esp8266com_index.json
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// VERSAO DA PLACA ENVIADO PARA FABRICACAO NA CHINA - CHEGADA 06/10/2018 
// PLACA DE CIRCUITRO IMPRESSO >---> CHINA | VERSAO: 3.0 - REVISAO: 02.09.18
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// IDENTIFICACAO DOS GPIO E ENDERECOS I2C E ADS1115
/* CONECTOR PARAFUSAVEL SUPERIOR
 * CONN_GPIO - ESQUERDA | CONN_I2C - DIREITA
 * GPIO_11              | +V >--> 3V3
 * GPIO_13              | CL >--> SCL - I2C
 * GPIO_14              | DA >--> SDA - I2C
 * GPIO_16              | 0V >--> GND */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// PCI_GPIO  >--> CONECTOR SOLDAVEL ACIMA DO ESP8266
/* GPIO_07              | GPIO_11
 * GPIO_09              | GPIO_13
 * GPIO_10              | GPIO_14
 * GPIO_08              | GPIO_16
 * GPIO_06              | ADC1V_IN */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// ADS_IN >--> CONECTOR SOLDAVEL A ESQUERDA DO ADS1115
/* PRIMEIRO >--> ADS_A0 | SEGUNDO   >--> ADS_A1
 * TERCEIRO >--> ADS_A2 | QUARTO    >--> ADS_A3 */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// GPIO LED ATL
// GPIO_12 >--> LED_DB  | LED_ON  >--> CONECTADO DIRETO NA FONTE - INDICA LIGADO
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// CON4_PROG >--> CONECTOR DIL PARA GRAVACAO DO ESP8266 - GRAVADOR
/* PINO_10 >--> 3V3     | PINO_01  >--> GND  >--> 0 V
 * PINO_09 >--> 3V3     | PINO_02  >--> [18]ESP-GPIO-00
 * PINO_08 >--> 3V3     | PINO_03  >--> [21]ESP-GPIO-03-RXD0
 * PINO_07 >--> 3V3     | PINO_04  >--> [22]ESP-GPIO-01-TXD0
 * PINO_06 >--> 3V3     | PINO_05  >--> [01]ESP-RST /*
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// ENDERECOS DOS ACESSORIOS I2C
/* T/P/U                | 0x76 ou 0x77 >--> BME280 >--> USA-SE O "0x76"
 * LUX                  | 0x39 ou 0x38 >--> VEML6070 "AUTOMATICO" PELO DRIVER ADAFRUIT
 * PRESSAO              | 0x48 >--> ADS1115 FREESCALE OU 0x49, 0x4A, 0x4B*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// "BIBLIOTECAS" QUE DEVEM PERMANECER NA PASTA DESTE CODIGO OU DE SUAS FUTURAS VERSOES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
//#include     "cactus_io_BME280_I2C.h"   // BME280 - I2C
//#include     "ADS1115.h"                // ADS1115 - I2C
//#include     "Adafruit_VEML6070.h"      // SENSOR UV - I2C
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// DEMAIS <BIBLIOTECAS> GENERICAS QUE SAO GERENCIADAS PELA IDE ARDUINO
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
#include     <ESP8266WiFi.h>            // BIBLIOTECA WiFi DO ESP8266
#include     <Wire.h>                   // NECESSARIO PARA COMUNICACAO I2C
#include     <MySQL_Connection.h>       // CONEXAO COM BANCO DE DADOS
#include     <MySQL_Cursor.h>           // CONEXAO COM BANCO DE DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// https://www.factoryforward.com/tcs3200-color-sensor-tutorial-arduino-tcs230/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* TCS3200 Color Sensor Tutorial | Arduino | TCS230
In this tutorial, we will see how to use TCS3200/TCS230 color sensor with Arduino. This color sensor is used in wide range of applications like conveyors, food processing units, paint mixing applications, Vending machines and many more. So let’s see how it works and how to use it with Arduino.
At the center of the sensor, you can able to see the TCS3200 Chip. It consists of 8×8 arrays of photodiodes (Total 64 photodiodes). There is a current to frequency converter that converts the sensed current from photodiode into a square wave frequency. This frequency determines the color of the object in front of TCS3200.
There are 4 White LEDs around the TCS3200 chip is to apply a plain white color on the object and the reflected amount of color is detected by photodiodes.
// TCS230 & 3200 color recognition sensor
// Sensor connection pins to Arduino are shown in comments
Color Sensor      Arduino
-----------      --------
 VCC               5V OR 3V3
 GND               GND
 s0                8
 s1                9
 s2                12
 s3                11
 OUT               10
 OE                GND */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
const int s0 = 8;
const int s1 = 9;
const int s2 = 12;
const int s3 = 11;
const int out = 10;
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// LED pins connected to Arduino
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
int redLed = 2;
int greenLed = 3;
int blueLed = 4;
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// Variables
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
int red = 0;
int green = 0;
int blue = 0;
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
void setup(){
Serial.begin(9600);
pinMode(s0, OUTPUT);
pinMode(s1, OUTPUT);
pinMode(s2, OUTPUT);
pinMode(s3, OUTPUT);
pinMode(out, INPUT);
pinMode(redLed, OUTPUT);
pinMode(greenLed, OUTPUT);
pinMode(blueLed, OUTPUT);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
//Setting frequncy to 100%
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
digitalWrite(s0, HIGH);
digitalWrite(s1, HIGH);}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
void loop(){
color();
Serial.print("R Intensity:");
Serial.print(red, DEC);
Serial.print(" G Intensity: ");
Serial.print(green, DEC);
Serial.print(" B Intensity : ");
Serial.print(blue, DEC);
// Serial.println();
/* This is for common Anode LEDs, Change HIGH and LOW to opposite for Common Cathode*/
if (red < blue && red < green && red < 20){
Serial.println(" - (Red Color)");
digitalWrite(redLed, LOW); // Turn RED LED ON
digitalWrite(greenLed, HIGH);
digitalWrite(blueLed, HIGH);}
else if (blue < red && blue < green){
Serial.println(" - (Blue Color)");
digitalWrite(redLed, HIGH);
digitalWrite(greenLed, HIGH);
digitalWrite(blueLed, LOW); // Turn BLUE LED ON
}
else if (green < red && green < blue){
Serial.println(" - (Green Color)");
digitalWrite(redLed, HIGH);
digitalWrite(greenLed, LOW); // Turn GREEN LED ON
digitalWrite(blueLed, HIGH);}
else{Serial.println();}
delay(300);
digitalWrite(redLed, HIGH);
digitalWrite(greenLed, HIGH);
digitalWrite(blueLed, HIGH);}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
/* Read and Store values in red, green and blue variables */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
void color(){
//s2 and s3 to LOW for reading RED Values
digitalWrite(s2, LOW);
digitalWrite(s3, LOW);
red = pulseIn(out, digitalRead(out) == HIGH ? LOW : HIGH);
//s3 to HIGH (Already s2 in LOW) for BLUE values
digitalWrite(s3, HIGH);
blue = pulseIn(out, digitalRead(out) == HIGH ? LOW : HIGH);
//s2 to HIGH (Already s3 HIGH) for GREEN
digitalWrite(s2, HIGH);
green = pulseIn(out, digitalRead(out) == HIGH ? LOW : HIGH);}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// END
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */
// https://www.factoryforward.com/tcs3200-color-sensor-tutorial-arduino-tcs230/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - */

