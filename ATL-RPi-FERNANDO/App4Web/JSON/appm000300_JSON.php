<?php
    // Previsto receber:
    //   
    //     cpf
	//     mac
	//
	// Previsto Retornar
	//    
	//     Ultima medicao para os MACs do CPF ou apenas do MAC informado
	//
	//     Quando estiver carregando apenas um MAC, deve retornar dadoa para o grafigo com datas dos ultimos 10 dias
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

	//echo "<br>Acao: {$_acao}";
	//echo "<br>Complemento  : {$_complemento}";
	//echo "<br>Parametro    : {$_parametro}";
	//echo "<br>Empresa      : {$_empresa}";
	//echo "<br>Filial       : {$_filial}";
	//echo "<br>Usuario      : {$_usuario}";
	//echo "<br>CPF          : {$_cpf}";
	//echo "<br>MAC          : {$_mac}";
	//echo "<br><br>Data     : {$_CURRENT_DATE}";
	//echo "<br>Hora         : {$_CURRENT_TIME}";
	//echo "<br>TimeStamp    : {$_CURRENT_DATETIME}";
	
	// Associativo para receber dados 	
	$_dados = array();
	
	// Conectando com o Banco do RPI3
	$_pdo = ConectarRPI3();
	
	// Buscando dados para um CPF ou um MAC ou para ambos

    $_sql = "SELECT a.id as id, a.cpf as cpf, a.mac as mac, a.nome as descricao, b.d_T as temp1, b.d_U as umidade, b.b_T as temp2, b.b_P as pressao, b.hora as hora, b.dia as dia, b.ativo as ativo "
         .  " FROM ( SELECT a.id, a.cpf, a.mac, a.nome, max(d.id) max_id_dia "
         .         " FROM adm_clima a, dia_clima d "
		 .         " WHERE a.mac = d.mac ";
    // Foi informado um CPF valido
	if ( $_cpf != "NONE" ) {
		$_sql .= " AND a.cpf = ? "; //'01234567890'
	}
	// Foi informado um MAC valido
	if ( $_mac != "NONE" ) {
		$_sql .= " AND a.mac = ? "; //'60:01:94:07:4F:F7'
	}
	$_sql .=     " GROUP BY 1,2,3,4 ) as a, dia_clima b "
         . " WHERE a.mac = b.mac "
         .  " and a.max_id_dia = b.id ";
		 		 
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
	   //for ($i=0; $i < count($_db_ret); $i++) {		
	      $_dados["EST_CLIMA"] = $_db_ret;
	   //}
	   
	   // Se estiver com um MAC especifico, deve carregar dados para grafico
	   if ( $_mac != "NONE" ) {

			$_sql = "SELECT dia, date_format(dia,'%d-%m') mes_dia, count(*) leituras, truncate(AVG(d_T),2) temp_1, "
			      .       " truncate(AVG(d_U),2) umidade, truncate(AVG(b_T),2) temp_2, truncate(AVG(b_P),2) pressao "
				  .  " FROM dia_clima "
				  //.  " WHERE dia >= date(current_date - 10) "
				  .  " WHERE dia >= date(current_date - 10000) "
				  .  " AND mac = ? "
				  .  " GROUP BY 1,2 "
				  .  " ORDER BY 1 ASC";					 
            
			//echo "<br>SQL: ".$_sql;				  
			
			$_stm = $_pdo->prepare($_sql);
		    $_stm->bindValue(1, $_mac);
			$_stm->execute();
	        $_db_ret = $_stm->fetchAll(PDO::FETCH_ASSOC);	
            if ( $_db_ret) {
	           // Percorrendo o recultado
			   $_faixa = null;
			   $_temp_1 = null;
			   $_temp_2 = null;
			   $_umidade = null;
			   $_pressao = null;
			   
			   // Descarregando do bando para o PHP os dados para o Grafico
	           for ($i=0; $i < count($_db_ret); $i++) {		
	               $_faixa[$i]   = $_db_ret[$i]["mes_dia"];
				   $_temp_1[$i]  = floatval( $_db_ret[$i]["temp_1"] );
				   $_temp_2[$i]  = floatval( $_db_ret[$i]["temp_2"] );
				   $_umidade[$i] = floatval( $_db_ret[$i]["umidade"] );
				   $_pressao[$i] = floatval( $_db_ret[$i]["pressao"] );
	           }			
			   
			   // Preparando para retornar via JSON
			   $_dados["GRAFICO"][0]["FAIXA"]   = $_faixa;
			   $_dados["GRAFICO"][0]["TEMP_1"]  = $_temp_1;
			   $_dados["GRAFICO"][0]["TEMP_2"]  = $_temp_2;
			   $_dados["GRAFICO"][0]["UMIDADE"] = $_umidade;
			   $_dados["GRAFICO"][0]["PRESSAO"] = $_pressao;
			}

	   }	   
	   
	} else {
	   $_dados["EST_CLIMA"] = null;
	   $_status = false;
	   $_mensagem .= "<br>Estação Climatica [ {$_cpf} {$_mac} ], nao encontrada.";
	   $_status = false;
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
