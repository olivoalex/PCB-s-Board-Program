/*                    VERSÃO RPi TESTE DO CODIGO
               VERSÃO 2.0      DATA: 25/05/2017
               COMPILADO NA VERSAO ARDUINO: 1.8.1
               __________________________________

                PLACA WIFI ESP8266-07 AT THINKER
                PROGRAMA: MINI ESTACAO CLIMATICA
                CONTÉM SENSORES: BMP-180 E DHT22
               __________________________________

               CONFIGURACAO DA PLACA PARA GRAVACAO - ESP-07
               PLACA:             GENERIC ESP8266 MODULE
               FLASH MODE:        DIO
               FLASH FREQUENCY:   40 MHz
               CPU FREQUENCY:     80 MHz
               FLASH SIZE:        1M (512K SPIFFS)
               DEBUG PORT:        SERIAL
               DEBUG LEVEL:       TODOS
               RESET MOTHOD:      ck
               UPLOAD SPEED:      115200
               PORTA: PORTA ESP CONECTADA AO COMPUTADOR
               __________________________________

               CONFIGURACAO DA PLACA PARA GRAVACAO - ESP-12E
               PLACA:             NODE MCU 1.0 (ESP-12E MODULE)
               CPU FREQUENCY:     80 MHz
               FLASH SIZE:        4M (3M SPIFFS)
               UPLOAD SPEED:      115200
               PORTA: PORTA ESP CONECTADA AO COMPUTADOR
               __________________________________

*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// AGROTECHLINK.COM - ESP8266 - PROGRAM HEADER TEMPLATE - 2017 - JANUARY
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
     OU USB DO COMPUTADOR!
*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// LIVRARIAS EXTERNAS PARA FUNCIONAMENTO DOS SENSORES, CONEXÃO E DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#include     <ESP8266WiFi.h>         // BIBLIOTECA WiFi DO ESP8266
#include     <SFE_BMP180.h>          // SENSOR BMP-180 (PRESSAO) 
#include     <Wire.h>                // NECESSÁRIO PARA COMUNICACAO I2C (PRESSAO)
#include     "DHT.h"                 // SENSOR DHT22 OU DHT11 (TEMPERATURA-HUMIDADE)   
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
#define      DHTPIN        ATL5             // SENSOR DHT22 (TEMPERATURA-HUMIDADE)
#define      DHTTYPE       DHT22            // ESPECIFICACAO DO SENSOR UTILIZADO
//#define      WIFI_SSID     "Agrotechlink"   // NOME DA INTERNET DO RASPBERRY-PI
//#define      WIFI_PASSWORD "agricultura"    // SENHA DA INTERNET
//#define      WIFI_SSID     "CEV_UNIFIQUE_2GHz"   // NOME DA INTERNET DO RASPBERRY-PI
//#define      WIFI_PASSWORD "UnfqAngelica2015"    // SENHA DA INTERNET
#define      WIFI_SSID     "ATLRPi"   // NOME DA INTERNET DO RASPBERRY-PI
#define      WIFI_PASSWORD "agrotechlinkPI2017"    // SENHA DA INTERNET

DHT          dht (DHTPIN, DHTTYPE);         // ENDERECAMENTO DO SENSOR DHT22
SFE_BMP180   pressao;                       // DEFINICAO DO SENSOR BMP-180
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// DEFINICAO DAS VARIAVEIS GLOBAIS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
char                MAC[25];                             // VARIAVEL MAC PARA O MySQL
String              mac;                                 // VARIAVEL MAC STRING TO CHAR PARA O MySQL
double              P_bmp, T_bmp;                        // VARIAVEIS PARA O SENSOR BMP-180
float               T_dht, U_dht;                        // VARIAVEIS PARA O SENSOR DHT22
unsigned long       tempoPrevio = 0;                     // VARIAVEL DE CONTROLE DE TEMPO
unsigned long       intervalo = 20000;                   // VARIAVEL PARA CONTROLE DE SUBIDA DOS DADOS (1.ª SUBIDA = 45 SEGUNDOS)
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
// PRESSAO E TEMPERATURA DO BMP180 - ESPECIAL
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
double getPressure() {
  char status;
  status = pressao.startTemperature();
  if (status != 0) {
    delay(status);
    status = pressao.getTemperature(T_bmp);
    if (status != 0) {
      status = pressao.startPressure(3);
      if (status != 0) {
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
  if ((P_bmp || T_bmp) == 0) {
    for (short i = 0; i < 10; i++) {
      getPressure();
      if ((P_bmp || T_bmp) != 0) {
        i = 12;
      }
    }
  }
Serial.println("\n- - - - - - - - - - - - - - - - -");
Serial.print("Pressao atmosferica......BMP: ");
Serial.print(P_bmp, 2);    Serial.println(" hPa");
Serial.print("Temperatura ambiente.....BMP: ");
Serial.print(T_bmp, 2);    Serial.print(" *C");
Serial.println("\n- - - - - - - - - - - - - - - - -");}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// GET DADOS DE TEMPERATURA E HUMIDADE DO DHT22
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void GetATLdhtTU() {
  U_dht = dht.readHumidity();
  T_dht = dht.readTemperature();
  if (isnan(U_dht) || isnan(T_dht)) {
    for (short i = 0; i < 10; i++) {
      delay(2000);
      U_dht = dht.readHumidity();
      T_dht = dht.readTemperature();
      if ((U_dht || T_dht) != 0) {
        i = 12;
      }
    }
  }
  if (isnan(T_dht)) {
    T_dht = 0;      // PARA ASSEGURAR O REGISTRO 0 NO BD DO MySQL
    if (isnan(U_dht)) {
      U_dht = 0;    // PARA ASSEGURAR O REGISTRO 0 NO BD DO MySQL
    }
  }
Serial.print("\nTemperatura ambiente.....DHT: ");
Serial.print(T_dht);
Serial.println(" *C");
Serial.print("Umidade relativa do ar...DHT: ");
Serial.print(U_dht);
Serial.println(" %UR");
Serial.println("- - - - - - - - - - - - - - - - -");}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void setup() {
  Serial.begin(115200);
  pinMode(ATL3, OUTPUT);     digitalWrite(ATL3, HIGH);   // GPIO-16 + LED0 / INICIA HIGH E TERMINA SETUP LOW
  pinMode(ATL4, OUTPUT);     digitalWrite(ATL4, HIGH);   // GPIO-15 + ESTADO NORMAL DO ESP / HIGH
  // A T E N C A O ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! ! !
  // O ATL4 ESTA LIGADO NO GPIO-14  E NAO NO 15.
  // NO FUTURO SE FORMOS USA-LO TERA UM BUZZER QUE PODE SER ACIONADO PELO GPIO-14
  // SE ESQUECERMOS QUE FOI USADA A MESMA NOMENCLATURA PARA DUAS GPIO DIFERENTES
  // PODERA GERAR CONFUSAO!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  pinMode(ATL9, OUTPUT);     digitalWrite(ATL9, HIGH);   // GPIO-02 + ESTADO NORMAL DO ESP / HIGH
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
  dht.begin();               // INICIANDO SENSOR DE TEMPERATURA DHT22
  pressao.begin();           // INICIANDO SENSOR DE PRESSAO BMP-180
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
  digitalWrite(ATL3, LOW);   // GPIO-16 + LED0 / DESLIGA SETUP OK!
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FIM DO SETUP E CONFIGURACOES. INICIO DO LOOP.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void loop() {
  unsigned long currentMillis = millis();

  if (currentMillis - tempoPrevio >= intervalo) {     // SOBE OS PRIMEIROS DADOS NO PRIMEIRO MINUTO
    digitalWrite(ATL3, HIGH);                         // GPIO-16 + LED0 | LIGADO. ESTOU VIVO!
    tempoPrevio = currentMillis;
//    intervalo = 300000;                  // APOS - SOBE OS DADOS A CADA  5 MINUTOS (ESSE VAI SER O NOSSO TEMPO DE SUBIDA!!)
//    intervalo = 60000;                   // APOS - SOBE OS DADOS A CADA  1 MINUTO >--> testes com RPi
    intervalo = 120000;                    // APOS - SOBE OS DADOS A CADA  2 MINUTOS >--> testes com RPi
    GetATLdhtTU();                                    // DHT22
    GetATLbmpPT();                                    // BMP-180
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
    char ST_dht[6], SU_dht[6], ST_bmp[6], SP_bmp[8], query[170];
    // CONVERTENDO DADOS DOS SENSORES PARA STRING
    dtostrf(T_dht, 2, 2, ST_dht);
    dtostrf(U_dht, 2, 2, SU_dht);
    dtostrf(T_bmp, 2, 2, ST_bmp);
    dtostrf(P_bmp, 4, 2, SP_bmp);

    mac.toCharArray(MAC, 25);
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
    sprintf(query, INSERT_SQL, MAC, ST_dht, SU_dht, ST_bmp, SP_bmp);
    // CONCATENANDO A STRING INSERT_SQL PARA GRAVACAO NO BANCO DE DADOS
    MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);

    digitalWrite(ATL3, LOW);        // GPIO-16 + LED0 | DESLIGA NO INICIO DA SUBIDA NO BANCO. EFEITO BLINK

    cur_mem->execute(query);        // SUBINDO DADOS PARA O BANCO
    delete cur_mem;                 // DELETANDO A QUERY EXECUTADA DA MEMORIA
  }
  yield();
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// MAIN FUNCTION END - FINAL
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
