<?php
function carregaGrupoItem($pdo, $_gru_id, $_gru_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaGrupoItem";
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
	$sql = "SELECT gru_id, gru_cod, gru_descr, gru_controle, gru_ordem"
		 . "  FROM grupo_item "
		 . " WHERE gru_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_gru_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["GRU"] = $_db_ret[0];
	} else {
		$_dados["GRU"] = null;
		$_status = false;
		$_mensagem .= "\nGrupo Id#{$_gru_id}, nao encontrada.";
	}

	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	//print_r($_retorno);

	return $_retorno;
}