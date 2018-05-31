/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// SOME DESCRIPTIONS HERE!
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/*  FUNCIONANDO 0K >-->  DADOS PARA CALIBRACAO ONLINE >--> OLIVO
 *  MEDIDAS COM LAMPADA LED DE 20 W A 3 CENTIMETROS DE DISTANCIA 
 *  >--> APRESENTARAM COMO RESULTADO ENTRE 15670 A 16573 CONTAGENS E 
 *  TENSOES ENTRE 2,92 A 3,11 V
 *  DENTRO DA GAVETA AS 12 HORAS COM ILUMINACAO EXTERNA EM PENUMBRA
 *  >--> APRESENTARAM COMO RESULTADO ENTRE 13 A 50 CONTAGENS E 
 *  TENSOES ENTRE 0,002435 A 0,009375 V. */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// INCLUDES AND DEFINES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#include <Wire.h>
#include <Adafruit_ADS1015.h>
#define BAUD_RATE 115200
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// LIBRARY PARAMETERS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
Adafruit_ADS1115 ads(0x48); // TEXAS INSTRUMENTS ADS1115 4 MULTIPLEXED I2C ADC ADDRESS
// https://github.com/adafruit/Adafruit_ADS1X15
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// VARIABLES DEFINITIONS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
float VA0, VA1, VA2, VA3 = 0.0;
int16_t ADC0, ADC1, ADC2, ADC3;  // we read from the ADC, we have a sixteen bit integer as a result
// ADC0 = LDR1, ADC1 = LDR2, ADC2 = LDR3, ADC3 = LDR4 >---> TO DO NOT FORGET
int ADC_CONV_TIME = 5; // adc conversion time in miliseconds  
int TIME_BETWEEN_MEAS = 6000; //  TIME BETWEEN MEASUREMENTS
// TO PRODUCTION SYSTEMS DO NOT FORGET TO REMOVE ALL DELAY TIMES!!! 0K?
// AND TO REMOVE ALL SERIAL PRINTS THAT DECREASE I2C RELIABILITY!!! 0K?
unsigned COUNT = 0; // just a single measurements counter here!
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// SETUP FUNCTION
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void setup(void){
  Serial.begin(BAUD_RATE);  
  ads.begin();
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// READ ALL MULTIPLEXED ADC FUNCTION
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
float ReadAllADC(){
  ADC0 = ads.readADC_SingleEnded(0);
  delay(ADC_CONV_TIME);
    ADC1 = ads.readADC_SingleEnded(1);
    delay(ADC_CONV_TIME);
      ADC2 = ads.readADC_SingleEnded(2);
      delay(ADC_CONV_TIME);
        ADC3 = ads.readADC_SingleEnded(3);
        delay(ADC_CONV_TIME);
  VA0 = (ADC0 * 0.1875)/1000;
    VA1 = (ADC1 * 0.1875)/1000;
      VA2 = (ADC2 * 0.1875)/1000;
        VA3 = (ADC3 * 0.1875)/1000;
delay(TIME_BETWEEN_MEAS);
return(ADC0, ADC1, ADC2, ADC3);}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// MAIN LOOP FUNCTION
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void loop(void){
ReadAllADC();
Serial.println("|-----------------------------------------------------|");
Serial.print("|> AIN0 >--> LDR1: "); Serial.print(ADC0); 
  Serial.print("\tVA0 >--> V_LDR1: "); Serial.println(VA0, 7);  
    Serial.print("|> AIN1 >--> LDR2: "); Serial.print(ADC1); 
    Serial.print("\tVA1 >--> V_LDR2: "); Serial.println(VA1, 7);  
      Serial.print("|> AIN2 >--> LDR3: "); Serial.print(ADC2); 
      Serial.print("\tVA2 >--> V_LDR3: "); Serial.println(VA2, 7);  
        Serial.print("|> AIN3 >--> LDR4: "); Serial.print(ADC3); 
        Serial.print("\tVA3 >--> V_LDR4: "); Serial.println(VA3, 7);  
Serial.print("|> MEASUREMENT NUMBER: "); Serial.print(COUNT++);
Serial.print(" each one after: "); Serial.print(TIME_BETWEEN_MEAS/1000);
Serial.println(" seconds"); 
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// END OF CODE!
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/

