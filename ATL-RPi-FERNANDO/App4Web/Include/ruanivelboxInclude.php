<?php
function carregaRua($pdo, $_rua_id, $_rua_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaRua";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;
		
	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;
	
	// Rua
	$sql = "SELECT rua_id, rua_cod, rua_no, rua_descr, rua_controle"
		 . "  FROM local_armazenagem_rua "
		 . " WHERE rua_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_rua_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["RUA"] = $_db_ret[0];
	} else {
		$_dados["RUA"] = null;
		$_status = false;
		$_mensagem .= "\nRua de Endereço Id#{$_rua_id}, nao encontrada.";
	}
			
	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;
	
	print_r($_retorno);
	
	return $_retorno;
}

function carregaNivel($pdo, $_niv_id, $_niv_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaNivel";
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
	$sql = "SELECT niv_id, niv_cod, niv_no, niv_descr, niv_controle"
		 . "  FROM local_armazenagem_nivel "
		 . " WHERE niv_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_niv_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["NIV"] = $_db_ret[0];
	} else {
		$_dados["NIV"] = null;
		$_status = false;
		$_mensagem .= "\nNivel de Endereço Id#{$_niv_id}, nao encontrado.";
	}
		 			
	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	print_r($_retorno);

	return $_retorno;
}

function carregaBox($pdo, $_box_id, $_box_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaBox";
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
	$sql = "SELECT box_id, box_cod, box_no, box_descr, box_controle"
		 . "  FROM local_armazenagem_box "
		 		. " WHERE box_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_box_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["BOX"] = $_db_ret[0];
	} else {
		$_dados["BOX"] = null;
		$_status = false;
		$_mensagem .= "\nBox Id#{$_box_id}, nao encontrado.";
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