/*                    VERSÃO FINAL DO CODIGO
               VERSÃO 1.0.1      DATA: 12/01/2017
               COMPILADO NA VERSAO ARDUINO: 1.8.1
               __________________________________

            !!LEMBRAR DE MUDAR PARA O CPF DO USUÁRIO!!

                PLACA WIFI ESP8266-07 AT THINKER
                PROGRAMA: MINI ESTACAO CLIMATICA
                CONTÉM SENSORES: BMP-180 E DHT22
               __________________________________

               CONFIGURACAO DA PLACA PARA GRAVACAO
               PLACA:             GENERIC ESP8266 MODULE
               FLASH MODE:        DIO
               FLASH FREQUENCY:   40 MHz
               CPU FREQUENCY:     80 MHz
               FLASH SIZE:        1M (512K SPIFFS)
               DEBUG PORT:        DISABLED
               DEBUG LEVEL:       NENHUM
               RESET MOTHOD:      ck
               UPLOAD SPEED:      115200
               PORTA: PORTA ESP CONECTADA AO COMPUTADOR
               __________________________________
*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// AGROTECHLINK.COM - ESP8266 - PROGRAM HEADER TEMPLATE - 2017 - JANUARY
//                    TODOS OS DIREITOS RESERVADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/*   CADA GPIO POSSUI UMA IDENTIFICACAO ESPECIFICA
     PORTAS UTILIZADAS NAS PLACAS DA MINI ESTACAO CLIMATICA

     ATL3     >--> GPIO-16 + LED0
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
#include     <ESP8266WiFi.h>         // BIBLIOTECA WiFi DO ESP8266
#include     <DNSServer.h>           // BIBLIOTECA WiFi DO ESP8266
#include     <ESP8266WebServer.h>    // BIBLIOTECA WiFi DO ESP8266
#include     <WiFiManager.h>         // BIBLIOTECA WiFi-MANANGER DO ESP8266
#include     <SFE_BMP180.h>          // SENSOR BMP-180 (PRESSAO) 
#include     <Wire.h>                // NECESSÁRIO PARA COMUNICACAO I2C (PRESSAO)
#include     "DHT.h"                 // SENSOR DHT22 OU DHT11 (TEMPERATURA-HUMIDADE)   
#include     <MySQL_Connection.h>    // CONEXAO COM BANCO DE DADOS
#include     <MySQL_Cursor.h>        // CONEXAO COM BANCO DE DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// AGROTECHLINK MINI ESTACAO CLIMATICA - PINOUTS - DEFINES - DESCRICOES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#define      LED_BUILTIN   2         // LED_BUILTIN (LED NATIVO DO ESP8266)
#define      ATL3         16         // GPIO-16 + LED0
#define      ATL5         12         // GPIO-12 + SENSOR DHT22 (TEMPERATURA-HUMIDADE)
#define      ATL7          5         // GPIO-05 + SCL >--> PULLUP INTERNO / SENSOR BMP-180 (PRESSAO)
#define      ATL8          4         // GPIO-04 + SDA >--> PULLUP INTERNO / SENSOR BMP-180 (PRESSAO)
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// SENSOR PINS SETTINGS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#define      DHTPIN     ATL5         // SENSOR DHT22 (TEMPERATURA-HUMIDADE)
#define      DHTTYPE    DHT22        // ESPECIFICACAO DO SENSOR UTILIZADO
DHT          dht (DHTPIN, DHTTYPE);  // ENDERECAMENTO DO SENSOR DHT22
SFE_BMP180   pressao;                // DEFINICAO DO SENSOR BMP-180
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// DEFINICAO DAS VARIAVEIS GLOBAIS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
static const char   CPF[] = "09084678931";               // CPF DO USUARIO. APENAS NUMEROS!!!!
//static const char   CPF[] = "01234567890";               // CPF DO USUARIO. APENAS NUMEROS!!!!
// ID DO PATO DONALD PARA TESTES...
char                MAC[25];                             // MAC PARA O MySQL
String              mac;                                 // VARIAVEL MAC STRING TO CHAR. MySQL
double              baseline, P_bmp, T_bmp;              // VARIAVEIS PARA O SENSOR BMP-180
float               T_dht, U_dht;                        // VARIAVEIS PARA O SENSOR DHT22
int                 fMysql;                              // VARIAVEl PARA MySQL EM CASO DE ERROS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// CONFIGURACOES DE ACESSO AO BANCO DE DADOS E WiFi
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
IPAddress   server_addr (186, 202, 127, 122);   // IP DO MySQL SERVER - SITE AGROTECHLINK.COM
char        user[] = "agrotech_u_intel";        // USUARIO DO BANCO DE DADOS
char        password[] = "OlvAgrotechlink1357"; // SENHA DO USUARIO

WiFiClient client;
MySQL_Connection conn((Client *)&client);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// CONFIGURACAO WiFi
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void configModeCallback (WiFiManager *myWiFiManager) {
  Serial.println(WiFi.softAPIP());
  Serial.println(myWiFiManager->getConfigPortalSSID());
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FAZER O LED PISCAR
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void LedATLblinks(unsigned M) {
  for (short j = 0; j < M; j++) {
    digitalWrite(ATL3, HIGH);                    delay(300);
    digitalWrite(ATL3, LOW);                     delay(300);
  }
  digitalWrite(ATL3, LOW);
  digitalWrite(LED_BUILTIN, LOW);                delay(300);
  digitalWrite(LED_BUILTIN, HIGH);
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// PRESSAO E TEMPERATURA DO BMP180 - ESPECIAL
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
          return (P_bmp, T_bmp);
        }
      }
    }
  }
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// GET DADOS DE TEMPERATURA E PRESSAO DO BMP-180
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void GetATLbmpPT() {
  getPressure();
  if (isnan(P_bmp)) {
    delay(500);
    getPressure();
    if (isnan(P_bmp)) {
      return;
    }
  }
  if (isnan(T_bmp)) {
    delay(500);
    getPressure();
    if (isnan(T_bmp)) {
      return;
    }
  }
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// GET DADOS DE TEMPERATURA E HUMIDADE DO DHT22 OU DHT11
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void GetATLdhtTU() {
  U_dht = dht.readHumidity();
  T_dht = dht.readTemperature();
  delay(500);
  if (isnan(U_dht) || isnan(T_dht)) {
    for (short i = 0; i < 11; i++) {
      delay(2000);
      U_dht = dht.readHumidity();
      T_dht = dht.readTemperature();
    }
  }
  if (isnan(U_dht) || isnan(T_dht)) {
    T_dht = 0; // PARA ASSEGURAR O REGISTRO 0 NO BD DO MySQL
    U_dht = 0; // PARA ASSEGURAR O REGISTRO 0 NO BD DO MySQL
  }
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void setup() {
  pinMode(LED_BUILTIN, OUTPUT);         // INICIALIZA O LED_BUILTIN NATIVO DO ESP8266
  digitalWrite(LED_BUILTIN, HIGH);      // DESLIGA O LED_BUILTIN NATIVO DO ESP8266
  pinMode(ATL3, OUTPUT);      digitalWrite(ATL3, LOW);  // GPIO-16 + LED0
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  WiFiManager wifiManager;
  wifiManager.setAPCallback(configModeCallback);
  wifiManager.autoConnect("Agrotechlink", "agrotechlink");
  delay(500);
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  mac = WiFi.macAddress();
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  while (conn.connect(server_addr, 3306, user, password) != true) {
    delay(500);
  }
  delay(500);

  LedATLblinks(3);           // LED. 3 VEZES = PRIMEIRA CONEXAO COM BD OK!
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  dht.begin();               // INICIANDO SENSOR DE TEMPERATURA DHT22
  pressao.begin();           // INICIANDO SENSOR DE PRESSAO BMP-180
  getPressure();             // SETANDO CONFIGURACOES ESPECIAIS DO BMP-180
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FIM DO SETUP E CONFIGURACOES. INICIO DO LOOP.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void loop() {
  GetATLbmpPT();             // BMP-180
  GetATLdhtTU();             // DHT22
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  if (conn.connected()) {
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
    char ST_dht[6], SU_dht[6], ST_bmp[6], SP_bmp[8], query[190];
    // CONVERTENDO DADOS DOS SENSORES PARA STRING
    dtostrf(T_dht, 2, 2, ST_dht);
    dtostrf(U_dht, 2, 2, SU_dht);
    dtostrf(T_bmp, 2, 2, ST_bmp);
    dtostrf(P_bmp, 4, 2, SP_bmp);

    mac.toCharArray(MAC, 25);
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
    char INSERT_SQL[] = "INSERT INTO agrotech_intel.dia_clima SET cpf='%s', mac='%s', d_T='%s', d_U='%s', b_T='%s', b_P='%s', data=CURRENT_DATE, hora=CURRENT_TIME";
    sprintf(query, INSERT_SQL, CPF, MAC, ST_dht, SU_dht, ST_bmp, SP_bmp);
    delay(500);
    // CONCATENANDO A STRING INSERT_SQL PARA GRAVACAO NO BANCO DE DADOS
    MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
    cur_mem->execute(query);
    delete cur_mem;
    fMysql = 0;
    LedATLblinks(1);           // LED. 1 VEZ = DADOS INSERIDOS NO BD!

  } else {
    conn.close();
    if (conn.connect(server_addr, 3306, user, password)) {
      delay(500);
      fMysql++;
      if (fMysql >= 7) {
        delay(5000);
        ESP.restart();
        delay(3000);
      }
    }
  }
  delay(2500);
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// MAIN FUNCTION END - FINAL
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/