<?php
function carregaNaturezaItem($pdo, $_nat_id, $_nat_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaNaturezaItem";
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
	$sql = "SELECT nat_id, nat_cod, nat_descr, nat_controle, nat_ordem"
		 . "  FROM natureza "
		 . " WHERE nat_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_nat_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["NAT"] = $_db_ret[0];
	} else {
		$_dados["NAT"] = null;
		$_status = false;
		$_mensagem .= "\nNatureza Id#{$_nat_id}, nao encontrada.";
	}

	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	//print_r($_retorno);

	return $_retorno;
}