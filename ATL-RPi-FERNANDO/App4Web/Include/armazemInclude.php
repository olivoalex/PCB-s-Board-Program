<?php
function carregaArmazem($pdo, $_arm_id, $_arm_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaArmazem";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;
		
	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;
	
	// Armazem
	$sql = "SELECT arm_id, arm_cod, arm_scd, arm_nome"
		 . "  FROM armazem "
		 . " WHERE arm_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_arm_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["ARM"] = $_db_ret[0];
	} else {
		$_dados["ARM"] = null;
		$_status = false;
		$_mensagem .= "\nArmazem Id#{$_arm_id}, nao encontrado.";
	}
			
	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;
	
	print_r($_retorno);
	
	return $_retorno;
}

function carregaSubArmazem($pdo, $_sba_id, $_sba_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaSubArmazem";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	// Sub/Armazem
	$sql = "SELECT sba_id, sba_cod, sba_scd, sba_nome"
		 . "  FROM sub_armazem "
		 . " WHERE sba_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_sba_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["SBA"] = $_db_ret[0];
	} else {
		$_dados["SBA"] = null;
		$_status = false;
		$_mensagem .= "\nSub/Armazem Id#{$_sba_id}, nao encontrado.";
	}
		 			
	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	print_r($_retorno);

	return $_retorno;
}
?>