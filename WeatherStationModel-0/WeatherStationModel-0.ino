/*                    VERSÃO FINAL DO CODIGO                    
                VERSÃO 1.0.0      DATA: 12/01/2017
                   HORA-ULTIMO UPDATE: 1:29 a.m
                COMPILADO NA VERSAO ARDUINO: ....
                __________________________________

                VERSAO DO PROGRAMA ARDUINO: 1.8.1
                PROGRAMA ATUALIZADO EM: 12/01/2017
                __________________________________
                
                 PLACA WIFI ESP8266-07 AT THINKER
                 PROGRAMA: MINI ESTACAO CLIMATICA
                 CONTÉM SENSORES: BMP-180 E DHT22
                __________________________________  

                CONFIGURACAO DA PLACA PARA GRAVACAO
                PLACA: GENERIC ESP8266 MODULE
                FLASH MODE: DIO
                FLASH FREQUENCY: 40MHz
                CPU FREQUENCY: 80MHz
                FLASH SIZE: 1M (512K SPIFFS)
                DEBUG PORT: SERIAL
                DEBUG LEVEL: OTA + UPDATER
                RESET MOTHOD: ck
                UPLOAD SPEED: 115200
                PORTA: PORTA ESP CONECTADA AO COMPUTADOR
                _________________________________              
*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// AGROTECHLINK.COM - ESP8266 - PROGRAM HEADER TEMPLATE - 2017 - JANUARY
//                    TODOS OS DIREITOS RESERVADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/*   CADA GPIO POSSUI UMA IDENTIFICACAO ESPECIFICA
     PORTAS UTILIZADAS NAS PLACAS DA MINI ESTACAO CLIMATICA
     
     ATL3     >--> GPIO-16 + LED0
     ATL4     >--> GPIO-14 + BUZZER
     ATL5     >--> GPIO-12 + SENSOR DHT22 (TEMPERATURA-HUMIDADE)
     ATL7     >--> GPIO-05 + SCL >--> PULLUP INTERNO / SENSOR BMP-180 (PRESSAO)
     ATL8     >--> GPIO-04 + SDA >--> PULLUP INTERNO / SENSOR BMP-180 (PRESSAO)
     /RST     >--> ---[10k]--+3V3  -//- JUMPER COM 0V P/ "RESET"
                   +++[103]---CERAMIC CAPACITOR
     CH-PD    >--> ---[10k]--+3V3 + 103 CERAMIC CAPACITOR
                   +++[103]---CERAMIC CAPACITOR
     GPIO-02  >--> ---[10k]--+3V3
     GPIO-00  >--> ---[10k]--+3V3  -//- JUMPER COM 0V P/ "FLASH"
     GPIO-15  >--> ---[10k]---0V
     RX + TX    >--> CONEXOES PARA GRAVADOR EXTERNO
     PROCEDIMENTO PARA GRAVACAO COM GRAVADOR FTDI
     FTDI-TX  >--> ATL-RX
     FTDI-RX  >--> ATL-TX
     FTDI-3V3 >--> ATL-3V3
     FTDI-0V  >--> ATL-0V
     
     NUNCA ALIMENTAR ESTE MODULO DIRETAMENTE PELO GRAVADOR
     OU USB DO COMPUTADOR!  
*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// LIVRARIAS EXTERNAS PARA FUNCIONAMENTO DOS SENSORES, CONEXÃO E DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#include     <SFE_BMP180.h>        // SENSOR BMP-180 (PRESSAO) 
#include     <Wire.h>              // NECESSÁRIO PARA COMUNICACAO I2C (PRESSAO)
#include     "DHT.h"               // SENSOR DHT22 OU DHT11 (TEMPERATURA-HUMIDADE)
#include     <ESP8266WiFi.h>       // BIBLIOTECA WiFi DO ESP8266
#include     <WiFiClient.h>        // BIBLIOTECA WiFi DO ESP8266
#include     <MySQL_Connection.h>  // CONEXAO COM BANCO DE DADOS
#include     <MySQL_Cursor.h>      // CONEXAO COM BANCO DE DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// AGROTECHLINK MINI ESTACAO CLIMATICA - PINOUTS - DEFINES - DESCRICOES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#define      LED_BUILTIN   2    // LED_BUILTIN (LED NATIVO DO ESP8266)
//#define      ATL2         A0    // ADC
#define      ATL3         16    // GPIO-16 + LED0
#define      ATL4         14    // GPIO-14 + BUZZER
#define      ATL5         12    // GPIO-12 + SENSOR DHT22 (TEMPERATURA-HUMIDADE)
#define      ATL7          5    // GPIO-05 + SCL >--> PULLUP INTERNO / SENSOR BMP-180 (PRESSAO)
#define      ATL8          4    // GPIO-04 + SDA >--> PULLUP INTERNO / SENSOR BMP-180 (PRESSAO)
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// SENSOR PINS SETTINGS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#define      DHTPIN     ATL5         // SENSOR DHT22 (TEMPERATURA-HUMIDADE)
#define      DHTTYPE    DHT22        // ESPECIFICACAO DO SENSOR UTILIZADO
/*#define      DHTTYPE    DHT11*/    // DESMARCAR ESTE DEFINE CASO UTILIZAR O DHT11 E SUBLINHAR DHT22!!!
#define      ALTITUDE   4.5          // ALTITUDE DA LOCALIZACAO DO ESP8266: JARAGUÁ DO SUL - SC (EM METROS - USAR PONTO AO INVES DA VIRGULA. Ex.:(23.3))
DHT          dht (DHTPIN, DHTTYPE);  // ENDERECAMENTO DO SENSOR DHT22 
SFE_BMP180   pressao;                // DEFINICAO DO SENSOR BMP-180
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// DEFINICAO DAS VARIAVEIS GLOBAIS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
double      baseline, P_bmp, T_bmp;  // VARIAVEIS PARA O SENSOR BMP-180
float       T_dht, U_dht;            // VARIAVEIS PARA O SENSOR DHT22 OU DHT11 (CASO HABILITADO)
unsigned    T = 0;                   // DELETE AFTER TESTS ARE DONE
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// CONFIGURACOES WiFi / INTERNET E BANCO DE DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
IPAddress   server_addr (186, 202, 127, 122);   // IP DO MySQL SERVER - SITE AGROTECHLINK.COM
char        user[] = "agrotech_u_intel";        // USUARIO DO BANCO DE DADOS
char        password[] = "OlvAgrotechlink1357"; // SENHA DO USUARIO
// TEMPORARIO CODIGO INTERNET. VAI SER SUBSTITUIDO PELA BIBLIOTECA WIFI.
 char        ssid[] = "GVT-A0F8"; // your SSID
 char        pass[] = "N1B9024257"; // your SSID Password
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
WiFiClient client;
MySQL_Connection conn((Client *)&client);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// LED BLINK FUNCTION
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void LedATLblinks(unsigned M) {
  Serial.print("\nJUST LED0 IS BLINKING N TIMES: ");    Serial.println(M);
  for (short j = 0; j < M; j++) {
    Serial.print(j + 1);  Serial.print(" . ");
    digitalWrite(ATL3, HIGH);                             delay(100);
    digitalWrite(ATL3, LOW);                              delay(2500);}
  digitalWrite(ATL3, LOW);
  digitalWrite(LED_BUILTIN, LOW);                         delay(100);
  digitalWrite(LED_BUILTIN, HIGH);
  Serial.print("\n| - - - - - - BUILTIN LED BLINKS! - - - - - - - - |");
Serial.println("\n| - - - - - - - - - - - - - - - - - - - - - - - - |");}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// PRESSAO E TEMPERATURA DO BMP180
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
float getPressure() {
  char status;
  status = pressao.startTemperature();
  if (status != 0)  {
    delay(status);
    status = pressao.getTemperature(T_bmp);
    if (status != 0)    {
      status = pressao.startPressure(3);
      if (status != 0)      {
        delay(status);
        status = pressao.getPressure(P_bmp, T_bmp);
        if (status != 0) {
          return (P_bmp, T_bmp);}
        else Serial.print("\n>--> Erro ajustando - P - ESPECIAL!");}
      else Serial.print("\n>--> Erro na leitura - P - ESPECIAL!");}
    else Serial.print("\n>--> Erro ajustando temperatura BMP - ESPECIAL!");}
  else Serial.print("\n>--> Erro na leitura da temperatura BMP - ESPECIAL!");}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// GET BMPP180 VALUES FUNCTION
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void GetATLbmpPT() { // SENSOR BMP180
  getPressure();
  if (isnan(P_bmp)) {
    delay(500);
    getPressure();
    if (isnan(P_bmp)) {
      Serial.println("\n>--> Falha na leitura - p - do sensor BMP180!");
      return;}}
  if (isnan(T_bmp)) {
    delay(500);
    getPressure();
    if (isnan(T_bmp)) {
      Serial.println("\n>--> Falha na leitura - T - do sensor BMP180!");
      return;}}
  Serial.print("\n| Pressao atmosferica......BMP: ");
  Serial.print(P_bmp, 2);
  Serial.print(" hPa");
  Serial.println(" - - - |");
  Serial.print("| Temperatura ambiente.....BMP: ");
  Serial.print(T_bmp, 2);
  Serial.print(" *C");
  Serial.println("  - - - - |");
  Serial.print("\n| - - - - - - - - - - - - - - - - - - - - - - - - |");
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// GET DHT TEMPERATURE AND HUMIDIYT VALUES FUNCTION
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void GetATLdhtTU() { // SENSOR AM2302
  U_dht = dht.readHumidity();
  T_dht = dht.readTemperature();
  if (isnan(U_dht) || isnan(T_dht)) {
    delay(2000);
// WHEN DHT22 IS USED SET THIS TO 2000, OTHERWISE SET TO 5000
    U_dht = dht.readHumidity();
    T_dht = dht.readTemperature();
    if (isnan(U_dht) || isnan(T_dht)) {
      Serial.println("\n>--> Falha na leitura do Sensor DHT!");
      return;}}
  Serial.print("\n| Temperatura ambiente.....DHT: ");
  Serial.print(T_dht);
  Serial.print(" *C");
  Serial.println("  - - - - |");
  Serial.print("| Umidade relativa do ar...DHT: ");
  Serial.print(U_dht);
  Serial.print(" %UR");
  Serial.println(" - - - - |");
  Serial.print("\n| - - - - - - - - - - - - - - - - - - - - - - - - |");}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void setup() {                         // COMMENT - UNCOMMENT AS NECESSARY
  Serial.begin(115200);
  pinMode(LED_BUILTIN, OUTPUT);       // Initialize the LED_BUILTIN - output
  digitalWrite(LED_BUILTIN, HIGH);      // turn LED OFF - acive low on ESP07
  pinMode(ATL3, OUTPUT);      digitalWrite(ATL3, LOW);  // GPIO-16 + LED0
  pinMode(ATL4, OUTPUT);      digitalWrite(ATL4, LOW);  // GPIO-14 + BUZZER
  /*----------------------------------------------------------------------*/
  Serial.println("| - - - - - - - - - - - - - - - - - - - - - - - - |");
  Serial.println("| AGROTECHLINK - RADIO BOARD TEST - ESP8266 - - - |");
  Serial.println("| Iniciao da verificacao sensores:  BMP180 & DHT22|");
  Serial.println("| Informacoes do ESP8266 - - - - - - - - - - - -  |");
  Serial.print("| MAC NUMBER: " + WiFi.macAddress());
  Serial.println(" - - - - - - - - - |");
//  Serial.print("| Chip ID: %08X\n" + ESP.getFlashChipId());
//  Serial.println(" - - - - - - - - - |");
  Serial.println("| AGROTECHLINK - TODOS OS DIREITOS SAO RESERVADOS |");
  Serial.println("| - - - - - - - - - - - - - - - - - - - - - - - - |");
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  WiFi.begin(ssid, pass);
  // Wait for connection
  while ( WiFi.status() != WL_CONNECTED ) {
    delay ( 500 );
    Serial.print ( "." );}
  Serial.println ( "" );
  Serial.println("| - - - - - - - - - - - - - - - - - - - - - - - - |");
  Serial.print("| Connected to:  ");
  Serial.println (ssid);
  Serial.print ("| IP address: ");
  Serial.println ( WiFi.localIP() );
// End WiFi section
  Serial.println("| DB - Connecting...   \n");
  while (conn.connect(server_addr, 3306, user, password) != true) {
    delay(500);
    Serial.print ( "." );}
  delay(500);
  Serial.println("| - - - - - - - - - - - - - - - - - - - - - - - - |");  
  dht.begin();
  pressao.begin();
  getPressure();}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// TEST RADIO MODULE BOARD MAIN FUNCTION - INCLUDED: LED0&1+BUZZER
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void loop() {
  LedATLblinks(T);
  GetATLbmpPT();
  GetATLdhtTU();
  T++;
  if (T == 10) T = 0;
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  char ST_dht[6],SU_dht[6], ST_bmp[6], SP_bmp[8], query[82];
  dtostrf(T_dht,2, 2, ST_dht);
  dtostrf(U_dht,2, 2, SU_dht);
  dtostrf(T_bmp,2, 2, ST_bmp);
  dtostrf(P_bmp,4, 2, SP_bmp);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  char INSERT_SQL[] = "INSERT INTO agrotech_intel.teste VALUES (NULL, %s, %s, %s, %s);";
  sprintf(query, INSERT_SQL, ST_dht, SU_dht, ST_bmp, SP_bmp);
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  Serial.println("\n| Executando querry");
  cur_mem->execute(query);
  Serial.println("| Querry INSERT:  ");
  Serial.println(query);
  Serial.println("| Limpando dados de requisicao da memoria.");
  delete cur_mem;
  delay(1000);}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// MAIN FUNCTION END - FINAL
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
