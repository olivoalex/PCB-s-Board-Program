<?php
//
// Autor: Fernando
// Data: 08-Abr-2017
//
//  Conteudo:
//
//     	Biblioteca para pegar dados do debianRspyberryPi3
//
//      -> Determina o IP da eth0
//      -> Determina o MAC da eth0
//      -> Determina o DATA e HORA por TimeZone
//      -> Le status do GPIO que controla fluxo de energia para decidir se esta usando ou nao a BATERIA
//
//  Alteracoes por:
//
//    <dd-MMM-yyyy> - <Sr. Alterador>
//     --> comentarios
//

function debianIP() {
  // Pegando o IP da eth0
  $_cmd = "sudo ifconfig eth0 | grep \"inet addr\"";

  $_ip = shell_exec($_cmd);

  file_put_contents ('/tmp/teste.log', "IP: " . $_ip."\n", FILE_APPEND);

  $_tam = strlen($_ip);
  $_pos = strpos($_ip, "inet addr:") + 10;
  $_fim = $_tam - $_pos;
  $_ip = substr($_ip,$_pos,$_fim);
  $_pos = strpos($_ip, " ");
  $_ip = substr($_ip,0,$_pos);

  //echo "Len {$_tam}, Pos {$_pos} e Fim {$_fim}\n";
  echo "IP: {$_ip}\n";

  return $_ip;
}

function debianMAC() {

  // Pegando o MAC da eth0
  $_cmd = "sudo ifconfig eth0 | grep \"HWaddr\"";

  $_mac = shell_exec($_cmd);

  file_put_contents ('/tmp/teste.log', "MAC: " . $_mac."\n", FILE_APPEND);

  $_tam = strlen($_mac);
  $_pos = strpos($_mac, "HWaddr") + 7;
  $_fim = $_tam - $_pos;
  $_mac = strtoupper(substr($_mac,$_pos,$_fim));
  $_tam = strlen($_mac);
  $_fim = $_tam-2; // tiurando o espaco e o \n do final do texto
  $_mac = strtoupper(substr($_mac,0,$_fim));

  //echo "Len {$_tam}, Pos {$_pos} e Fim {$_fim}\n";
  echo "MAC: {$_mac}\n";

  return $_mac;
}

function debianDateTime() {

  // Determinando o LOCALE
  date_default_timezone_set("America/Sao_Paulo");

  // Determinando a Data
  $_hoje = date('Y-m-d');
  echo "Data: {$_hoje}\n";

  // Determinando a Hora
  $_as = date("H:i:s");

  echo "Hora: {$_as}\n";

  $_ret[0] = $_hoje;
  $_ret[1] = $_as;

  return $_ret;

}

function debianReadEnergia($_pino) {

   $_cmd = "/usr/local/bin/gpio -g mode {$_pino} in";
   $_status = shell_exec ($_cmd);

   $_cmd = "/usr/local/bin/gpio -g read {$_pino}";
   $_status = shell_exec ($_cmd);

   $msg = "Valor PIN {$_pino} = " . $_status . "\n";
   file_put_contents ('/tmp/teste.log', $msg, FILE_APPEND);

  // Na teoria iremos sempre ter umretorno 1 (com EMERGIA) ou 0 (usando a BATERIA)
  // Aqui estou tirando o \n que vem do linux para o PHP

  $_pino_status = substr($_status,0,1);

  echo "Status do Pino {$_pino} esta como {$_pino_status}\n";

  return $_pino_status;
}
?>
