<?php
function carregaEmpresa($pdo, $_emp_id, $_emp_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaEmpresa";
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
	$sql = "SELECT emp_id, emp_cod, emp_scd, emp_nome, emp_apelido, tpe_id, emp_mtzfil, org_id, emp_dominio"
		 . "  FROM empresa "
		 . " WHERE emp_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_emp_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["EMP"] = $_db_ret[0];
	} else {
		$_dados["EMP"] = null;
		$_status = false;
		$_mensagem .= "\nEmpresa Id#{$_emp_id}, nao encontrada.";
	}
			
	// Atribuindo dados par aretorno
	$_retorno['status']   = $_status;
	$_retorno['mensagem'] = $_mensagem;
	$_retorno['error']    = $_error;
	$_retorno['dados']    = $_dados;
	
	print_r($_retorno);
	
	return $_retorno;
}

function carregaFilial($pdo, $_fil_id, $_fil_cod) {

	// Retornar Status
	$_retorno['acao']     = "carregaFilial";
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
	$sql = "SELECT fil_id, fil_cod, fil_scd, fil_nome, fil_apelido, tpe_id, fil_mtzfil, emp_id, org_id, fil_dominio"
		 . "  FROM filial "
		 . " WHERE fil_id = ?";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_fil_id);
	$stm->execute();
	$_db_ret = $stm->fetchAll(PDO::FETCH_ASSOC);
	if ( $_db_ret ) {
		$_dados["FIL"] = $_db_ret[0];
	} else {
		$_dados["FIL"] = null;
		$_status = false;
		$_mensagem .= "\nFilial Id#{$_fil_id}, nao encontrado.";
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