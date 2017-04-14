<?php
function carregaOrigemItem($pdo, $_ori_id, $_ori_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaOrigemItem";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	// Famiia
	$sql = "SELECT ori_id, ori_cod, ori_descr, ori_controle, ori_ordem"
		 . "  FROM origem "
		 . " WHERE ori_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_ori_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["ORI"] = $_db_ret[0];
	} else {
		$_dados["ORI"] = null;
		$_status = false;
		$_mensagem .= "\nOrigem Id#{$_ori_id}, nao encontrada.";
	}

	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	//print_r($_retorno);

	return $_retorno;
}