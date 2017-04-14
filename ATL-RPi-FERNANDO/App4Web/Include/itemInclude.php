<?php
function carregaItem($pdo, $_itm_id, $_itm_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaItem";
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
	$sql = "SELECT itm_id, org_id, itm_cod, itm_descr, itm_declinado, tpi_id, nat_id, ori_id, gru_id, por_id, lin_id, clf_id, mar_id, fam_id, sfa_id, seg_id, div_id, ctg_id"
		 . "  FROM item "
		 . " WHERE itm_id = ?";
 	$stm = $pdo->prepare($sql);
 	$stm->bindValue(1, $_itm_id);
 	$stm->execute();
 	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
 	if ( $_db_ret ) {
 		$_dados["ITM"] = $_db_ret[0];
 	} else {
 		$_dados["ITM"] = null;
 		$_status = false;
 		$_mensagem .= "\nItem Id#{$_itm_id}, nao encontrada.";
 	}

 	// Atribuindo dados par aretorno
 	$_retorno['status']   = $_status;
 	$_retorno['mensagem'] = $_mensagem;
 	$_retorno['error']    = $_error;
 	$_retorno['dados']    = $_dados;

 	//print_r($_retorno);

 	return $_retorno;
}

function carregaProduto($pdo, $_itm_id, $_prd_id) {

	// Retornar Status
	$_retorno['acao']     = "carregaProduto";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;

	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;

	// Produto
	$sql = "SELECT itm_id, prd_id, emp_id, prd_controle, tpp_id, dec_id_01, dec_id_02, dec_id_03, ori_id, nat_id, prd_ean13_cod, prd_barcode "
		 . "  FROM produto "
		 . " WHERE prd_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_prd_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["PRD"] = $_db_ret[0];
	} else {
		$_dados["PRD"] = null;
		$_status = false;
		$_mensagem .= "\nProduto Id#{$_prd_id}, nao encontrado.";
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