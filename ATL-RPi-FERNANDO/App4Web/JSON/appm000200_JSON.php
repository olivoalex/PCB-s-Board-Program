<?php
    // Previsto receber:
    //   
    //      acao
    //      cpf
    ///     mac
    //
    //      id: saida A ou B
    //      linha
    //      pino ( da gpio para ida a e b )
    //

    // Tratando acentuacao :(
    header('Content-Type: text/html; charset=UTF-8',true);
    
    include("Include/conectarRPI3Include.php");
       
    $_errors = array();
	$_return = array();
	$_acao   = "NONE";
	$_id     = 0;
	$_status = true;
	$_mensagem = "";
	$_error  = "";

	$_CURRENT_DATE     = date('Y-m-d');
	
	$_CURRENT_TIME     = date('H:i:s');
	
	$_CURRENT_DATETIME = date('Y-m-d H:i:s');
	
	// Procurando os controles para poder serem executados
	// Tarefa
	if (isset($_POST['acao'])) {
		$_acao = $_POST['acao'];
		if (isset($_POST['id'])) {
			$_id = $_POST['id'];
		}
	} else {
		if (isset($_GET['acao'])) {
			$_acao = $_GET['acao'];
			if (isset($_GET['id'])) {
				$_id = $_GET['id'];
			}
		}
	}

	//Recebe os parâmetros enviados via GET
	$_acao = (isset($_GET['acao'])) ? $_GET['acao'] : '';
	$_complemento = (isset($_GET['complemento'])) ? $_GET['complemento'] : 'NONE';
	$_parametro = (isset($_GET['parametro'])) ? $_GET['parametro'] : '';
	
	// Preparando comunicacao com DB
	$_db = new Model();
	
	$_empresa        = isset($_GET['empresa']) ? $_GET['empresa'] : '';
	$_filial         = isset($_GET['filial']) ? $_GET['filial'] : '';
	$_usuario        = isset($_GET['usuario']) ? $_GET['usuario'] : '';
	
	$_cpf            = isset($_GET['cpf']) ? $_GET['cpf'] : 'NONE';	
	$_mac            = isset($_GET['mac']) ? $_GET['mac'] : 'NONE';
	
	$_saida          = isset($_GET['saida']) ? $_GET['saida'] : 'NONE';
	$_linha          = isset($_GET['linha']) ? $_GET['linha'] : 'NONE';
	$_pino           = isset($_GET['pino']) ? $_GET['pino'] : 'NONE';
	$_saida_descricao= isset($_GET['saida_descricao']) ? $_GET['saida_descricao'] : 'NONE';

	//echo "<br>Acao: {$_acao}";
	//echo "<br>Complemento  : {$_complemento}";
	//echo "<br>Parametro    : {$_parametro}";
	//echo "<br>Empresa      : {$_empresa}";
	//echo "<br>Filial       : {$_filial}";
	//echo "<br>Usuario      : {$_usuario}";
	//echo "<br>CPF          : {$_cpf}";
	//echo "<br>MAC          : {$_mac}";
	//echo "<br>Saida        : {$_saida}";
	//echo "<br>Saida Descr  : {$_saida_descricao}";
	//echo "<br>Linha        : {$_linha}";
	//echo "<br>Pino         : {$_pino}";
	//echo "<br><br>Data     : {$_CURRENT_DATE}";
	//echo "<br>Hora         : {$_CURRENT_TIME}";
	//echo "<br>TimeStamp    : {$_CURRENT_DATETIME}";
	
	// Associativo para receber dados 	
	$_dados = array();
	
	// Habilita ou nao a validacao da GPIO para cada acinador saida A e B
	$_GPIO = true; 
	
	// Tratando os pinos e seus status
    $_pinos_gpio_regtorno = -1; // Retorno do Linux para comandos via shell_exec;
	$_pinos_comando = ""; // comando para shell_exec
	$_pino_a = ["disabled","","Desligado",0]; // botao_on, boto_off, status e pino da gpio
	$_pino_b = ["disabled","","Desligado",0]; // botao_on, boto_off, status e pino da gpio
			
	//
	// Acoes:
	//
	//   -> PROCESSAR
	//         Carrega as estacoes e identifica o status de cada uma
	//
	//   -> GPIO-READ
	//         Le o status do PINO solicitado na GPIO-READ
	//
	//   -> GPIO-WRITE-ON
	//         Grava no PINO indicado o valor 1 para LIGAR
	//
	//   -> GPIO-WRITE-OFF
	//         Grava no PINO indicado o valor 0 para DESLIGAR
	//
	
	if ( $_acao == "PROCESSAR" ) {
		// Conectando com o Banco do RPI3
		$_pdo = ConectarRPI3();
		
		// Buscando dados para um CPF ou um MAC ou para ambos
		$_sql = "SELECT a.id as id, a.cpf as cpf, a.mac as mac, a.nome as descricao, a.saidaA, a.saidaB "
			 .  " FROM adm_cont_BP2R a "
			 .         " WHERE 1=1 ";
		// Foi informado um CPF valido
		if ( $_cpf != "NONE" ) {
			$_sql .= " AND a.cpf = ? "; //'01234567890'
		}
		// Foi informado um MAC valido
		if ( $_mac != "NONE" ) {
			$_sql .= " AND a.mac = ? "; //'60:01:94:07:4F:F7'
		}
		$_sql .=     " ORDER BY 4,1";
					 
		$_stm = $_pdo->prepare($_sql);
		if ( $_cpf != "NONE" ) {
		   $_stm->bindValue(1, $_cpf);
		}
		if ( $_mac != "NONE" ) {
		   $_stm->bindValue(2, $_mac);
		}
		
		//echo "<br>SQL: ".$_sql;
		
		// Executand o a query conforme os parametros indicados
		$_stm->execute();
		$_db_ret = $_stm->fetchAll(PDO::FETCH_ASSOC);
		if ( $_db_ret) {
		   // Percorrendo o recultado
		   for ($i=0; $i < count($_db_ret); $i++) {		
			  $_dados["ACIONADOR"][$i] = $_db_ret[$i];
			  //
			  // SAIDA A
			  //			
			  // pegando status do pino
			  $_pinos_gpio_retorno = 0; // Desligado
			  // Pino da saida A diferente de NONE
			  $_pino_a[3] = $_db_ret[$i]["saidaA"]; 
			  if ( $_pino_a[3] != "NONE" ) {
			     if ($_GPIO == true) {
					 
					 
					$_pinos_comando = "/usr/local/bin/gpio -g mode " . $_pino_a[3] . " out";
			        $_pinos_gpio_retorno = shell_exec($_pinos_comando);
				    $_pinos_comando = "/usr/local/bin/gpio -g read " . $_pino_a[3];
			        $_pinos_gpio_retorno = shell_exec($_pinos_comando);
				 }
			  }
			  // indicando para o portal o status do PINO saida A
			  $_dados["ACIONADOR"][$i]["saidaA_status"] = substr($_pinos_gpio_retorno,0,1);		
			  //
			  // SAIDA B
			  //			
			  // pegando status do pino
			  $_pinos_gpio_retorno = 0; // Desligado
			  // Pino da saida B diferente de NONE
			  $_pino_b[3] = $_db_ret[$i]["saidaB"]; 
			  if ( $_pino_b[3] != "NONE" ) {
			     if ($_GPIO == true) {
					$_pinos_comando = "/usr/local/bin/gpio -g mode " . $_pino_b[3] . " out";
					$_pinos_gpio_retorno = shell_exec($_pinos_comando);
				    $_pinos_comando = "/usr/local/bin/gpio -g read " . $_pino_b[3];
			        $_pinos_gpio_retorno = shell_exec($_pinos_comando);
				 }
			  }
			  // indicando para o portal o status do PINO saida A
			  $_dados["ACIONADOR"][$i]["saidaB_status"] = substr($_pinos_gpio_retorno,0,1);	
		   }
		} else {
		   $_dados["ACIONADOR"] = null;
		   $_status = false;
		   $_mensagem .= "<br>Acionador [ {$_cpf} {$_mac} ], nao encontrada.";
		   $_status = false;
		}
	} else { // PROCESSAR
	    // Tratando: READ, WRITE-ON e WRITE-OFF
		// para saida A e B

	    $_pinos_comando = "";
		$_pinos_comando_mode = "/usr/local/bin/gpio -g mode " . $_pino . " out";
		$_pinos_comando_read = "/usr/local/bin/gpio -g read " . $_pino;
	    $_pinos_gpio_regtorno = 0;
				
        // Preparando dados para retorno
        $_dados["ACIONADOR"]["SAIDA"] = $_saida;
		$_dados["ACIONADOR"]["SAIDA_DESCRICAO"] = $_saida_descricao;
		$_dados["ACIONADOR"]["LINHA"] = $_linha;
		$_dados["ACIONADOR"]["PINO"] = $_pino;
		$_dados["ACIONADOR"]["PINO_STATUS"] = 0; //Sempre inicialza como OFF/Desligado

		// Estamos com tudo isso instalado no servidor ?
		if ( $_GPIO == true ) {
			
			switch ($_acao) {
				case "READ":
					$_pinos_comando = $_pinos_comando_read;
					break;
				case "WRITE-ON":
					$_pinos_comando = "/usr/local/bin/gpio -g write " . $_pino . " 1";
					break;
				case "WRITE-OFF":
					$_pinos_comando = "/usr/local/bin/gpio -g write " . $_pino . " 0";
					break;
				case "SHUTDOWN":
					$_pinos_comando = "sudo shutdown -a now > /tmp/shutdown_php.log 2>/tmp/shutdown_php.log";
					break;					
				default:
				    // READ como default
					$_pinos_comando = $_pinos_comando_read;
			}
			
			// Executar o comando
			// Se NAO for leitura, apos deve fazer uma leitura para ver vomo o pino ficou - double check			
			if ( $_acao != "SHUTDOWN" ) {
			   $_pinos_gpio_retorno =  shell_exec($_pinos_comando_mode);
			}
			$_pinos_gpio_retorno =  shell_exec($_pinos_comando);
			if ( $_acao != "READ" && $_acao != "SHUTDOWN") {
				$_pinos_gpio_retorno =  shell_exec($_pinos_comando_read);
			}	
            
			// Determinando o STATUS apos a leitura
			$_dados["ACIONADOR"]["PINO_STATUS"] = substr($_pinos_gpio_retorno,0,1);
			$_dados["ACIONADOR"]["COMANDO_00"] = $_pinos_comando_mode;
			$_dados["ACIONADOR"]["COMANDO_01"] = $_pinos_comando;
			$_dados["ACIONADOR"]["COMANDO_02"] = $_pinos_comando_read;
			
			// Status como OK
			$_status = true;
			$_mensagem = "";
			
		}		
	}
	
	// Retornar Status
	$_return['acao']     = $_acao;
	$_return['status']   = $_status;
	$_return['mensagem'] = $_mensagem;
	$_return['error']    = $_error;
	$_return['dados']    = $_dados;
	
	// Retornando JSON
	echo json_encode($_return);	
?>