/*                    VERSÃO FINAL DO CODIGO
                VERSÃO 1.0.1      DATA: 12/01/2017
                COMPILADO NA VERSAO ARDUINO: 1.8.1
                __________________________________

                VERSAO DO PROGRAMA ARDUINO: 1.8.1
                PROGRAMA ATUALIZADO EM: 14/01/2017
                HORA-ULTIMO UPDATE: 12:14 p.m
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
#include     <FS.h>                // BIBLIOTECA WiFi DO ESP8266. DEVE SER SEMPRE A PRIMEIRA BIBLIOTECA MENCIONADA NO #INLUDE!!!!!!!
#include     <ESP8266WiFi.h>       // BIBLIOTECA WiFi DO ESP8266
#include     <DNSServer.h>         // BIBLIOTECA WiFi DO ESP8266
#include     <ESP8266WebServer.h>  // BIBLIOTECA WiFi DO ESP8266
#include     "WiFiManager.h"       // BIBLIOTECA WiFi-MANANGER DO ESP8266
#include     <SFE_BMP180.h>        // SENSOR BMP-180 (PRESSAO) 
#include     <Wire.h>              // NECESSÁRIO PARA COMUNICACAO I2C (PRESSAO)
#include     "DHT.h"               // SENSOR DHT22 OU DHT11 (TEMPERATURA-HUMIDADE)   
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
int         nCon, contNcon;          // VARIAVEIS PARA A BIBLIOTECA WiFi MANANGER (ASSEGURAR A CONEXAO COM A INTERNET EM CASO DE ERROS)
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// CONFIGURACOES DE ACESSO AO BANCO DE DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
IPAddress   server_addr (186, 202, 127, 122);   // IP DO MySQL SERVER - SITE AGROTECHLINK.COM
char        user[] = "agrotech_u_intel";        // USUARIO DO BANCO DE DADOS
char        password[] = "OlvAgrotechlink1357"; // SENHA DO USUARIO

WiFiClient client;
MySQL_Connection conn((Client *)&client);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// MODO CONFIGURAÇÃO DO WiFi MANANGER
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void configModoCallback (WiFiManager *myWiFiManager) {
  Serial.println("Iniciando modo de configuracao.");
  Serial.println(WiFi.softAPIP());
  Serial.println(myWiFiManager->getConfigPortalSSID());
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FAZER O LED PISCAR
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void LedATLblinks(unsigned M) {
  Serial.print("\nJUST LED0 IS BLINKING N TIMES: ");    Serial.println(M);
  for (short j = 0; j < M; j++) {
    Serial.print(j + 1);  Serial.print(" . ");
    digitalWrite(ATL3, HIGH);                             delay(100);
    digitalWrite(ATL3, LOW);                              delay(2500);
  }
  digitalWrite(ATL3, LOW);
  digitalWrite(LED_BUILTIN, LOW);                         delay(100);
  digitalWrite(LED_BUILTIN, HIGH);
  Serial.print("\n| - - - - - - BUILTIN LED BLINKS! - - - - - - - - |");
  Serial.println("\n| - - - - - - - - - - - - - - - - - - - - - - - - |");
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
        else Serial.print("\n>--> Erro ajustando - P - ESPECIAL!");
      }
      else Serial.print("\n>--> Erro na leitura - P - ESPECIAL!");
    }
    else Serial.print("\n>--> Erro ajustando temperatura BMP - ESPECIAL!");
  }
  else Serial.print("\n>--> Erro na leitura da temperatura BMP - ESPECIAL!");
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// GET DADOS DE TEMPERATURA E PRESSAO DO BMP-180
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void GetATLbmpPT() { // SENSOR BMP180
  getPressure();
  if (isnan(P_bmp)) {
    delay(500);
    getPressure();
    if (isnan(P_bmp)) {
      Serial.println("\n>--> Falha na leitura - p - do sensor BMP180!");
      return;
    }
  }
  if (isnan(T_bmp)) {
    delay(500);
    getPressure();
    if (isnan(T_bmp)) {
      Serial.println("\n>--> Falha na leitura - T - do sensor BMP180!");
      return;
    }
  }
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
// GET DADOS DE TEMPERATURA E HUMIDADE DO DHT22 OU DHT11
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void GetATLdhtTU() {
  U_dht = dht.readHumidity();
  T_dht = dht.readTemperature();
  if (isnan(U_dht) || isnan(T_dht)) {
    delay(2000); // WHEN DHT22 IS USED SET THIS TO 2000, OTHERWISE SET TO 5000
    U_dht = dht.readHumidity();
    T_dht = dht.readTemperature();
    if (isnan(U_dht) || isnan(T_dht)) {
      Serial.println("\n>--> Falha na leitura do Sensor DHT!");
      return;
    }
  }
  Serial.print("\n| Temperatura ambiente.....DHT: ");
  Serial.print(T_dht);
  Serial.print(" *C");
  Serial.println("  - - - - |");
  Serial.print("| Umidade relativa do ar...DHT: ");
  Serial.print(U_dht);
  Serial.print(" %UR");
  Serial.println(" - - - - |");
  Serial.print("\n| - - - - - - - - - - - - - - - - - - - - - - - - |");
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void setup() {
  Serial.begin(115200);
  pinMode(LED_BUILTIN, OUTPUT);         // INICIALIZA O LED_BUILTIN NATIVO DO ESP8266
  digitalWrite(LED_BUILTIN, HIGH);      // DESLIGA O LED_BUILTIN NATIVO DO ESP8266
  pinMode(ATL3, OUTPUT);      digitalWrite(ATL3, LOW);  // GPIO-16 + LED0
  pinMode(ATL4, OUTPUT);      digitalWrite(ATL4, LOW);  // GPIO-14 + BUZZER
  /*----------------------------------------------------------------------*/
  WiFiManager wifiManager;
  wifiManager.setAPCallback(configModoCallback);
  if (!wifiManager.autoConnect("Agrotechlink", "agrotechlink")) {
    Serial.println("Falha na conexao. Reiniciando");
    contNcon = (nCon + 1);
    delay(5000);
    ESP.reset();
    delay(2000);
    if (contNcon == 5) {
      wifiManager.resetSettings();      // CASO OCORRA FALHAS EXEPCIONAIS NA CONEXAO O ESP8266 RESETA E INICIA NOVAMENTE
      delay(5000);
      ESP.reset();
      delay(2000);
    }
  }
  Serial.println("Internet conectada. Wi-fi client em rede :)");
  delay(500);
  /*----------------------------------------------------------------------*/
  Serial.println("| - - - - - - - - - - - - - - - - - - - - - - - - |");
  Serial.println("| AGROTECHLINK - RADIO BOARD TEST - ESP8266 - - - |");
  Serial.println("| Iniciao da verificacao sensores:  BMP-180 & DHT22|");
  Serial.println("| Informacoes do ESP8266 - - - - - - - - - - - -  |");
  Serial.print("| MAC NUMBER: " + WiFi.macAddress());
  Serial.println(" - - - - - - - - - |");
  Serial.print("| IP LOCAL: " + WiFi.localIP());
  Serial.println(" - - - - - - - - - |");
  Serial.println("| AGROTECHLINK - TODOS OS DIREITOS SAO RESERVADOS |");
  Serial.println("| - - - - - - - - - - - - - - - - - - - - - - - - |");
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  Serial.println("| Conectando ao banco de dados da Agrotechlink... |\n");
  while (conn.connect(server_addr, 3306, user, password) != true) {
    delay(500);
    Serial.print ( "." );
  }
  delay(500);
  
  Serial.println("| - - - - - - - - - - - - - - - - - - - - - - - - |");
  Serial.println("| Conexao com o banco de dados bem sucedida! (:   |\n");
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  dht.begin();                      // INICIANDO SENSOR DE TEMPERATURA DHT22
  pressao.begin();                  // INICIANDO SENSOR DE PRESSAO BMP-180
  getPressure();                    // SETANDO CONFIGURAÇÕES ESPECIAIS DO BMP-180
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FIM DO SETUP E CONFIGURACOES. INICIO DO LOOP.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void loop() {
  LedATLblinks(T);              // LED
  GetATLbmpPT();                // BMP-180
  GetATLdhtTU();                // DHT22
  T++;                          // VERIFICACAO DO LED
  if (T == 10) T = 0;
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  char ST_dht[6], SU_dht[6], ST_bmp[6], SP_bmp[8], query[82];  // CONVERTENDO DADOS DOS SENSORES PARA STRING
  dtostrf(T_dht, 2, 2, ST_dht);
  dtostrf(U_dht, 2, 2, SU_dht);
  dtostrf(T_bmp, 2, 2, ST_bmp);
  dtostrf(P_bmp, 4, 2, SP_bmp);
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  char INSERT_SQL[] = "INSERT INTO agrotech_intel.teste VALUES (NULL, %s, %s, %s, %s);";
  sprintf(query, INSERT_SQL, ST_dht, SU_dht, ST_bmp, SP_bmp);  // CONCATENANDO A STRING INSERT_SQL PARA GRAVACAO NO BANCO DE DADOS
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  Serial.println("\n| Executando querry no banco de dados");
  cur_mem->execute(query);
  Serial.println("| Querry INSERT:  ");
  Serial.println(query);
  Serial.println("| Limpando dados de requisicao da memoria.");
  delete cur_mem;
  Serial.println("| Execucao de querry bem sucedida!");
  delay(1000);
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// MAIN FUNCTION END - FINAL
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
