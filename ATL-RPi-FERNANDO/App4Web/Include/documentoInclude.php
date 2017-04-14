<?php
function carregaAreaDeNegocio($pdo, $_adn_id, $_adn_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaAreaDeNegocio";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;
		
	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;
	
	// Empresa
	$sql = "SELECT adn_id, adn_cod, adn_descr, adn_no, adn_controle, adn_cssico, adn_controle, adn_ativo, adn_valido"
		 . "  FROM area_de_negocio "
		 . " WHERE adn_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_adn_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["ADN"] = $_db_ret[0];
	} else {
		$_dados["ADN"] = null;
		$_status = false;
		$_mensagem .= "\nArea de Negocio Id#{$_adn_id}, nao encontrada.";
	}
			
	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;
	
	//print_r($_retorno);
	
	return $_retorno;
}

function carregaNaturezaDocumento($pdo, $_ntd_id, $_ntd_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaNaturezaDocumento";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	// Filial
	$sql = "SELECT ntd_id, ntd_cod, ntd_descr, ntd_no, ntd_controle, ntd_cssico, ntd_ativo, ntd_valido"
		 . "  FROM natureza_documento "
		 		. " WHERE ntd_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_ntd_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["NTD"] = $_db_ret[0];
	} else {
		$_dados["NTD"] = null;
		$_status = false;
		$_mensagem .= "\nNatrureza Documento Id#{$_ntd_id}, nao encontrada.";
	}
	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	//print_r($_retorno);

	return $_retorno;
}

function carregaTipoDocumento($pdo, $_tpd_id, $_tpd_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaTipoDocumento";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	// Filial
	$sql = "SELECT * "
		 . "  FROM tipo_documento "
		 . " WHERE tpd_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_tpd_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["TPD"] = $_db_ret[0];
	} else {
		$_dados["TPD"] = null;
		$_status = false;
		$_mensagem .= "\nTipo Documento Id#{$_tpd_id}, nao encontrado.";
	}
		 			
	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	//print_r($_retorno);

	return $_retorno;
}

function carregaTipoTransacao($pdo, $_ttr_id, $_ttr_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaTipoTransacao";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	// TipoTransacao
	$sql = "SELECT * "
		 . "  FROM tipo_transacao "
		 . " WHERE ttr_id = ?";

	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_ttr_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["TTR"] = $_db_ret[0];
	} else {
		$_dados["TTR"] = null;
		$_status = false;
		$_mensagem .= "\nTipo Transacao Id#{$_ttr_id}, nao encontrado.";
	}

	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;
	//print_r($_retorno);

	return $_retorno;
}

function carregaTipoNumeroSequencial($pdo, $_tns_id, $_tns_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaTipoNumeroSequencial";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	// Filial
	$sql = "SELECT * "
		 . "  FROM tipo_numero_sequencial "
		 		. " WHERE tns_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_tns_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["TNS"] = $_db_ret[0];
	} else {
		$_dados["TNS"] = null;
		$_status = false;
		$_mensagem .= "\nTipo Numero Sequencial Id#{$_tns_id}, nao encontrado.";
	}

	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	//print_r($_retorno);

	return $_retorno;
}

function carregaTransacao($pdo, $_trn_id, $_trn_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaTransacao";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	// Filial
	$sql = "SELECT * "
		 . "  FROM transacao "
  		 . " WHERE trn_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_trn_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["TRN"] = $_db_ret[0];
	} else {
		$_dados["TRN"] = null;
		$_status = false;
		$_mensagem .= "\nTransacao Id#{$_trn_id}, nao encontrado.";
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