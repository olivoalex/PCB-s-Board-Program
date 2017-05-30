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

   $_ip = debianInterfaceAtiva();

   $_mac = debianMAC($GLOBALS['interface_tipo']);

   $_now = debianDateTime();
   $_hoje = $_now[0];
   $_as = $_now[1];

   // STATUS para o BOOT sempre sera 1 com REDE ou 0 SEM REDE
   $_status = debianStatusRede();

   // Conectando com o Banco do RPI3
   $_pdo = ConectarBancoIp($_ip);

   // Atualizando os demais para -1
   $_sql = "insert into adm_raspberry (id, interface, ip, mac, evento, evento_status, data, hora) "
         . " values (?, ?, ?, ?, ?, ?, ?, ?)";

   $_stm = $_pdo->prepare($_sql);

   $_evento = 'BOOT';

   // Determinando os valores para o input
   $_stm->bindValue(1, 0);
   $_stm->bindValue(2, $GLOBALS['interface']);
   $_stm->bindValue(3, $_ip);
   $_stm->bindValue(4, $_mac);
   $_stm->bindValue(5, $_evento);
   $_stm->bindValue(6, $_status);
   $_stm->bindValue(7, $_hoje);
   $_stm->bindValue(8, $_as);

   $_stm->execute();

   if ( $_stm ) {
     echo 'ok';
   } else {
      // Apenas logando o ID pendente que deve estar sendo processado pelo script usado no AT daqui 5 minutos :)
      $_log = 'Falha em {$_hoje} as {$_as} ao Logar entrada no MySQL - BOOT\n';
      file_put_contents ('~pi/testes/entrada.log', $_log, FILE_APPEND);  
   }
?>