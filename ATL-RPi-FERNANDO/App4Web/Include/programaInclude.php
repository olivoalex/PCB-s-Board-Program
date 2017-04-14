<?php
function carregaPrograma($pdo, $_prg_id, $_prg_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaPrograma";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	// Programa
	if ( $_prg_id != 0 ) {
		$sql = "SELECT * "
			 . "  FROM programa "
  		 	. " WHERE prg_id = ?";
		$stm = $pdo->prepare($sql);
		$stm->bindValue(1, $_prg_id);
		$stm->execute();
		$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
		if ( $_db_ret ) {
			$_dados["PRG"] = $_db_ret[0];
		} else {
			$_dados["PRG"] = null;
			$_status = false;
			$_mensagem .= "\nPrograma Id#{$_prg_id}, nao encontrado.";
		}
	} else {
		$_dados["PRG"] = null;
		$_status = true;
		$_mensagem .= "\nPrograma, nao carregado.";		
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