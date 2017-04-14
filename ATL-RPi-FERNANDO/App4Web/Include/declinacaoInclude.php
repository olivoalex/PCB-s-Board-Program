<?php
function carregaTipoDeclinacao($pdo, $_tdc_id, $_tdc_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaTipoDeclinacao";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;
		
	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;
	
	// TipoDeclinacao
	$sql = "SELECT tdc_id, tdc_cod, tdc_descr, tdc_controle, tdc_ordem"
		 . "  FROM tipo_declinacao "     
		 . " WHERE tdc_id = ?";	     
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_tdc_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["TDC"] = $_db_ret[0];
	} else {
		$_dados["TDC"] = null;
		$_status = false;
		$_mensagem .= "\nTipo Declinacao Id#{$_tdc_id}, nao encontrada.";
	}
			
	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;
	
	//print_r($_retorno);
	
	return $_retorno;
}

function carregaDeclinacao($pdo, $_tdc_id, $_dec_id, $_dec_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaDeclinacao";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	if ( $_tdc_id == null ) { $_tdc_id = 0;	}
	if ( $_dec_id == null ) { $_dec_id = 0;	}
	
	if ( $_tdc_id == 0 and $_dec_id == 0 ) {
		// Atribuindo dados par aretorno
		$_retorno['status']   = false;
		$_retorno['mensagem'] = "carregaDeclinacao: Parametros invalidos.";
		$_retorno['error']    = "carregaDeclinacao: Parametros invalidos.";
		$_retorno['dados']    = null;		
		return $_retorno;
	}
	
	// Declinacao
	$sql = "SELECT dec_id, tdc_id, dec_cod, dec_descr, dec_ordem"
		 . "  FROM declinacao ";
	if ( $_tdc_id != 0 ) {	 		
       $sql .= " WHERE tdc_id = ". $_tdc_id;
       if ( $_dec_id != 0 ) {
       	$sql .= " AND dec_id = " . $_dec_id;
       }
	} else {
	   $sql .= " WHERE dec_id = ". $_dec_id;
	   $sql .= " ORDER BY dec_ordem ASC";
	}
	//echo "<br>SQL: {$sql} ";
	
	$stm = $pdo->prepare($sql);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		if ( $_dec_id != null and $_dec_id != 0) {
		   // manda apenas o solicitado para o tipo
		   $_dados["DEC"] = $_db_ret[0];
		} else {
		   // Manda uma lista
		   $_dados["DEC"] = $_db_ret;
		}
	} else {
		$_dados["DEC"] = null;
		$_status = false;
		if ( $_dec_id != 0 ) {
		   $_mensagem .= "\nDeclinacao Id#{$_dec_id}, nao encontrado.";
		} else {
		   $_mensagem .= "\nNao foi encontrada nenhuma Declinacao para o Tipo Id# {$_tdc_id}.";
		}
	}
		 			
	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	//print_r($_retorno);

	return $_retorno;
}
?>