<?php
function carregaTipoEntidade($pdo, $_tpe_id, $_tpe_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaTipoEntidade";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	// Tipo Entidade
	if ( $_tpe_id != 0 ) {
		$sql = "SELECT * "
			 . "  FROM tipo_entidade "
  		 	. " WHERE tpe_id = ?";
		$stm = $pdo->prepare($sql);
		$stm->bindValue(1, $_tpe_id);
		$stm->execute();
		$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
		if ( $_db_ret ) {
			$_dados["TPE"] = $_db_ret[0];
		} else {
			$_dados["TPE"] = null;
			$_status = false;
			$_mensagem .= "\nTipo Entidade Id#{$_trn_id}, nao encontrado.";
		}
	} else {
		$_dados["TPE"] = null;
		$_status = true;
		$_mensagem .= "\nTipo Entidade ZERADO, nao carregado.";		
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