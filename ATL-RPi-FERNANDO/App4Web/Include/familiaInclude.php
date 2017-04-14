<?php
function carregaFamilia($pdo, $_fam_id, $_fam_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaFamilia";
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
	$sql = "SELECT fam_id, fam_cod, fam_descr, fam_ordem"
		 . "  FROM familia "
		 . " WHERE fam_id = ?";
 	$stm = $pdo->prepare($sql);
 	$stm->bindValue(1, $_fam_id);
 	$stm->execute();
 	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
 	if ( $_db_ret ) {
 		$_dados["FAM"] = $_db_ret[0];
 	} else {
 		$_dados["FAM"] = null;
 		$_status = false;
 		$_mensagem .= "\nFamilia Id#{$_fam_id}, nao encontrada.";
 	}

 	// Atribuindo dados par aretorno
 	$_retorno['status']   = $_status;
 	$_retorno['mensagem'] = $_mensagem;
 	$_retorno['error']    = $_error;
 	$_retorno['dados']    = $_dados;

 	//print_r($_retorno);

 	return $_retorno;
}

function carregaSubFamilia($pdo, $_fam_id, $_sfa_id, $_sfa_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaSubFamilia";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	// Sub/Familia
	$sql = "SELECT fam_id, sfa_id, sfa_cod, sfa_descr, tdc_id_01, tdc_id_02, tdc_id_03, flx_id, sfa_ordem"
		 . "  FROM sub_familia "
		 . " WHERE sfa_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_sfa_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["SFA"] = $_db_ret[0];
	} else {
		$_dados["SFA"] = null;
		$_status = false;
		$_mensagem .= "\nSub/Familia Id#{$_sfa_id}, nao encontrada.";
	}

	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;

	//print_r($_retorno);

	return $_retorno;
}

function carregaFluxo($pdo, $_flx_id, $_flx_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaFluxo";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	// Fluxo
	$sql = "SELECT flx_id, flx_cod, flx_descr, flx_ordem, flx_no, flx_controle"
		 . "  FROM fluxo "
 		. " WHERE flx_id = ?";
 	$stm = $pdo->prepare($sql);
 	$stm->bindValue(1, $_flx_id);
 	$stm->execute();
 	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
 	if ( $_db_ret ) {
 		$_dados["FLX"] = $_db_ret[0];
 	} else {
 		$_dados["FLX"] = null;
 		$_status = false;
 		$_mensagem .= "\nFluxo Id#{$_flx_id}, nao encontrado.";
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