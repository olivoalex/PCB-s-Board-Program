/*                    VERSÃO FINAL DO CODIGO
                VERSÃO 1.0.0      DATA: 12/01/2017
                COMPILADO NA VERSAO ARDUINO: 1.8.1
                __________________________________

                 PLACA WIFI ESP8266-07 AT THINKER
                 PROGRAMA: ATUADOR DE BAIXA POTENCIA
                 CONTÉM SENSORES: 2 RELES
                __________________________________

                CONFIGURACAO        DA PLACA PARA GRAVACAO
                PLACA:              GENERIC ESP8266 MODULE
                FLASH MODE:         DIO
                FLASH FREQUENCY:    40MHz
                CPU FREQUENCY:      80MHz
                FLASH SIZE:         1M (512K SPIFFS)
                DEBUG PORT:         DISABLED
                DEBUG LEVEL:        NENHUM
                RESET MOTHOD:       ck
                UPLOAD SPEED:       115200
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
#define      LED_BUILTIN   2    // LED_BUILTIN (LED NATIVO DO ESP8266)
#define      ATL3         16    // GPIO-16 + LED
#define      ATL7          4    // GPIO-04 + RELE 1 / A 
#define      ATL8          5    // GPIO-05 + RELE 2 / B
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// DEFINICAO DAS VARIAVEIS GLOBAIS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
//static const char   CPF[] = "09084678931";          // CPF DO USUARIO. APENAS NUMEROS!!!!
static const char   CPF[] = "01234567890";               // CPF DO USUARIO. APENAS NUMEROS!!!!
// ID DO PATO DONALD PARA TESTES...
String              macAdress;
char                query[200], S_macAdress[30];    // QUERY E MAC PARA BANCO DE DADOS
int                 fMysql;                         // VARIAVEl PARA MySQL EM CASO DE ERROS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// CONFIGURACOES DE ACESSO AO BANCO DE DADOS
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
IPAddress   server_addr (186, 202, 127, 122);   // IP DO MySQL SERVER - SITE AGROTECHLINK.COM
char        user[] = "agrotech_u_intel";        // USUARIO DO BANCO DE DADOS
char        password[] = "OlvAgrotechlink1357"; // SENHA DO USUARIO

WiFiClient client;
MySQL_Connection conn((Client *)&client);
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FAZER O LED PISCAR
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void LedATLblinks(unsigned M) {
  Serial.print("\nJUST LED0 IS BLINKING N TIMES: ");    Serial.println(M);
  for (short j = 0; j < M; j++) {
    Serial.print(j + 1);  Serial.print(" . ");
    digitalWrite(ATL3, HIGH);                     delay(300);
    digitalWrite(ATL3, LOW);                      delay(300);
  }
  digitalWrite(ATL3, LOW);
  digitalWrite(LED_BUILTIN, LOW);                 delay(300);
  digitalWrite(LED_BUILTIN, HIGH);
}
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
// INICIO DO MODO SETUP DO ATUADOR ESP8266 BP-2R
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void setup() {
  Serial.begin(115200);
  pinMode(LED_BUILTIN, OUTPUT);         // INICIALIZA O LED_BUILTIN NATIVO DO ESP8266
  digitalWrite(LED_BUILTIN, HIGH);      // DESLIGA O LED_BUILTIN NATIVO DO ESP8266
  pinMode(ATL3, OUTPUT);      digitalWrite(ATL3, LOW);  // GPIO-16 + LED0
  pinMode(ATL7, OUTPUT);      digitalWrite(ATL7, LOW);  // GPIO-05 + RELE 1 / A
  pinMode(ATL8, OUTPUT);      digitalWrite(ATL8, LOW);  // GPIO-04 + RELE 2 / B
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  WiFiManager wifiManager;
  wifiManager.setDebugOutput(false);
  wifiManager.autoConnect("Agrotechlink", "agrotechlink");
  delay(500);
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  while (conn.connect(server_addr, 3306, user, password) != true) {
    delay(500);
  }
  delay(500);
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  macAdress = WiFi.macAddress();
  macAdress.toCharArray(S_macAdress, 30);
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  row_values *row = NULL;
  long head_count = 0;

  char SELECT_SQL[] = "SELECT id_controlador FROM agrotech_intel.controlador_BP2R WHERE mac_number = '%s'";
  sprintf(query, SELECT_SQL, S_macAdress);
  MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
  cur_mem->execute(query);
  column_names *columns = cur_mem->get_columns();
  Serial.println("Passou aqui 1");
  do {
    row = cur_mem->get_next_row();
    if (row != NULL) {
      head_count = atol(row->values[0]);
    }
  } while (row != NULL);
  delete cur_mem;
  Serial.println(head_count);
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  if (head_count == 0 || NULL) {
    char  SRele1[] = "0", SRele2[] = "0", SAtivo[] = "1";
    char INSERT_SQL[] = "INSERT INTO agrotech_intel.controlador_BP2R SET cpf_usuario ='%s', mac_number ='%s', rele_A ='%s', rele_B ='%s', ativo='%s';";
    sprintf(query, INSERT_SQL, CPF, S_macAdress, SRele1, SRele2, SAtivo);
    MySQL_Cursor *cur_mem = new MySQL_Cursor(&conn);
    cur_mem->execute(query);
    delete cur_mem;
  }
  LedATLblinks(3);           // LED. 3 VEZES = PRIMEIRA CONEXAO COM BD OK!
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// FIM DO SETUP E CONFIGURACOES. INICIO DO LOOP.
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
void loop() {
  row_values *row_1 = NULL;
  row_values *row_2 = NULL;
  int head_count_1 = 0;
  int head_count_2 = 0;
  /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
  if (conn.connected()) {                                       // VERIFICA A CONEXAO COM O DB DO MySQL
    macAdress.toCharArray(S_macAdress, 30);                     // CONVERTE O MAC DE STRING PARA CHAR
    char SELECT_RELE_A_SQL[] = "SELECT rele_1 FROM agrotech_intel.teste_atuador WHERE mac_number = '%s';";
    sprintf(query, SELECT_RELE_A_SQL, S_macAdress);             // CONCATENANDO A STRING SELECT_SQL PARA BUSCA NO BANCO DE DADOS

    MySQL_Cursor *cur_mem_1 = new MySQL_Cursor(&conn);
    cur_mem_1 -> execute(query);
    column_names *columns_1 = cur_mem_1 -> get_columns();        // PESQUISANDO POR COLUNAS. IMPORTANTE DEVIDO A MEMORIA DO ESP8266 SER POUCA
    
    do {                                                         // LE O ROW SELECIONADO
      row_1 = cur_mem_1 -> get_next_row();
      if (row_1 != NULL) {
        head_count_1 = atol(row_1 -> values[0]);
      }
    } while (row_1 != NULL);
    
    delete cur_mem_1;                                             // LIMPANDO A MEMORIA DO ESP8266
    delay(500);
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
    char SELECT_RELE_B_SQL[] = "SELECT rele_2 FROM agrotech_intel.teste_atuador WHERE mac_number = '%s';";
    sprintf(query, SELECT_RELE_B_SQL, S_macAdress);              // CONCATENANDO A STRING SELECT_SQL PARA BUSCA NO BANCO DE DADOS

    MySQL_Cursor *cur_mem_2 = new MySQL_Cursor(&conn);
    cur_mem_2 -> execute(query);
    column_names *columns_2 = cur_mem_2 -> get_columns();        // PESQUISANDO POR COLUNAS. IMMPORTANTE DEVIDO A MEMORIA DO ESP8266 SER POUCA
    
    do {                                                         // LE O ROW SELECIONADO
      row_2 = cur_mem_2 -> get_next_row();
      if (row_2 != NULL) {
        head_count_2 = atol(row_2 -> values[0]);
      }
    } while (row_2 != NULL);
    
    delete cur_mem_2;                                            // LIMPANDO A MEMORIA DO ESP8266
    Serial.println(head_count_1);                                // RESUTLADO DO DB DO RELE 1
    Serial.println(head_count_2);                                // RESUTLADO DO DB DO RELE 2
    /* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
    atuaESP_BP2R(head_count_1, ATL7);                            // ATUACAO NA PORTA A. RELE 1
    atuaESP_BP2R(head_count_2, ATL8);                            // ATUACAO NA PORTA B. RELE 2

  } else {
    conn.close();
    if (conn.connect(server_addr, 3306, user, password)) {
      delay(500);
      fMysql++;
      if (fMysql == 5) {
        delay(5000);
        ESP.restart();
        delay(3000);
      }
    }
  }
}
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
// MAIN FUNCTION END - FINAL
/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -*/
