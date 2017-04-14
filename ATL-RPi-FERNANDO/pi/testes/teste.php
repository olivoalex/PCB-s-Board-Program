<?php
//
// Autor: Fernando
// Data: 08-Abr-2017
//
//  Conteudo:
//
//     	Script para verificar GPIO de controle de fluxo de ENERGIA
//
//     	Pino: ??
//
//     	Se pino estiver consumindo EMERGIA deve retornar o valor 1
//     	Se pino NAO estiver consumindo EMERGIA e usando a BATERIA, deve retornar o valor 0
//     	Esse script vai gerar um registro na tabela ADM_ENERGIA com falha_pendente TRUE, isso
//     servira pra indicar que um OUTRO script deve ser disparado com o comando AT (linux) para verificar e tomar uma decisao de
//     desligar ou nao o RASPBERRY-PI3. Antes, deve colocar esse falha_pendente como FALSE indicando que foi tomada uma DECISAO
//
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

  // Aciona INSERT pena quando estiver com PIN igual a ZERO desligado, 1 indica LIGADO ou com energia e sem energia
  if ( $_pino_status == "0" ) {

     // Conectando com o Banco do RPI3
     $_pdo = ConectarBanco();

     // Veririca se tem pendencia
     $_sql = "select * from adm_energia where falha_pendente = true order by falha_data desc, falha_hora desc, id desc";
     // Preparando comando SQL
     $_stm = $_pdo->prepare($_sql);
     // Executand o a query conforme os parametros indicados
     $_stm->execute();
     $_db_ret = $_stm->fetchAll(PDO::FETCH_ASSOC);
     if ( $_db_ret) {
        // Percorrendo o resultado
        // determina qual ID usar, o mais recente os antigos coloca como -1, defeito
        $_id = 0;
        if ( count($_db_ret) > 1 ) {
           // guarda o primeiro ID e descarta os outros com erro
           $_id = $_db_ret[0]["id"];
           // Atualizando os demais para -1
           $_sql = "update adm_energia set falha_pendente = -1 where falha_pendente = true and id != {$_id}";
           $_stm = $_pdo->prepare($_sql);
           $_stm->execute();
           if ( $_stm ) { 
             echo "Change Internal Controls with failue...\n";
           } else {
             echo "Internal Error on Internal Controls.\n"; 
           }
        } else {
           // guarda o ID nao tem o que descartar 
           $_id = $_db_ret[0]["id"];
        }

        // Apenas logando o ID pendente que deve estar sendo processado pelo script usado no AT daqui 5 minutos :)
        $_log = "ID {$_id}, sendo analisado\n";
        file_put_contents ('/tmp/teste.log', $_log, FILE_APPEND);  
        echo $_log;

     } else {
       // Nao tem nada pendente
       // Buscando dados para um CPF ou um MAC ou para ambos
       $_sql = "insert into adm_energia (id, ip, mac, falha_data, falha_hora, falha_pendente) "
	     .  " values (?, ?, ?, ?, ?, ?) ";
   
       // Preparando comando SQL
       $_stm = $_pdo->prepare($_sql);
   
       // Determinando os valores para o input
       $_stm->bindValue(1, 0);
       $_stm->bindValue(2, $_ip);
       $_stm->bindValue(3, $_mac);
       $_stm->bindValue(4, $_hoje);
       $_stm->bindValue(5, $_as);
       $_stm->bindValue(6, true);
	     	
       // Executand o a query conforme os parametros indicados
       $_stm->execute();
     
       if ( $_stm ) { 
          echo "\nLogged...\n";
       } else {
         echo "\nInternal Error on LOG on Database History.\n"; 
       }

       // programando para analisar falha ENERGIA para daqui 5 minutos
       $_cmd = "at now +5 minutes < ~pi/testes/analise.sh";
       $_ret = shell_exec($_cmd);

     }
  }

?>
