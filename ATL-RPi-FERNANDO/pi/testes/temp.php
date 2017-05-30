<?php
//
// Autor: Fernando
// Data: 26-mai-2017
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

   echo "Ethernet:\n";
   $_ip = debianIp("rede");
   $_mac = debianMAC($GLOBALS['interface_tipo']);

   echo "\nWireless:\n";
   $_ip = debianIp("wifi");
   $_mac = debianMAC($GLOBALS['interface_tipo']);

   echo "\nSem Rede / Localhost:\n";
   $_ip = debianIp("localhost");
   $_mac = debianMAC($GLOBALS['interface_tipo']);

   echo "\nSituacao Atual/Interface Ativa:\n";
   $_ip = debianInterfaceAtiva();
   $_mac = debianMAC($GLOBALS['interface_tipo']);

   $_now = debianDateTime();
   $_hoje = $_now[0];
   $_as = $_now[1];

   // STATUS para o BOOT sempre sera 1 com REDE ou 0 SEM REDE
   $_status = debianStatusRede();
   echo "\nStatus de Rede: {$_status}\n";

   $_internet = debianInternet();
   echo "Status internet: {$_internet}\n";

   // dados de temperatura CPU e Ventilador como esta

   $_limite = debianVentiladorLimiteTemperatura();
   echo "Limite Ventilador ser Acionado: {$_limite}*C\n";

   $_maximo = debianCpuMaxTemperatura();
   echo "Temperatura Maxima par Shutdown: {$_maximo}*C\n";

   $_cpu = debianCpuTemperatura();
   echo "Temperatura da CPU: : {$_cpu}*C\n";

   $_ventilador = debianVentiladorStatus();
   echo "Status Ventilador: {$_ventilador}\n";

   $_descricao = debianPinoStatusDescricao($_ventilador);
   echo "Descricao Status Ventilador: {$_descricao}\n";

   $_invertido = debianInvertePinoStatus($_ventilador);
   echo "Status Invertido Ventilador: {$_invertido}\n";

   $_descricao =  debianPinoStatusDescricao($_invertido);
   echo "Descricao Status Invertido Ventilador: {$_descricao}\n";

?>
