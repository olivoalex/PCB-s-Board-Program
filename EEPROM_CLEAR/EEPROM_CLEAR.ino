/*
 * EEPROM Clear
 *
 * Sets all of the bytes of the EEPROM to 0.
 * This example code is in the public domain.

 */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// AGROTECHLINK MINI ESTACAO CLIMATICA - PINOUTS - DEFINES - DESCRICOES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#define      ATL3         16    // GPIO-16 + LED0
#define      ATL4         15    // GPIO-15 + ESTADO NORMAL DO ESP / PERMITE ROTINAS E RESTART
#define      ATL7          4    // GPIO-04 + RELE 1 / A 
#define      ATL8          5    // GPIO-05 + RELE 2 / B
#define      ATL9          2    // GPIO-02 + LED NATIVO DO ESP8266 / PERMITE ROTINAS E RESTART
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#include <EEPROM.h>
void setup(){
  EEPROM.begin(512);
// write a 0 to all 512 bytes of the EEPROM
  for (int i = 0; i < 512; i++)
    EEPROM.write(i, 0);
// turn the LED on when we're done
  pinMode(ATL3, OUTPUT);
  digitalWrite(ATL3, LOW);
  EEPROM.end();}
void loop(){}
