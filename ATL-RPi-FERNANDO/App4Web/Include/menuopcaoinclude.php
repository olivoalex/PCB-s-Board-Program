<?php
function carregaMenu($pdo, $_mnu_id, $_mnu_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaMenu";
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
	if ( $_mnu_id != 0 ) {
		$sql = "SELECT * "
			 . "  FROM opcao  "
  		 	. " WHERE opc_id = ?";
		$stm = $pdo->prepare($sql);
		$stm->bindValue(1, $_mnu_id);
		$stm->execute();
		$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
		if ( $_db_ret ) {
			$_dados["MNU"] = $_db_ret[0];
		} else {
			$_dados["MNU"] = null;
			$_status = false;
			$_mensagem .= "\nMenu Id#{$_mnu_id}, nao encontrado.";
		}
	} else {
		$_dados["MNU"] = null;
		$_status = true;
		$_mensagem .= "\nMenu, nao carregado.";		
	}

	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	//print_r($_retorno);

	return $_retorno;
}

function carregaOpcao($pdo, $_opc_id, $_opc_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaOpcao";
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
	if ( $_opc_id != 0 ) {
		$sql = "SELECT * "
			 . "  FROM opcao  "
  		 	. " WHERE opc_id = ?";
		$stm = $pdo->prepare($sql);
		$stm->bindValue(1, $_opc_id);
		$stm->execute();
		$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
		if ( $_db_ret ) {
			$_dados["OPC"] = $_db_ret[0];
		} else {
			$_dados["OPC"] = null;
			$_status = false;
			$_mensagem .= "\nOpcao Id#{$_opc_id}, nao encontrado.";
		}
	} else {
		$_dados["MNU"] = null;
		$_status = true;
		$_mensagem .= "\nOpcao, nao carregada.";		
	}

	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	//print_r($_retorno);

	return $_retorno;
}

function carregaMenuOpcao($pdo, $_mno_id, $_mno_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaMenuOpcao";
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
	if ( $_mno_id != 0 ) {
		$sql = "SELECT * "
			 . "  FROM menu  "
  		 	. " WHERE mno_id = ?";
		$stm = $pdo->prepare($sql);
		$stm->bindValue(1, $_mno_id);
		$stm->execute();
		$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
		if ( $_db_ret ) {
			$_dados["MNO"] = $_db_ret[0];
		} else {
			$_dados["MNO"] = null;
			$_status = false;
			$_mensagem .= "\nMenuOpcao Node Id#{$_mno_id}, nao encontrado.";
		}
	} else {
		$_dados["MNO"] = null;
		$_status = true;
		$_mensagem .= "\nMenuOpcao Node, nao carregado.";		
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