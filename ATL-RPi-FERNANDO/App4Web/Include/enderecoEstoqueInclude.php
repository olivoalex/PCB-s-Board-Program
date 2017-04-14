<?php
function carregaEnderecoEstoque($pdo, $_emp_id, $_fil_id, $_arm_id, $_sba_id, $_rua_id, $_niv_id, $_box_id) {

    // Buscando dados do endereco de estoque
    //echo "<br>carregaEnderecoEstoque:";
	//echo "<br>Empresa: ". $_emp_id;
	//echo "<br>Filial: ".$_fil_id;
	//echo "<br>Armazem: ".$_arm_id;
	//echo "<br>Sub/Armazem: ".$_sba_id;
	//echo "<br>Rua: ".$_rua_id;
	//echo "<br>Nivel: ".$_niv_id;
	//echo "<br>Box: ".$_box_id;
	//echo "<br><br>";
	
	// Retornar Status
	$_retorno['acao']     = "carregaEnderecoEstoque";
	$_retorno['status']   = true;
	$_retorno['mensagem'] = "";
	$_retorno['error']    = "";
	$_retorno['dados']    = null;
		
	// Locais
	$_mensagem = "";
	$_error = "";
	$_status = true;
	$_dados = null;
	
	$sql = "SELECT ede_id, ede_capacidade, ede_ativo, ede_valido, lar_id, ada_id, tam_id, eds_id"
			. "  FROM endereco_estoque "
			. " WHERE emp_id = ? "
		    .   " AND fil_id = ? "
		    .   " AND arm_id = ? "
		    .   " AND sba_id = ? "
		    .   " AND rua_id = ? "
		    .   " AND niv_id = ? "
		    .   " AND box_id = ? ";
	$stm = $pdo->prepare($sql);
	$stm->bindValue(1, $_emp_id);
	$stm->bindValue(2, $_fil_id);
	$stm->bindValue(3, $_arm_id);
	$stm->bindValue(4, $_sba_id);
	$stm->bindValue(5, $_rua_id);
	$stm->bindValue(6, $_niv_id);
	$stm->bindValue(7, $_box_id);
	$stm->execute();
	$_sel = $stm->fetchAll(PDO::FETCH_ASSOC);	
	if ( $_sel) {
	
		$_ede_id = $_sel[0]["ede_id"];
		$_lar_id = $_sel[0]["lar_id"];
		$_ada_id = $_sel[0]["ada_id"];
		$_tam_id = $_sel[0]["tam_id"];
		$_eds_id = $_sel[0]["eds_id"];
		
		// Empilhando ENDERECO para retorno
		$_dados["EDE"] = $_sel[0];
		
		// Achou o endereco, pega dados complementares
		// 1 - Local_armazenagem
		$sql = "SELECT lar_id, lar_cod, lar_no, lar_descr, lar_controle"
				. "  FROM local_armazenagem "
				. " WHERE lar_id = ? ";
		$stm = $pdo->prepare($sql);
		$stm->bindValue(1, $_lar_id);
		$stm->execute();
		$_sel = $stm->fetchAll(PDO::FETCH_ASSOC);
		if ( $_sel ) {
		   $_dados["LAR"] = $_sel[0];
		} else {
		   $_dados["LAR"] = null;
		   $_status = false;
		   $_error .= "\nLocal Armazenagem Id#{$_lar_id}, nao encontrado.";
		}
		// 2 - Area de Armazenagem
		$sql = "SELECT ada_id, ada_cod, ada_no, ada_descr, ada_controle"
				. "  FROM area_armazenagem "
				. " WHERE ada_id = ? ";
		$stm = $pdo->prepare($sql);
		$stm->bindValue(1, $_ada_id);
		$stm->execute();
		$_sel = $stm->fetchAll(PDO::FETCH_ASSOC);
		if ( $_sel ) {
			$_dados["ADA"] = $_sel[0];
		} else {
			$_dados["ADA"] = null;
			$_status = false;
			$_error .= "\nArea de Armazenagem Id#{$_ada_id}, nao encontrada.";
		}
		
		// 3 - Tipo de Armazenagem
		$sql = "SELECT tam_id, tam_cod, tam_no, tam_descr, tam_controle"
				. "  FROM tipo_armazenagem "
				. " WHERE tam_id = ? ";
		$stm = $pdo->prepare($sql);
		$stm->bindValue(1, $_tam_id);
		$stm->execute();
		$_sel = $stm->fetchAll(PDO::FETCH_ASSOC);
		if ( $_sel ) {
			$_dados["TAM"] = $_sel[0];
		} else {
			$_dados["TAM"] = null;
			$_status = false;
			$_error .= "\nTipo de Armazenagem Id#{$_tam_id}, nao encontrado.";
		}
		
		// 4 - Endereco Situacao
		$sql = "SELECT eds_id, eds_cod, eds_no, eds_descr, eds_controle"
				. "  FROM endereco_situacao "
				. " WHERE eds_id = ? ";
		$stm = $pdo->prepare($sql);
		$stm->bindValue(1, $_eds_id);
		$stm->execute();
		$_sel = $stm->fetchAll(PDO::FETCH_ASSOC);
		if ( $_sel ) {
			$_dados["EDS"] = $_sel[0];
		} else {
			$_dados["EDS"] = null;
			$_status = false;
			$_error .= "\nSituacao do Endereco Id#{$_eds_id}, nao encontrado.";
		}		
	} else {
		$_dados["EDE"] = null;
		$_dados["LAR"] = null;
		$_dados["ADA"] = null;
		$_dados["TAM"] = null;
		$_dados["EDS"] = null;
		$_status = false;
		$_error .= "\nEndereco, nao encontrado.";
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