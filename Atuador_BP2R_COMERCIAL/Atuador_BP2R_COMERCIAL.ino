/*                    VERSÃO FINAL DO CODIGO
               VERSÃO 1.0.1      DATA: 12/01/2017
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
               DEBUG PORT:        DISABLED
               DEBUG LEVEL:       NENHUM
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
//                    TODOS OS DIREITOS RESERVADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/*   CADA GPIO POSSUI UMA IDENTIFICACAO ESPECIFICA
     PORTAS UTILIZADAS NAS PLACAS DA MINI ESTACAO CLIMATICA

     ATL3     >--> GPIO-16 + LED0
     ATL7     >--> GPIO-05 + RELE 1
     ATL8     >--> GPIO-04 + RELE 2
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
     OU USB DO COMPUTADOR!*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// LIVRARIAS EXTERNAS PARA FUNCIONAMENTO DOS SENSORES, CONEXÃO E DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#include     <ESP8266WiFi.h>       // BIBLIOTECA WiFi DO ESP8266
#include     <DNSServer.h>         // BIBLIOTECA WiFi DO ESP8266
#include     <ESP8266WebServer.h>  // BIBLIOTECA WiFi DO ESP8266
#include     <WiFiManager.h>       // BIBLIOTECA WiFi-MANANGER DO ESP8266  
#include     <MySQL_Connection.h>  // CONEXAO COM BANCO DE DADOS
#include     <MySQL_Cursor.h>      // CONEXAO COM BANCO DE DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// AGROTECHLINK MINI ESTACAO CLIMATICA - PINOUTS - DEFINES - DESCRICOES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#define      ATL3         16    // GPIO-16 + LED0
#define      ATL4         15    // GPIO-15 + ESTADO NORMAL DO ESP / PERMITE ROTINAS E RESTART
#define      ATL7          4    // GPIO-04 + RELE 1 / A 
#define      ATL8          5    // GPIO-05 + RELE 2 / B
#define      ATL9          2    // GPIO-02 + LED NATIVO DO ESP8266 / PERMITE ROTINAS E RESTART
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// DEFINICAO DAS VARIAVEIS GLOBAIS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
String              macAdress;
char                query[100], S_macAdress[30];         // MAC PARA O MySQL
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// QUERY PARAS AS INTERACOES COM O BANCO DE DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
char INSERT_SQL[] = "INSERT INTO agrotech_intel.cont_BP2R SET mac='%s'";
char SELECT_RELE_A_SQL[] = "SELECT r_A FROM agrotech_intel.cont_BP2R WHERE mac='%s' LIMIT 1";
char SELECT_RELE_B_SQL[] = "SELECT r_B FROM agrotech_intel.cont_BP2R WHERE mac='%s' LIMIT 1";
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// CONFIGURACOES DE ACESSO AO BANCO DE DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
IPAddress   server_addr (186, 202, 127, 122);      // IP DO MySQL SERVER - SITE AGROTECHLINK.COM
char        user[] = "agrotech_u_intel";           // USUARIO DO BANCO DE DADOS
char        password[] = "OlvAgrotechlink1357";    // SENHA DO USUARIO

WiFiClient client;
MySQL_Connection conn((Client *)&client);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// CALLBACK DO WIFIMANANGER
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void configModeCallback (WiFiManager *myWiFiManager) {
  WiFi.softAPIP();
  myWiFiManager->getConfigPortalSSID();
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FAZER O LED PISCAR
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void LedATLblinks(unsigned M) {
  for (unsigned short j = 0; j < M; j++) {
    digitalWrite(ATL3, HIGH); delay(500);
    digitalWrite(ATL3, LOW);
  }
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// CONTROLE DE ATUAÇÃO DAS DUAS TOMADAS / RELES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void atuaESP_BP2R(int comando, uint8_t PIN) {
  switch (comando) {
    case 1:
      digitalWrite(PIN, HIGH);
      LedATLblinks(1);
      break;
    case 0:
      digitalWrite(PIN, LOW);
      LedATLblinks(1);
      break;
    default:
      digitalWrite(PIN, LOW);
      LedATLblinks(1);
      break;
  }
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// INICIO DO MODO SETUP DO ATUADOR ESP8266 BP-2R
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void setup() {
  pinMode(ATL3, OUTPUT);     digitalWrite(ATL3, HIGH);   // GPIO-16 + LED0
  pinMode(ATL4, OUTPUT);     digitalWrite(ATL4, HIGH);   // GPIO-15 + ESTADO NORMAL DO ESP / HIGH
  pinMode(ATL9, OUTPUT);     digitalWrite(ATL9, HIGH);   // GPIO-02 + ESTADO NORMAL DO ESP / HIGH
  pinMode(ATL7, OUTPUT);     digitalWrite(ATL7, LOW);    // GPIO-05 + RELE 1 / A
  pinMode(ATL8, OUTPUT);     digitalWrite(ATL8, LOW);    // GPIO-04 + RELE 2 / B
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  WiFiManager wifiManager;
  wifiManager.setDebugOutput(false);
  wifiManager.setAPCallback(configModeCallback);
  wifiManager.autoConnect("Agrotechlink", "agrotechlink");
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  while (conn.connect(server_addr, 3306, user, password) != true) {
    yield();
  }
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  macAdress = WiFi.macAddress();
  macAdress.toCharArray(S_macAdress, 30);
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  sprintf(query, INSERT_SQL, S_macAdress);
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  cur_mem->execute(query);
  delete cur_mem;                 // DELETANDO A QUERY EXECUTADA DA MEMORIA
  conn.close();                   // ENCERRANDO CONEXAO COM BANCO DE DADOS
  digitalWrite(ATL3, LOW);        // GPIO-16 + LED0 / DESLIGA SETUP OK!

}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FIM DO SETUP E CONFIGURACOES. INICIO DO LOOP.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void loop() {
  row_values *row_1 = NULL;
  row_values *row_2 = NULL;
  int head_count_1 = 0;
  int head_count_2 = 0;

  int conexao = WiFi.status();

  switch (conexao) {
    case WL_CONNECTED: {
        while (conn.connect(server_addr, 3306, user, password) != true) {
          yield();
        }
        macAdress.toCharArray(S_macAdress, 30);                   // CONVERTE O MAC DE STRING PARA CHAR
        sprintf(query, SELECT_RELE_A_SQL, S_macAdress);           // CONCATENANDO A STRING SELECT_SQL PARA BUSCA NO BANCO DE DADOS

        MySQL_Cursor *cur_mem_1 = new MySQL_Cursor(&conn);
        cur_mem_1 -> execute(query);
        column_names *columns_1 = cur_mem_1 -> get_columns();     // PESQUISANDO POR COLUNAS. IMPORTANTE DEVIDO A MEMORIA DO ESP8266 SER POUCA

        do {                                                      // LE O ROW SELECIONADO
          row_1 = cur_mem_1 -> get_next_row();
          if (row_1 != NULL) {
            head_count_1 = atol(row_1 -> values[0]);
          }
        } while (row_1 != NULL);

        delete cur_mem_1;                                         // LIMPANDO A MEMORIA DO ESP8266
        /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
        sprintf(query, SELECT_RELE_B_SQL, S_macAdress);           // CONCATENANDO A STRING SELECT_SQL PARA BUSCA NO BANCO DE DADOS

        MySQL_Cursor *cur_mem_2 = new MySQL_Cursor(&conn);
        cur_mem_2 -> execute(query);
        column_names *columns_2 = cur_mem_2 -> get_columns();     // PESQUISANDO POR COLUNAS. IMMPORTANTE DEVIDO A MEMORIA DO ESP8266 SER POUCA

        do {                                                      // LE O ROW SELECIONADO
          row_2 = cur_mem_2 -> get_next_row();
          if (row_2 != NULL) {
            head_count_2 = atol(row_2 -> values[0]);
          }
        } while (row_2 != NULL);

        delete cur_mem_2;                                         // LIMPANDO A MEMORIA DO ESP8266
        conn.close();                                             // ENCERRANDO CONEXAO COM BANCO DE DADOS
        /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
        atuaESP_BP2R(head_count_1, ATL7);                         // ATUACAO NA PORTA A. RELE 1
        atuaESP_BP2R(head_count_2, ATL8);                         // ATUACAO NA PORTA B. RELE 2
        /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
      }
      break;

    default: {
        ESP.restart();
      }
      break;
  }
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// MAIN FUNCTION END - FINAL
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
