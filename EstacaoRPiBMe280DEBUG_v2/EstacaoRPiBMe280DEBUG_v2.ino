incompleto!!!
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/*                    VERSÃO RPi TESTE DO CODIGO
               VERSÃO 3.0          DATA: 01072017
               COMPILADO NA VERSAO ARDUINO: 1.8.1
               __________________________________

                PLACA WIFI ESP8266-07 AT THINKER
                PROGRAMA: MINI ESTACAO CLIMATICA
                CONTÉM SENSORES: BMP-180 E DHT22
               __________________________________

               CONFIGURACAO PLACA GRAVACAO - ESP-07
               PLACA:             GENERIC ESP8266 MODULE
               FLASH MODE:        DIO
               FLASH FREQUENCY:   40 MHz
               CPU FREQUENCY:     80 MHz
               FLASH SIZE:        1M (512K SPIFFS)
               DEBUG PORT:        SERIAL
               DEBUG LEVEL:       TODOS >--> WI-FI
               RESET MOTHOD:      ck
               UPLOAD SPEED:      115200
               PORTA: PORTA ESP CONECTADA AO COMPUTADOR
               __________________________________

               CONFIGURACAO PLACA GRAVACAO - ESP-12E
               PLACA:             NODE MCU 1.0 (ESP-12E MODULE)
               CPU FREQUENCY:     80 MHz
               FLASH SIZE:        4M (1M SPIFFS)
               UPLOAD SPEED:      115200
               PORTA: PORTA ESP CONECTADA AO COMPUTADOR
               __________________________________
*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// AGROTECHLINK.COM - ESP8266 - PROGRAM HEADER TEMPLATE - 2017 - JULY
//                    TODOS OS DIREITOS SAO RESERVADOS
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
     OU USB DO COMPUTADOR! */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// LIVRARIAS EXTERNAS PARA FUNCIONAMENTO DOS SENSORES, CONEXÃO E DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#include     <ESP8266WiFi.h>         // BIBLIOTECA WiFi DO ESP8266
//#include     <SFE_BMP180.h>          // SENSOR BMP-180 (PRESSAO) 
#include     <Wire.h>                // NECESSÁRIO PARA COMUNICACAO I2C (PRESSAO)
//#include     "DHT.h"                 // SENSOR DHT22 OU DHT11 (TEMPERATURA-HUMIDADE)   
#include     "cactus_io_BME280_I2C.h"
//#include     <cactus_io_BME280_I2C.h>
#include     <MySQL_Connection.h>    // CONEXAO COM BANCO DE DADOS
#include     <MySQL_Cursor.h>        // CONEXAO COM BANCO DE DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// AGROTECHLINK MINI ESTACAO CLIMATICA - PINOUTS - DEFINES - DESCRICOES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#define      ATL3         16         // GPIO-16 + LED0
#define      ATL4         15         // GPIO-15 + ESTADO NORMAL DO ESP / PERMITE ROTINAS E RESTART
#define      ATL5         12         // GPIO-12 + SENSOR DHT22 (TEMPERATURA-HUMIDADE)
#define      ATL7          5         // GPIO-05 + SCL >--> PULLUP INTERNO / SENSOR BMP-180 (PRESSAO)
#define      ATL8          4         // GPIO-04 + SDA >--> PULLUP INTERNO / SENSOR BMP-180 (PRESSAO)
#define      ATL9          2         // GPIO-02 + LED NATIVO DO ESP8266 / PERMITE ROTINAS E RESTART
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// SENSOR PINS E CONFIGURACAO Wi-Fi
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
//#define      DHTPIN        ATL5             // SENSOR DHT22 (TEMPERATURA-HUMIDADE)
//#define      DHTTYPE       DHT22            // ESPECIFICACAO DO SENSOR UTILIZADO
//#define      WIFI_SSID     "Agrotechlink"   // NOME DA INTERNET DO RASPBERRY-PI
//#define      WIFI_PASSWORD "agricultura"    // SENHA DA INTERNET
//#define      WIFI_SSID     "CEV_UNIFIQUE_2GHz"   // NOME DA INTERNET DO RASPBERRY-PI
//#define      WIFI_PASSWORD "UnfqAngelica2015"    // SENHA DA INTERNET
#define      WIFI_SSID     "ATLRPi"   // NOME DA INTERNET DO RASPBERRY-PI
#define      WIFI_PASSWORD "agrotechlinkPI2017"    // SENHA DA INTERNET
//DHT          dht (DHTPIN, DHTTYPE, 30);         // ENDERECAMENTO DO SENSOR DHT22
//SFE_BMP180   pressao;                       // DEFINICAO DO SENSOR BMP-180
BME280_I2C   bme(0x76);         // I2C using address 0x76
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/* PUT A 100nF decoupling capacitor between VCC and GND near the DHT22!
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/* NOTE: For working with a faster chip, like an Arduino Due or Teensy, 
you might need to increase the threshold for cycle counts considered a 
1 or 0. You can do this by passing a 3rd parameter for this threshold.  
It's a bit of fiddling to find the right value, but in general the faster
the CPU the higher the value.  The default for a 16mhz AVR is a value of 
6.  For an Arduino Due that runs at 84MHz a value of 30 works. Example to
initialize DHT sensor for Arduino Due: */
// DHT dht(DHTPIN, DHTTYPE, 6);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// DEFINICAO DAS VARIAVEIS GLOBAIS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
char                MAC[25];                // VARIAVEL MAC PARA O MySQL
String              mac;                    // VARIAVEL MAC STRING TO CHAR PARA O MySQL
double              P_bmp, U_bmp, T_bmp;    // VARIAVEIS PARA O SENSOR BMP-180
//float               T_dht = 0;//, U_dht;           // VARIAVEIS PARA O SENSOR DHT22
unsigned long       tempoPrevio = 0;        // VARIAVEL DE CONTROLE DE TEMPO
unsigned long       intervalo = 20000;      // VARIAVEL PARA CONTROLE DE SUBIDA DOS DADOS (1.ª SUBIDA = 45 SEGUNDOS)
char INSERT_SQL[] = "INSERT INTO agrotech_intel.dia_clima SET mac='%s', d_T='%s', d_U='%s', b_T='%s', b_P='%s', hora=CURRENT_TIME, dia=CURRENT_DATE";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// CONFIGURACOES DE ACESSO AO BANCO DE DADOS E WiFi
/* - - - - - - - - - - - - - - - - - - - - - - - - -' - - - - - - - - - -*/
//IPAddress   server_addr (10, 3, 141, 1);    // IP DO MySQL SERVER - LOCALHOST
//IPAddress   server_addr (11, 12, 13, 30);    // IP DO MySQL SERVER - LOCALHOST
//IPAddress   server_addr (11, 12, 13, 20);    // IP DO MySQL SERVER - LOCALHOST - FERNANDO
IPAddress   server_addr (11, 11, 11, 15);    // IP DA REDE WIFI GATEWAY - 28062017
//IPAddress   server_addr (0, 0, 0, 0);    // IP DA REDE WIFI GATEWAY - 28062017
//IPAddress   server_addr (127, 0, 0, 1);    // IP DA REDE WIFI GATEWAY - 28062017
char        user[] = "agrotech_u_intel";        // USUARIO DO BANCO DE DADOS
char        password[] = "OlvAgrotechlink1357"; // SENHA DO USUARIO
// para conectar precisa configurar "/etc/mysql/my.cnf" --> bind address = 11.12.13.30
// para conectar precisa configurar: sudo nano /etc/mysql/my.cnf --> bind address = 0.0.0.0
WiFiClient client;
MySQL_Connection conn((Client *)&client);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// PRESSAO, UMIDADE E TEMPERATURA >>-->> BME280 >>-->> NOVO 070817
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/*double getPUT() {
bme.readSensor();
P_bmp = bme.getPressure_MB();     // Pressure in millibars
U_bmp = bme.getHumidity(); 
T_bmp = bme.getTemperature_C();
Serial.println("\n- - - - - - - - - - - - - - - - -");
Serial.print("Pressao atmosferica.....: ");
Serial.print(P_bmp, 2);    Serial.println(" mBar");  //Serial.println(" hPa");
Serial.print("Umidade relativa do ar..: ");
Serial.print(U_bmp, 2);    Serial.println(" %UR");
Serial.print("Temperatura ambiente....: ");
Serial.print(T_bmp, 2);    Serial.print(" *C");
Serial.println("\n- - - - - - - - - - - - - - - - -");}*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void setup() {
  Serial.begin(115200);
  Serial.println("\nL E D >---> L I G A D O . . .");
  pinMode(ATL5, OUTPUT);     digitalWrite(ATL5, HIGH);   // GPIO-16 + LED0 / INICIA HIGH E TERMINA SETUP LOW
  pinMode(ATL4, OUTPUT);     digitalWrite(ATL4, HIGH);   // GPIO-15 + ESTADO NORMAL DO ESP / HIGH
  pinMode(ATL9, OUTPUT);     digitalWrite(ATL9, HIGH);   // GPIO-02 + ESTADO NORMAL DO ESP / HIGH
// A T E N C A O ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! !
// O ATL4 ESTA LIGADO NO GPIO-14  E NAO NO 15.
// NO FUTURO SE FORMOS USA-LO TERA UM BUZZER QUE PODE SER ACIONADO PELO GPIO-14
// SE ESQUECERMOS QUE FOI USADA A MESMA NOMENCLATURA PARA DUAS GPIO DIFERENTES
// PODERA GERAR CONFUSAO!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  Serial.println("\nConectando 123 A internet...");
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  while (WiFi.status() != WL_CONNECTED) {
    yield();
  }
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  Serial.println();
  Serial.print("Conectado 456 a: ");
  Serial.println(WiFi.localIP());
  Serial.println("MAC: " + WiFi.macAddress());
  mac = WiFi.macAddress();
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  Serial.print("Endereco do servidor MySQL: ");
  Serial.println(server_addr);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
//  dht.begin();               // INICIANDO SENSOR DE TEMPERATURA DHT22
//  pressao.begin();           // INICIANDO SENSOR DE PRESSAO BMP-180
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
unsigned num = 1;
unsigned mysqlResposta;
mysqlResposta = conn.connect(server_addr, 3306, user, password);
Serial.print("\nResposta: ");
Serial.print(mysqlResposta);
Serial.print("\nResposta SERVER ADDR: ");
Serial.print(server_addr);
Serial.print("\nResposta USER: ");
Serial.print(user);
Serial.print("\nResposta PW: ********************\n");
//Serial.println(password);
while (conn.connect(server_addr, 3306, user, password) != true) {
yield();
Serial.print(" + "); Serial.println(num++); }
Serial.println("0k!!! \nBanco de dados conectado!!!");
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  digitalWrite(ATL5, LOW);   // GPIO-16 + LED0 / DESLIGA SETUP OK!
  Serial.println("\nL E D >---> D E S L I G A D O . . ."); 
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
Serial.println("Bosch BME280 Barometric Pressure - Humidity - Temp Sensor | cactus.io");
  if (!bme.begin()) {
    Serial.println("Could not find a valid BME280 sensor, check wiring!");
    while (1);}
  bme.setTempCal(-1);   // NAO EXPLICA O PORQUE DO "-1"???
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FIM DO SETUP E CONFIGURACOES. INICIO DO LOOP.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void loop() {
  unsigned long currentMillis = millis();
  if (currentMillis - tempoPrevio >= intervalo) {     // SOBE OS PRIMEIROS DADOS NO PRIMEIRO MINUTO
    digitalWrite(ATL5, HIGH);                         // GPIO-16 + LED0 | LIGADO. ESTOU VIVO!
  Serial.println("\nL E D >---> L I G A D O . . .");
    tempoPrevio = currentMillis;
//    intervalo = 300000;                  // 5 MINUTOS (TEMPO DE SUBIDA)
//    intervalo = 60000;                   // 1 MINUTO
    intervalo = 120000;                    // 2 MINUTOS
//intervalo = 240000;                    // APOS - SOBE OS DADOS A CADA  4 MINUTOS
//    GetATLdhtTU();                                    // DHT22
//    GetATLbmpPT();                                    // BMP-180
//getPUT();   //BME280 >>-->> P, U, T
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// nao eh uma funcao... >>-->> oops!
// PRESSAO, UMIDADE E TEMPERATURA >>-->> BME280 >>-->> NOVO 070817
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
bme.readSensor();
//P_bmp = bme.getPressure_MB();     // Pressure in millibars 
P_bmp = bme.getPressure_HP() / 100;     // pressure hectopascal
U_bmp = bme.getHumidity(); 
T_bmp = bme.getTemperature_C();
Serial.println("\n- - - - - - - - - - - - - - - - -");
Serial.print("Pressao atmosferica.....: ");
Serial.print(P_bmp, 2);    Serial.println(" hPa");
Serial.print("Umidade relativa do ar..: ");
Serial.print(U_bmp, 2);    Serial.println(" %UR");
Serial.print("Temperatura ambiente....: ");
Serial.print(T_bmp, 2);    Serial.print(" *C");
Serial.println("\n- - - - - - - - - - - - - - - - -");
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
    char ST_dht[6], SU_bmp[6], ST_bmp[6], SP_bmp[8], query[170];
    float T_dht = 0.00;  //  RETIRAR!!! APOS TESTES!!!
// CONVERTENDO DADOS DOS SENSORES PARA STRING
    dtostrf(T_dht, 2, 2, ST_dht);
    dtostrf(U_bmp, 2, 2, SU_bmp);
    dtostrf(T_bmp, 2, 2, ST_bmp);
    dtostrf(P_bmp, 4, 2, SP_bmp);
    mac.toCharArray(MAC, 25);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
    sprintf(query, INSERT_SQL, MAC, ST_dht, SU_bmp, ST_bmp, SP_bmp);
// CONCATENANDO A STRING INSERT_SQL PARA GRAVACAO NO BANCO DE DADOS
    MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
    Serial.println("\nL E D >---> D E S L I G A D O . . .");
    digitalWrite(ATL5, LOW);        // GPIO-16 + LED0 | DESLIGA NO INICIO DA SUBIDA NO BANCO. EFEITO BLINK
    cur_mem->execute(query);        // SUBINDO DADOS PARA O BANCO
    delete cur_mem;                 // DELETANDO A QUERY EXECUTADA DA MEMORIA
  }
  yield();
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// MAIN FUNCTION END - FINAL
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
