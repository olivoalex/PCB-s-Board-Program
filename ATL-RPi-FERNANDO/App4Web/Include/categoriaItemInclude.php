<?php
function carregaCategoriaItem($pdo, $_ctg_id, $_ctg_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaCategoriaItem";
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
	$sql = "SELECT ctg_id, ctg_cod, ctg_descr, ctg_controle, ctg_no, ctg_ordem"
		 . "  FROM categoria "
		 . " WHERE ctg_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_ctg_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["CTG"] = $_db_ret[0];
	} else {
		$_dados["CTG"] = null;
		$_status = false;
		$_mensagem .= "\nCategoria Id#{$_ctg_id}, nao encontrada.";
	}

	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	//print_r($_retorno);

	return $_retorno;
}