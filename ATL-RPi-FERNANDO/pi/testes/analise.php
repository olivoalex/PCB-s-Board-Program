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

   //$x = "Hello World\n"; echo "$x\n";
   //file_put_contents ('/tmp/teste.log', $x, FILE_APPEND);  

   $_ip = debianIP();

   $_mac = debianMAC();

   $_now = debianDateTime();
   $_hoje = $_now[0];
   $_as = $_now[1];

   $_pino_status = debianReadEnergia(24);

   // Conectando com o Banco do RPI3
   $_pdo = ConectarBanco();


   // Continua ZERO, deve fazer o SHUTDOWN
   if ( $_pino_status == "0" ) {

      // Atualizando os demais para -1
      $_sql = "update adm_energia set falha_pendente = false, shutdown_data = '{$_hoje}', shutdown_hora = '{$_as}', shutdown_necessario = 'S' "
            . " where falha_pendente = true ";

      $_stm = $_pdo->prepare($_sql);
      $_stm->execute();
      if ( $_stm ) { 
          echo "Sera feito um SHUTDOWN em seguida...\n";
      } else {
          echo "Internal Error on Internal SHUTDOWN not executed.\n"; 
      }

      // Apenas logando o ID pendente que deve estar sendo processado pelo script usado no AT daqui 5 minutos :)
      $_log = "Ira fazer o SHUTDOWN\n";
      file_put_contents ('/tmp/teste.log', $_log, FILE_APPEND);  
      echo $_log;


      $_cmd = "sudo reboot";
      $_cmd = "sudo shutdown -a now";
      $_ret = shell_exec($_cmd);

   } else {
      // Tem energia, libera a falha sem SHUTDOWN
      $_sql = "update adm_energia set falha_pendente = false, shutdown_necessario = 'N' "
            . " where falha_pendente = true ";
      $_stm = $_pdo->prepare($_sql);
      $_stm->execute();
      if ( $_stm ) { 
          echo "NAo Sera feito SHUTDOWN , emergia foi reestabelecida ..\n";
      } else {
          echo "Internal Error on Internal PENDENCIA not released.\n"; 
      }

      // Apenas logando o ID pendente que deve estar sendo processado pelo script usado no AT daqui 5 minutos :)
      $_log = "NAO sera necessario fazer o SHUTDOWN\n";
      file_put_contents ('/tmp/teste.log', $_log, FILE_APPEND);  
      echo $_log;
  }

?>
