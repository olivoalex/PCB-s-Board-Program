<?php
//
// Autor: Fernando
// Data: 08-Abr-2017
//
//  Conteudo:
//
//     	Biblioteca para pegar dados do debianRspyberryPi3
//
//      -> Determina o IP cfme tipo: eth0, wlan01 ou lo (localhost)
//      -> Determina o MAC da eth0
//      -> Determina o DATA e HORA por TimeZone
//      -> Le status do GPIO que controla fluxo de energia para decidir se esta usando ou nao a BATERIA
//
//  Alteracoes por:
//
//    <dd-MMM-yyyy> - <Sr. Alterador>
//     --> comentarios
//

// Interface usada coo default sera a LOCALHOST
// global $_interface; 
// global $_interface_tipo;
// global $_interface_ip;
// global $_interface_mac;

function debianTipoInterface($_tipo) {
 
  $_tipo = strtoupper($_tipo);
  
  $_tipo_interface = "lo";
  
  switch ( $_tipo ) {
	  case "REDE" :
	     $_tipo_interface = "eth0";
	     break;
	  case "WIFI" :
	     $_tipo_interface = "wlan0";
	     break;
	  case "LOCALHOST" :
	     $_tipo_interface = "lo";
		 break;
	  default :
	     $_tipo_interface = "lo";
  }   
  
  // Atribui a interface como GLOBAL
  $GLOBALS['interface'] = $_tipo_interface;
  $GLOBALS['interface_tipo'] = $_tipo;

  return $_tipo_interface;  
}

function debianInterfaceAtiva() {
	// tenta descobrir qual esta ativa na seguinte prioridade
	// rede (cabo) - eth0
	// wifi (wireless) - wlan01
	// localhost (sem rede) - lo
	
	$_ip = debianIP("rede");
	if ( $_ip == "NONE" ) {
	   $_ip = debianIP("wifi");
       if ( $_ip == "NONE") {
		   $_ip = debianIP("lo");
	   }		   
	}  
	
	//echo "Interface: {$GLOBALS['interface']} com IP: {$_ip}\n";
    //echo "Interface Tipo: {$GLOBALS['interface_tipo']}\n";
	
	return $_ip;
}

function debianStatusRede() {
   
   $_interface = $GLOBALS['interface'];
   
   // STATUS para o BOOT sempre sera 1 com REDE ou 0 SEM REDE
   $_status = 0; // Local host, sem rede
   if ( $_interface != "lo" ) {
	   $_status = 1; // com rede wifi ou ethernet
   } 	
  
   return $_status;
}

function debianIP($_tipo) {
 
  // identificando qual o tipo de interface a ser buscada
  $_tipo_interface = debianTipoInterface($_tipo);
  
  // Pegando o IP cfme o tipo
  $_cmd = "sudo ifconfig {$_tipo_interface} | grep \"inet addr\"";

  //echo "Comando IP: {$_cmd}\n";

  $_ip = shell_exec($_cmd);

  // Logando addos analisados conforme o tipo de conexao: rede cabeada, wifi e sem rede - localhost
  $_msg = "IP: {$_ip}, Type: {$_tipo_interface}, Parameter: {$_tipo}\n";
  file_put_contents ('/home/pi/testes/teste.log', $_msg, FILE_APPEND);

  $_tam = strlen($_ip);
  $_pos = strpos($_ip, "inet addr:") + 10;
  $_fim = $_tam - $_pos;
  $_ip = substr($_ip,$_pos,$_fim);
  $_pos = strpos($_ip, " ");
  $_ip = substr($_ip,0,$_pos);
  
  // tratando o retorno para definir como fica o IP
  if ( empty($_ip) || $_ip == null || $_ip == " " || $_ip == "") {
	  $_ip = "NONE";
  }

  ////echo "Len {$_tam}, Pos {$_pos} e Fim {$_fim}\n";
  //echo "IP: {$_ip}\n";
  
  $GLOBALS['interface_ip'] = $_ip;
  
  return $_ip;
}

function debianMAC($_tipo) {
  // identificando qual o tipo de interface a ser buscada
  $_tipo_interface = debianTipoInterface($_tipo);
  
  // Pegando o MAC da eth0
  $_cmd = "sudo ifconfig {$_tipo_interface} | grep \"HWaddr\"";

  $_mac = shell_exec($_cmd);

  // Logando addos analisados conforme o tipo de conexao: rede cabeada, wifi e sem rede - localhost
  $_msg = "MAC: {$_mac}, Type: {$_tipo_interface}, Parameter: {$_tipo}\n";
  file_put_contents ('/home/pi/testes/teste.log', $_msg, FILE_APPEND);

  $_tam = strlen($_mac);
  $_pos = strpos($_mac, "HWaddr") + 7;
  $_fim = $_tam - $_pos;
  $_mac = strtoupper(substr($_mac,$_pos,$_fim));
  $_tam = strlen($_mac);
  $_fim = $_tam-2; // tirando o espaco e o \n do final do texto
  $_mac = strtoupper(substr($_mac,0,$_fim));

  // tratando o retorno para definir como fica o MAC
  if ( empty($_mac) || $_mac == null || $_mac == " " || $_mac == "") {
	  $_mac = "NONE";
  }
  $GLOBALS['interface_mac'] = $_mac;
  
  ////echo "Len {$_tam}, Pos {$_pos} e Fim {$_fim}\n";
  ////echo "MAC: {$_mac}\n";  
  
  return $_mac;
}

function debianDateTime() {

  // Determinando o LOCALE
  date_default_timezone_set("America/Sao_Paulo");

  // Determinando a Data
  $_hoje = date('Y-m-d');
  ////echo "Data: {$_hoje}\n";

  // Determinando a Hora
  $_as = date("H:i:s");

  ////echo "Hora: {$_as}\n"; 
  $_ret[0] = $_hoje;
  $_ret[1] = $_as;

  return $_ret;

}

function debianReadEnergia() {
	// Energia esta no GPIO 2 (DOIS)
	return debianGpioReadOutput(2);
}

function debianGpioReadOutput($_pino) {

   $_cmd = "/usr/local/bin/gpio -g mode {$_pino} out";
   $_status = shell_exec ($_cmd);

   $_cmd = "/usr/local/bin/gpio -g read {$_pino}";
   $_status = shell_exec ($_cmd);

   $msg = "Valor PIN {$_pino} = " . $_status . "\n";
   file_put_contents ('/home/pi/testes/teste.log', $msg, FILE_APPEND);

  // Na teoria iremos sempre ter umretorno 1 (com EMERGIA) ou 0 (usando a BATERIA)
  // Aqui estou tirando o \n que vem do linux para o PHP

  $_pino_status = substr($_status,0,1);

  //echo "Read Output, no Pino {$_pino}, status {$_pino_status}\n";

  return $_pino_status;
}

function debianGpioReadInput($_pino) {

   $_cmd = "/usr/local/bin/gpio -g mode {$_pino} in";
   $_status = shell_exec ($_cmd);

   $_cmd = "/usr/local/bin/gpio -g read {$_pino}";
   $_status = shell_exec ($_cmd);

   $msg = "Valor PIN {$_pino} = " . $_status . "\n";
   file_put_contents ('/home/pi/testes/teste.log', $msg, FILE_APPEND);

  // Na teoria iremos sempre ter umretorno 1 (com EMERGIA) ou 0 (usando a BATERIA)
  // Aqui estou tirando o \n que vem do linux para o PHP

  $_pino_status = substr($_status,0,1);

  //echo "Read Input, no Pino {$_pino}, status {$_pino_status}\n";

  return $_pino_status;
}

function debianGpioWriteOutput($_pino, $_onoff) {

   $_cmd = "/usr/local/bin/gpio -g mode {$_pino} out";
   $_status = shell_exec ($_cmd);

   $_cmd = "/usr/local/bin/gpio -g write {$_pino} {$_onoff}";
   $_status = shell_exec ($_cmd);

   $msg = "Valor PIN {$_pino} = " . $_status . "\n";
   file_put_contents ('/home/pi/testes/teste.log', $msg, FILE_APPEND);

  // Na teoria iremos sempre ter umretorno 1 (com EMERGIA) ou 0 (usando a BATERIA)
  // Aqui estou tirando o \n que vem do linux para o PHP

  $_pino_status = substr($_status,0,1);

  //echo "Write Output {$_onoff}, no Pino {$_pino}, status {$_pino_status}\n";

  return $_pino_status;
}

function debianGpioWriteInput($_pino, $_onoff) {

   $_cmd = "/usr/local/bin/gpio -g mode {$_pino} in";
   $_status = shell_exec ($_cmd);

   $_cmd = "/usr/local/bin/gpio -g write {$_pino} {$_onoff}";
   $_status = shell_exec ($_cmd);

   $msg = "Valor PIN {$_pino} = " . $_status . "\n";
   file_put_contents ('/home/pi/testes/teste.log', $msg, FILE_APPEND);

  // Na teoria iremos sempre ter umretorno 1 (com EMERGIA) ou 0 (usando a BATERIA)
  // Aqui estou tirando o \n que vem do linux para o PHP

  $_pino_status = substr($_status,0,1);

  //echo "Write Input {$_onoff}, no Pino {$_pino}, status {$_pino_status}\n";

  return $_pino_status;
}

function debianGpioMode($_pino, $_mode) {

   $_cmd = "/usr/local/bin/gpio -g mode {$_pino} {$_mode}";
   $_status = shell_exec ($_cmd);
   
   $_status = substr($_status,0,1);
   
   if ( $_status == "0" ) {
	   return true; // Ok
   } else {
	   return false; // Com algum erro no exec
   }
}

function debianInternet() {
   $_ok = false;

   $_cmd = "sh ~pi/testes/temInternet.sh";

   $_status = shell_exec($_cmd);

   // Internet nao esta funcionando ou esta, retorna TRUE ok e FALSE nok
   if ( strpos($_status, "FUNCIONANDO",0) != 0 ) {
      $_ok = true;
   } else {
      $_ok = false;
   }

   return $_ok;
}

function debianDominioOnline($_dominio) {
   $_ok = false;

   $_cmd = "sh ~pi/testes/verificaDominio.sh {$_dominio}";

   $_status = shell_exec($_cmd);

   // Internet nao esta funcionando ou esta, retorna TRUE ok e FALSE nok
   if ( strpos($_status, "FUNCIONANDO",0) != 0 ) {
      $_ok = true;
   } else {
      $_ok = false;
   }

   return $_ok;	
}


function debianVentiladorLimiteTemperatura() {

   // VALOR LIMITE PARA LIGAR OU DESLIGAR A VENTILACAO
   return 45000/1000;         

}

function debianCpuMaxTemperatura() {

   // RPi SUPORTA NO MAXIMO 80 *C --> 75 SEGURANCA
   return 75000/1000;                      

}

function debianCpuTemperatura() {

   $tempCPU = trim(shell_exec("cat /sys/class/thermal/thermal_zone0/temp"));

   return $tempCPU / 1000;

}

function debianVentiladorStatus() {

   return debianGpioReadOutput(18);
}

function debianInvertePinoStatus($_status) {
   
   if ( $_status == 1 ) {
      return 0;
   } else {
      return 1;
   }
}

function debianPinoStatusDescricao($_status) {
   
   if ( $_status == 0 ) {
     return "Desligado";
   } else { 
     return "Ligado";
   }
}
?>
