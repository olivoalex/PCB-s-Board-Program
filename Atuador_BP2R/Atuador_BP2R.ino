/*                    VERSÃO FINAL DO CODIGO
                VERSÃO 1.0.0      DATA: 12/01/2017
                COMPILADO NA VERSAO ARDUINO: 1.8.1
                __________________________________

                VERSAO DO PROGRAMA ARDUINO: 1.8.1
                PROGRAMA ATUALIZADO EM: 15/01/2017
                HORA-ULTIMO UPDATE: 14:20 p.m
                __________________________________

                 PLACA WIFI ESP8266-07 AT THINKER
                 PROGRAMA: ATUADOR DE BAIXA POTENCIA
                 CONTÉM SENSORES: 2 RELES
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
     ATL5     >--> GPIO-12 + LED1
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
#include     <MySQL_Connection.h>  // CONEXAO COM BANCO DE DADOS
#include     <MySQL_Cursor.h>      // CONEXAO COM BANCO DE DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// AGROTECHLINK MINI ESTACAO CLIMATICA - PINOUTS - DEFINES - DESCRICOES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
#define      LED_BUILTIN   2    // LED_BUILTIN (LED NATIVO DO ESP8266)
#define      ATL7          5    // RELE 1
#define      ATL8          4    // RELE 2
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// DEFINICAO DAS VARIAVEIS GLOBAIS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
unsigned    T = 0;                      // DELETE AFTER TESTS ARE DONE
int         nCon, contNcon;             // VARIAVEIS PARA A BIBLIOTECA WiFi MANANGER (ASSEGURAR A CONEXAO COM A INTERNET EM CASO DE ERROS)
String      macAdress;
char        query[92], S_macAdress[30], rele_1[] = "rele_1", rele_2[] = "rele_2"; // VARIAVEIS PARA EXECUCAO DE QUERIES NO MySQL
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
// CONTROLE DE ATUAÇÃO DAS DUAS TOMADAS / RELES
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void atuaESP_BP2R(int comando, uint8_t PIN) {
  switch (comando) {
    case 1:
      digitalWrite(PIN, HIGH);
      Serial.println("Ligado");
      digitalWrite(LED_BUILTIN, LOW);
      break;
    case 0:
      digitalWrite(PIN, LOW);
      Serial.println("Desligado");
      digitalWrite(LED_BUILTIN, HIGH);
      break;
    default:
      digitalWrite(PIN, LOW);
      Serial.println("Desligado. Modo DEFAULT.");
      digitalWrite(LED_BUILTIN, HIGH);
      break;
  }
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// INICIO DO MODO SETUP DO ATUADOR ESP8266 BP-2R
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void setup() {
  Serial.begin(115200);
  pinMode(LED_BUILTIN, OUTPUT);         // INICIALIZA O LED_BUILTIN NATIVO DO ESP8266
  digitalWrite(LED_BUILTIN, HIGH);      // DESLIGA O LED_BUILTIN NATIVO DO ESP8266
  pinMode(ATL7, OUTPUT);      digitalWrite(ATL7, LOW);  // GPIO-05 + RELE 1
  pinMode(ATL8, OUTPUT);      digitalWrite(ATL8, LOW);  // GPIO-04 + RELE 2
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
  Serial.println("| Inicio da verificacao do modulo BP-2R - - - - - |");
  Serial.println("| Informacoes do ESP8266 - - - - - - - - - - - -  |");
  Serial.print("| MAC NUMBER: " + WiFi.macAddress());
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
  macAdress = WiFi.macAddress();

  macAdress.toCharArray(S_macAdress, 30);
  char INSERT_SQL[] = "INSERT INTO agrotech_intel.teste_atuador VALUES (NULL, '%s', '0', '0');";
  sprintf(query, INSERT_SQL, S_macAdress);
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  Serial.println("| Cadastrando ESP-MAC Adress no banco de dados -  |");
  cur_mem->execute(query);
  delay(500);
  Serial.println("Limpando dados de requisicao da memoria.");
  delete cur_mem;
  delay(500);
  Serial.println("| SETUP BEM SUCEDIDO ((: - - - - - - - - - - - - -|");
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FIM DO SETUP E CONFIGURACOES. INICIO DO LOOP.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void loop() {
  row_values *row_1 = NULL;
  row_values *row_2 = NULL;
  int head_count_1 = 0;
  int head_count_2 = 0;

  if (conn.connected()) {                                       // VERIFICA A CONEXAO COM O DB DO MySQL
    macAdress.toCharArray(S_macAdress, 30);                     // CONVERTE O MAC DE STRING PARA CHAR
    char SELECT_RELE_1_SQL[] = "SELECT %s FROM agrotech_intel.teste_atuador WHERE mac_number = '%s'";
    sprintf(query, SELECT_RELE_1_SQL, rele_1, S_macAdress);     // CONCATENANDO A STRING SELECT_SQL PARA BUSCA NO BANCO DE DADOS

    MySQL_Cursor *cur_mem_1 = new MySQL_Cursor(&conn);
    cur_mem_1 -> execute(query);
    delay(500);
    Serial.println("| Pesquisando no banco de dados - - - - - - - - - |");
    column_names *columns_1 = cur_mem_1 -> get_columns();        // PESQUISANDO POR COLUNAS. IMMPORTANTE DEVIDO A MEMORIA DO ESP8266 SER POUCA
    do {                                                         // LE O ROW SELECIONADO
      row_1 = cur_mem_1 -> get_next_row();
      if (row_1 != NULL) {
        head_count_1 = atol(row_1 -> values[0]);
      }
    } while (row_1 != NULL);
    delete cur_mem_1;                                            // LIMPANDO A MEMORIA DO ESP8266
    delay(500);
    
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
    
    char SELECT_RELE_2_SQL[] = "SELECT %s FROM agrotech_intel.teste_atuador WHERE mac_number = '%s'";
    sprintf(query, SELECT_RELE_2_SQL, rele_2, S_macAdress);      // CONCATENANDO A STRING SELECT_SQL PARA BUSCA NO BANCO DE DADOS

    MySQL_Cursor *cur_mem_2 = new MySQL_Cursor(&conn);
    cur_mem_2 -> execute(query);
    delay(500);
    column_names *columns_2 = cur_mem_2 -> get_columns();        // PESQUISANDO POR COLUNAS. IMMPORTANTE DEVIDO A MEMORIA DO ESP8266 SER POUCA
    do {                                                         // LE O ROW SELECIONADO
      row_2 = cur_mem_2 -> get_next_row();
      if (row_2 != NULL) {
        head_count_2 = atol(row_2 -> values[0]);
      }
    } while (row_2 != NULL);
    delete cur_mem_2;                                            // LIMPANDO A MEMORIA DO ESP8266
    delay(500);
    Serial.println(head_count_1);                                // RESUTLADO DO DB DO RELE 1
    Serial.println(head_count_2);                                // RESUTLADO DO DB DO RELE 2
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
    atuaESP_BP2R(head_count_1, ATL7);                            // ATUACAO NA PORTA A. RELE 1
    atuaESP_BP2R(head_count_2, ATL8);                            // ATUACAO NA PORTA B. RELE 2
    
  } else {                                                       // ASSEGURA A CONEXAO MySQL CASO HAJA FALHAS NO WiFi (:
    conn.close();
    Serial.println("| Conectando ao banco de dados da Agrotechlink... |");
    if (conn.connect(server_addr, 3306, user, password)) {
      delay(500);
      Serial.println("| Conexao com o banco de dados bem sucedida! (:   |");
    }
    else {
      Serial.println("| Não consigo mais me conectar ao banco de dados! |");
      Serial.println("| Vou reiniciar. Retorno em 8 segundos (:  - - -  |");
      delay(1000);
      ESP.reset();
      delay(3000);
    }
  }
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// MAIN FUNCTION END - FINAL
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
