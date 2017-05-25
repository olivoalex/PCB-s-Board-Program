/* Ben Shorey ©2016
http://www.instructables.com/member/BenS226/
CGI script for WebGPIO
*/
#include <stdio.h>    // required for printf
#include <stdlib.h>   // required for atoi
#include <wiringPi.h> // required for GPIO control

int main (int argc, char* argv[])
{
  // if an argument given return status of that pin
  if ( argc == 2 ) {
    int pin = atoi(argv[1]);
    //set up wiringPi
    wiringPiSetupGpio();
    // output information about pin
    printf("Pin state for pin:\n%d\n", pin);
    printf("Mode: \n%d\n", getAlt(pin));
    printf("Value: \n%d\n", digitalRead(pin));  
  } else {
    // if no argument given then return information about all pins as:
    // (Pin_values) # # # # # .... # (Pin_modes) # # # # .... #
    wiringPiSetupGpio();
    printf("(Pin_states) ");
    int n;
    for (n = 0; n < 28; n++) {
      printf("%d ", digitalRead(n));
    }
    printf("(Pin_modes) ");
    for (n = 0; n < 28; n++) {
      printf("%d ", getAlt(n));
      // getAlt(pin) returns pin mode as an integer
      // corresponds to the following modes (from experimenting using GPIO)
      // 0 = "IN",1 = "OUT",2 = "ALT5",3 = "ALT4",4 = "ALT0",5 = "ALT1",6 = "ALT2",7 = "ALT3"
    }
  }
  return 0;
}