// VERSAO 2 - TAVARES - LED1 MUDOU PARA ATL-5 ANTES ERA LED0 NO ATL-3
// HABILITA WDT POR HARDWARE E DESATIVA POR SOFTWARE - 03/12/2017
// INSERCAO DE UM CONTADOR DE VEZES QUE ENVIA AS MEDICOES DE T/P/U
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
               DEBUG PORT:        DISABLED <--<
               DEBUG LEVEL:       RIEN <--<
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
               __________________________________                       */
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// AGROTECHLINK.COM - ESP8266 - PROGRAM HEADER TEMPLATE - 2017 - NOVEMBER
//                    TODOS OS DIREITOS SAO RESERVADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/*   CADA GPIO POSSUI UMA IDENTIFICACAO ESPECIFICA
     PORTAS UTILIZADAS NAS PLACAS DA MINI ESTACAO CLIMATICA
     ATL3     >--> GPIO-16 
     ATL4     >--> GPIO-14 
     ATL5     >--> GPIO-12 + LED1
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
// LIVRARIAS EXTERNAS PARA FUNCIONAMENTO DOS SENSORES - CONEXAO - DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#include     <ESP8266WiFi.h>         // BIBLIOTECA WiFi DO ESP8266
#include     <Wire.h>                // NECESSÁRIO PARA COMUNICACAO I2C (PRESSAO)
#include     "cactus_io_BME280_I2C.h"
#include     <MySQL_Connection.h>    // CONEXAO COM BANCO DE DADOS
#include     <MySQL_Cursor.h>        // CONEXAO COM BANCO DE DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// AGROTECHLINK MINI ESTACAO CLIMATICA - PINOUTS - DEFINES - DESCRICOES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#define      ATL3         16         // GPIO-16 
#define      ATL4         15         // GPIO-15 + ESTADO NORMAL DO ESP / PERMITE ROTINAS E RESTART
#define      ATL5         12         // GPIO-12 + LED1
#define      ATL7          5         // GPIO-05 + SCL >--> PULLUP INTERNO / SENSOR BMP-180 (PRESSAO)
#define      ATL8          4         // GPIO-04 + SDA >--> PULLUP INTERNO / SENSOR BMP-180 (PRESSAO)
#define      ATL9          2         // GPIO-02 + LED NATIVO DO ESP8266 / PERMITE ROTINAS E RESTART
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// SENSOR PINS E CONFIGURACAO Wi-Fi
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#define      WIFI_SSID     "ATLRPi"   // NOME DA INTERNET DO RASPBERRY-PI
#define      WIFI_PASSWORD "agrotechlinkPI2017"      // SENHA DA INTERNET
BME280_I2C   bme(0x76);                         // I2C using address 0x76
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// DEFINICAO DAS VARIAVEIS GLOBAIS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
char                MAC[25];                // VARIAVEL MAC PARA O MySQL
String              mac;                    // VARIAVEL MAC STRING TO CHAR PARA O MySQL
double              P_bmp, U_bmp, T_bmp;    // VARIAVEIS PARA O SENSOR BMP-180
unsigned long       tempoPrevio = 0;        // VARIAVEL DE CONTROLE DE TEMPO
unsigned long       intervalo = 20000;      // VARIAVEL PARA CONTROLE DE SUBIDA DOS DADOS (1.ª SUBIDA = 45 SEGUNDOS)
//char INSERT_SQL[] = "INSERT INTO agrotech_intel.dia_clima SET mac='%s', d_T='%s', d_U='%s', b_T='%s', b_P='%s', hora=CURRENT_TIME, dia=CURRENT_DATE";
unsigned long       C_cnt = 0;              // contagem ate WDT ou desligar vai para o BD
char INSERT_SQL[] = "INSERT INTO agrotech_intel.dia_clima SET mac='%s', d_U='%s', b_T='%s', b_P='%s', C_cnt=%s, hora=CURRENT_TIME, dia=CURRENT_DATE";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// CONFIGURACOES DE ACESSO AO BANCO DE DADOS E WiFi
/* - - - - - - - - - - - - - - - - - - - - - - - - -' - - - - - - - - - -*/
IPAddress   server_addr (11, 11, 11, 15);       // IP REDE WIFI GATEWAY RPi
char        user[] = "agrotech_u_intel";       // USUARIO DO BANCO DE DADOS
char        password[] = "OlvAgrotechlink1357";         // SENHA DO USUARIO
WiFiClient client;
MySQL_Connection conn((Client *)&client);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// PRESSAO, UMIDADE E TEMPERATURA >>-->> BME280 >------------> NOVO 041117
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void setup() {
  pinMode(ATL5, OUTPUT);     digitalWrite(ATL5, HIGH);   // GPIO-16 + LED0 / INICIA HIGH E TERMINA SETUP LOW
  pinMode(ATL4, OUTPUT);     digitalWrite(ATL4, HIGH);   // GPIO-15 + ESTADO NORMAL DO ESP / HIGH
  pinMode(ATL9, OUTPUT);     digitalWrite(ATL9, HIGH);   // GPIO-02 + ESTADO NORMAL DO ESP / HIGH
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
  while (WiFi.status() != WL_CONNECTED) {
    yield();}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  mac = WiFi.macAddress();
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
unsigned num = 1;
unsigned mysqlResposta;
mysqlResposta = conn.connect(server_addr, 3306, user, password);
while (conn.connect(server_addr, 3306, user, password) != true) {yield();}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
digitalWrite(ATL5, LOW);   // GPIO-16 + LED0 / DESLIGA SETUP OK!
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  if (!bme.begin()) {
//Serial.println("Could not find a valid BME280 sensor, check wiring!");
    while (1);}
// CONFIGURA SW - WDT - DESATIVADO - NA ULTIMA LINHA DO SETUP
  ESP.wdtDisable();}   // WDT do hardware PERMANECE  
// bme.setTempCal(-1);   // OFFSET DE TEMPERATURA AJUSTAVEL DE -1 GRAU C
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FIM DO SETUP E CONFIGURACOES. INICIO DO LOOP.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void loop() {
  unsigned long currentMillis = millis();
  if (currentMillis - tempoPrevio >= intervalo) {     // SOBE OS PRIMEIROS DADOS NO PRIMEIRO MINUTO
    digitalWrite(ATL5, HIGH);                         // GPIO-16 + LED0 | LIGADO. ESTOU VIVO!
    tempoPrevio = currentMillis;
    intervalo = 300000;                  // 5 MINUTOS (TEMPO DE SUBIDA)
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
bme.readSensor();
//P_bmp = bme.getPressure_MB();                   // Pressure in millibars 
P_bmp = bme.getPressure_HP() / 100;                // pressure hectopascal
U_bmp = bme.getHumidity(); 
T_bmp = bme.getTemperature_C();
C_cnt++;                              // INCREMENTA O CONTADOR DE MEDICOES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
    char ST_dht[6], SU_bmp[6], ST_bmp[6], SP_bmp[8], query[170], SC_cnt[255];
// SC_cnt[255]; contagem ate WDT
// CONVERTENDO DADOS DOS SENSORES PARA STRING
    dtostrf(U_bmp, 2, 2, SU_bmp);
    dtostrf(T_bmp, 2, 2, ST_bmp);
    dtostrf(P_bmp, 4, 2, SP_bmp);
    dtostrf(C_cnt, 4, 0, SC_cnt);
    mac.toCharArray(MAC, 25);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
sprintf(query, INSERT_SQL, MAC, SU_bmp, ST_bmp, SP_bmp, SC_cnt);
// CONCATENANDO A STRING INSERT_SQL PARA GRAVACAO NO BANCO DE DADOS
    MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
//    digitalWrite(ATL5, LOW); // LED1 | DESLIGA NO INICIO DA SUBIDA NO BANCO. EFEITO BLINK
// mudado para o final - 16/11/2017 --> cev
    cur_mem->execute(query);    // SUBINDO DADOS PARA O BANCO
    delete cur_mem;             // DELETANDO A QUERY EXECUTADA DA MEMORIA
} yield();
// REINICIA O WDTimer - EVITANDO O REINICIO DO ESP8266
ESP.wdtFeed(); // VAI NA ULTIMA LINHA DA FUNCAO LOOP!
digitalWrite(ATL5, LOW);}   // LED1 | DESLIGA AO FINAL DO ENVIO PARA O RPi
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// MAIN FUNCTION END - FINAL
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/

/*
// CONFIGURA SW - WDT - DESATIVADO - NA ULTIMA LINHA DO SETUP
  ESP.wdtDisable();}   // WDT do hardware PERMANECE
// REINICIA O WDTimer - EVITANDO O REINICIO DO ESP8266
ESP.wdtFeed(); // VAI NA ULTIMA LINHA DA FUNCAO LOOP!
*/
