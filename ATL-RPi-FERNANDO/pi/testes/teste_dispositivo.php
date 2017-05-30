<?php
//
// Autor: Fernando
// Data: 08-Abr-2017
//
//  Conteudo:
//
//  Alteracoes por:
//
//    <dd-MMM-yyyy> - <Sr. Alterador>
//     --> comentarios
//
   session_start();

   include "/var/www/html/App4Web/config.php";
   include "/var/www/html/App4Web/Include/conectarBanco.php";
   include "/var/www/html/App4Web/Include/debianRaspBerryPi3.php";
   
   // Esquema das portas GPIO a serem manipuladas
   //                             GPIO,MODE,DESCRICAO
   $_gpio["LED-VD"]       = array( 2,"out","Led Verde");
   $_gpio["LED-AM"]       = array( 4,"out","Led Amarelo");
   $_gpio["LED-VM"]       = array(15,"out","Led Vermelho");
   $_gpio["LED-B-VD"]     = array(27,"out","Led-B Verde");
   $_gpio["LED-B-VM"]     = array(18,"out","Led-B Vermelho");
   $_gpio["FAN-CTRL"]     = array(17,"out","Ventilador");
   $_gpio["BAT-OUT-CTRL"] = array(14,"out","Bateria");
   $_gpio["NET-FAIL"]     = array( 3,"in","Rede in Falha");
   
   // Verificando a rede   
   // IP
   $_ip = debianInterfaceAtiva();
   // MAC
   $_mac = debianMAC($GLOBALS['interface_tipo']);
   // STATUS para o BOOT sempre sera 1 com REDE ou 0 SEM REDE
   $_status = debianStatusRede();
   // Stats Internet
   $_internet = debianInternet();   
   
   // Ligar o NET-FAIL antes de tudo
   // Ajusta o MODE do gpio
   $_pino_netfail      = $_gpio["NET-FAIL"][0];
   $_mode_netfail      = $_gpio["NET-FAIL"][1];
   $_descricao_netfail = $_gpio["NET-FAIL"][2];
   echo "Ligando NET-FAIL, {$_descricao_netfail} onde MODO={$_mode_netfail} e GPIO eh {$_pino_netfail}.\n\n";
   // LIGA o pino, esta deixando ligado
   debianGpioWtiteInput($_pino_netfail,1);
   sleep(3);
			   
   // Percorrer e testar :)
   foreach($_gpio as $_chave => $_valor) {
	   
	   
	   if ( $_chave != "NET-FAIL" ) {
		   // Ajusta o MODE do gpio
		   $_pino      = $_valor[0];
		   $_mode      = $_valor[1];
		   $_descricao = $_valor[2];		
           
           echo "Testando {$_chave}, {$_descricao} onde MODO={$_modo} e GPIO eh {$_pino}.\n\n";
		   
		   if ( strtolower($_mode) == "out" ) {
			   // Le o Pino
			   debianGpioReadOutput($_pino);
			   // LIGA o pino
			   debianGpioWtiteOutput($_pino,1);
			   // Espera 3 segundos
			   sleep(3);
			   // Deslig o Pino
			   debianGpioWtiteOutput($_pino,0);
		   } else {
			   // Le o Pino
			   debianGpioReadInput($_pino);
			   // LIGA o pino
			   debianGpioWtiteInput($_pino,1);
			   // Espera 3 segundos
			   sleep(3)
			   // Deslig o Pino
			   debianGpioWtiteInput($_pino,0);
		   }
		   sleep(2);
	   }
	   
      // Desligandoecho
	  echo "Desligando NET-FAIL, {$_descricao_netfail} onde MODO={$_modo_netfail} e GPIO eh {$_pino_netfail}.\n\n";
      // DESLIGA o pino
      debianGpioWtiteInput($_pino_netfail,0);
	  // Encerrando testes
	  echo "Teste finalizado.";
   }
?>