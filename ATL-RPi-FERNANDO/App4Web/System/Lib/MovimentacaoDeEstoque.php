<?php
class MovimentacaoDeEstoque {
	public $_Transacao;
	public $_TipoTransacao;
	public $_GrupoTransacao;
	
	public $_TipoEntidade_Origem;
	public $_TipoEntidade_Destino;

	public $_TipoArmazem_Origem;
	public $_TipoArmazem_Destino;
	
	public $_Armazem_Origem;
	public $_Armazem_Destino;

	public $_SubArmazem_Origem;
	public $_SubArmazem_Destino;
	
	public $_Entidade_Origem;
	public $_Entidade_Destino;
	
	public $_Empresa;
	public $_Filial;
	public $_Usuario;
	public $_Item;
	public $_Produto;
	public $_Qualidade;
	public $_Rua;
	public $_Nivel;
	public $_Box;
	public $_Endereco_Estoque;
	
	public $_Estoque;
	public $_Estoque_Endereco;
	public $_Kardex;
	
	private $_Model;
	
	public function __construct() {
		$this->_Model = new Model();
		
		$this->_TipoTransacao = false;
		$this->_GrupoTransacao = false;
		$this->_Transacao = false;
		$this->_TipoEntidade_Origem = false;
		$this->_TipoEntidade_Destino = false;
		$this->_Armazem_Origem = false;
		$this->_Armazem_Destino = false;
		$this->_SubArmazem_Origem = false;
		$this->_SubArmazem_Destino = false;
		$this->_Entidade_Origem = false;
		$this->_Entidade_Destino = false;
		
		$this->_Empresa = false;
		$this->_Filial = false;
		$this->_Item = false;
		$this->_Produto = false;
		$this->_Qualidade = false;
		
		$this->_Rua = false;
		$this->_Nivel = false;
		$this->_Box = false;
		$this->_Endereco_Estoque = false;
		
		$this->_Estoque = false;
		$this->_Estoque_Endereco = false;
		$this->_Kardex = false;
		
	}
	
	public function CarregaTransacao($_como, $_trn) {
		// $_como pode ser: ID ou COD
		// Apenas carrega todas as estruturas associadas a TRANSACAO
		
		$_como = strtoupper($_como);
		
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
						
		// Carregando Transacao
		$_where = " trn_cod = '{$_trn}'";
		
		if ( $_como == "ID") {
			$_where = " trn_id = '{$_trn}'";
		}
		$_dados = $this->_Model->select('transacao', $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Transacao = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Transação [{$_trn}], nao Encontrada.<br>";
		}
		
		// Carregando Tipo de Transacao		
		if ( $_return['status'] == true ) {
			//print_r($this->_Transacao);
			$_ttr = $this->_Transacao['ttr_id'];
			$_where = " ttr_id = '" . $_ttr."'";
			$_dados = $this->_Model->select("tipo_transacao", $_where, 1);
			if ( count($_dados) > 0 ) {
				$this->_TipoTransacao = $_dados[0];
			} else {
				$_return['status'] = false;
				$_return['error'] .= "Tipo de Transacao nao Encontrado.<br>";
			}				
		}
		
		// Carregando Grupo Transacao		
		if ( $_return['status'] == true ) {
			$_gtr = $this->_Transacao['gtr_id'];
			$_where = " gtr_id = '" . $_gtr."'";
			$_dados = $this->_Model->select("grupo_transacao", $_where, 1);
			if ( count($_dados) > 0 ) {
				$this->_GrupoTransacao = $_dados[0];
			} else {
				$_return['status'] = false;
				$_return['error'] .= "Grupo de Transacao nao Encontrado.<br>";
			}
		}		
		
		// Carregando Tipo de Entidade: ORIGEM		
		if ( $_return['status'] == true ) {
			$_tpe = $this->_Transacao['tpe_id_origem'];
			$_where = " tpe_id = '" . $_tpe."'";
			$_dados = $this->_Model->select("tipo_entidade", $_where, 1);
			if ( count($_dados) > 0 ) {
				$this->_TipoEntidade_Origem = $_dados[0];
			} else {
				$_return['status'] = false;
				$_return['error'] .= "Tipo de Entidade ORIGEM nao Encontrada.<br>";
			}
		}
		
		// Carregando Tipo de Entidade: DESTINO
		
		if ( $_return['status'] == true ) {
			$_tpe = $this->_Transacao['tpe_id_destino'];
			$_where = " tpe_id = '" . $_tpe."'";
			$_dados = $this->_Model->select("tipo_entidade", $_where, 1);
			if ( count($_dados) > 0 ) {
				$this->_TipoEntidade_Destino = $_dados[0];
			} else {
				$_return['status'] = false;
				$_return['error'] .= "Tipo de Entidade DESTINO nao Encontrada.<br>";
			}
		}		
		
		// Carregando Tipo de Armazem: Origem		
		if ( $_return['status'] == true ) {
			$_tpa = $this->_Transacao['tpa_id_origem'];
			$_where = " tpa_id = '" . $_tpa."'";
			$_dados = $this->_Model->select("tipo_armazem", $_where, 1);
			if ( count($_dados) > 0 ) {
				$this->_TipoArmazem_Origem = $_dados[0];
			} else {
				$_return['status'] = false;
				$_return['error'] .= "Tipo de Armazem ORIGEM nao Encontrado.<br>";
			}
		}
		// Carregando Tipo de Armazem: DESTINO		
		if ( $_return['status'] == true ) {
			$_tpa = $this->_Transacao['tpa_id_destino'];
			$_where = " tpa_id = '" . $_tpa."'";
			$_dados = $this->_Model->select("tipo_armazem", $_where, 1);
			if ( count($_dados) > 0 ) {
				$this->_TipoArmazem_Destino = $_dados[0];
			} else {
				$_return['status'] = false;
				$_return['error'] .= "Tipo de Armazem DESTINO nao Encontrado.<br>";
			}
		}
		
		// se chogou ate aqui esta tudo ok
		return $_return;		
	}
	
	public function CarregaEntidades($_ent_origem, $_ent_destino){		
		// Carregando as Entidades
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
		
		// Carregando Entidade: ORIGEM
   	    $_where = " ent_id = " . $_ent_origem;   	    
	    $_dados = $this->_Model->select("entidade", $_where, 1);
	    if ( count($_dados) > 0 ) {
			$this->_Entidade_Origem = $_dados[0];
	    } else {
			$_return['status'] = false;
			$_return['error'] .= "Entidade ORIGEM nao Encontrada.<br>";		
		}

		// Carregando Entidade: DESTINO
		$_where = " ent_id = " . $_ent_destino;
		$_dados = $this->_Model->select("entidade", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Entidade_Destino = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Entidade DESTINO nao Encontrada.<br>";
		}
		
		// se chogou ate aqui esta tudo ok
		return $_return;
	}
	
	public function CarregaArmazens($_arm_origem, $_arm_destino){
        // Carregando os Armazens
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
		
		// Carregando Armazem: ORIGEM
   	    $_where = " arm_id = " . $_arm_origem;   	    
	    $_dados = $this->_Model->select("armazem", $_where, 1);
	    if ( count($_dados) > 0 ) {
			$this->_Armazem_Origem = $_dados[0];
	    } else {
			$_return['status'] = false;
			$_return['error'] .= "Armazem ORIGEM nao Encontrado.<br>";		
		}

		// Carregando Armazem: DESTINO
		$_where = " arm_id = " . $_arm_destino;
		$_dados = $this->_Model->select("armazem", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Armazem_Destino = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Armazem DESTINO nao Encontrado.<br>";
		}
		
		// se chogou ate aqui esta tudo ok
		return $_return;
	}
	
	public function CarregaSubArmazens($_sba_origem, $_sba_destino) {
        // Carregando os SubArmazens
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
		
		// Carregando SubArmazem: ORIGEM
   	    $_where = " sba_id = " . $_sba_origem;   	    
	    $_dados = $this->_Model->select("sub_armazem", $_where, 1);
	    if ( count($_dados) > 0 ) {
			$this->_SubArmazem_Origem = $_dados[0];
	    } else {
			$_return['status'] = false;
			$_return['error'] .= "Sub/Armazem ORIGEM nao Encontrado.<br>";		
		}

		// Carregando SubArmazem: DESTINO
		$_where = " sba_id = " . $_sba_destino;
		$_dados = $this->_Model->select("sub_armazem", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_SubArmazem_Destino = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Sub/Armazem DESTINO nao Encontrado.<br>";
		}
		
		// se chogou ate aqui esta tudo ok
		return $_return;
	}
	
	public function CarregaEmpresaFilial($_empresa, $_filial) {
		// Carregando dados da EMpresa e da Filial
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
		
		// Empresa
		$_where = " emp_id = " . $_empresa;
		$_dados = $this->_Model->select("empresa", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Empresa = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Empresa nao Encontrada.<br>";
		}

		// Filial
		$_where = " fil_id = " . $_filial;
		$_dados = $this->_Model->select("filial", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Filial = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Filial nao Encontrada.<br>";
		}
				
		// se chogou ate aqui esta tudo ok
		return $_return;
	}
	
	public function CarregaUsuario($_usuario) {
		// Carregando dados do Usuario
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
	
		// Usuario
		$_where = " usr_id = " . $_usuario;
		$_dados = $this->_Model->select("usuario", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Usuario = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Usuario nao Encontrado.<br>";
		}	
	
		// se chogou ate aqui esta tudo ok
		return $_return;
	}

	public function CarregaItem($_item) {
		// Carregando dados do Item GENERICO
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
	
		// Item
		$_where = " itm_id = " . $_item;
		$_dados = $this->_Model->select("item", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Item = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Item nao Encontrado.<br>";
		}
	
		// se chogou ate aqui esta tudo ok
		return $_return;
	}
	
	public function CarregaProduto($_produto) {
		// Carregando dados do Produto Declinado Pelo ID
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;

		// Produto
		$_where = " prd_id = " . $_produto;
		$_dados = $this->_Model->select("produto", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Produto = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Produto nao Encontrado.<br>";
		}
		
		// se chogou ate aqui esta tudo ok
		return $_return;
	}
	
	public function CarregaProdutoDeclinacao($_empresa, $_item, $_declinacao_01, $_declinacao_02, $_declinacao_03) {
		// Carregando dados do Item e Produto
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
	
		// Produto pela Declinacao
		$_where = " emp_id = " . $_empresa 
		    . " AND itm_id = " . $_item
		    . " AND dec_id_01 = " . $_declinacao_01
		    . " AND dec_id_02 = " . $_declinacao_02
		    . " AND dec_id_03 = " . $_declinacao_03;
		$_dados = $this->_Model->select("produto", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Produto = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Produto Declinado nao Encontrado.<br>";
		}	
		// se chogou ate aqui esta tudo ok
		return $_return;
	}
	
	public function CarregaQualidade($_como, $_qualidade) {
		// Carregando dados da Qualidade
		// $_como pode ser: ID ou COD
		$_como = strtoupper($_como);		

		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
		
		// Qualidade
		$_where = " qld_id = " . $_qualidade;
		$_dados = $this->_Model->select("qualidade", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Qualidade = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Qualidade nao Encontrada.<br>";
		}
		
		// se chogou ate aqui esta tudo ok
		return $_return;
		
	}
	
	public function CarregaRua($_como, $_rua) {
		// Carregando dados de Rua Nivel e Box
		// $_como pode ser: ID ou COD
		$_como = strtoupper($_como);
	
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
	
		// Rua
		$_where = " rua_cod = '" . $_rua."' ";
		if ( $_como == "ID" ) {
			$_where = " rua_id = " . $_rua;
		}
		$_dados = $this->_Model->select("local_armazenagem_rua", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Rua = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Local Armazenagem Rua nao Encontrada.<br>";
		}
	
		// se chogou ate aqui esta tudo ok
		return $_return;
	
	}
	
	public function CarregaNivel($_como, $_nivel) {
		// Carregando dados de Nivel
		// $_como pode ser: ID ou COD
		$_como = strtoupper($_como);
	
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
	
		// Rua
		$_where = " niv_cod = '" . $_nivel."' ";
		if ( $_como == "ID" ) {
			$_where = " niv_id = " . $_nivel;
		}
		$_dados = $this->_Model->select("local_armazenagem_nivel", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Nivel = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Local Armazenagem Nivel nao Encontrado.<br>";
		}
	
		// se chogou ate aqui esta tudo ok
		return $_return;
	
	}
	
	public function CarregaBox($_como, $_box) {
		// Carregando dados de Box
		// $_como pode ser: ID ou COD		
		$_como = strtoupper($_como);
		
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
	
		// Rua
		$_where = " box_cod = '" . $_box."' ";
		if ( $_como == "ID" ) {
			$_where = " box_id = " . $_box;
		}
		$_dados = $this->_Model->select("local_armazenagem_box", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Box = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Local Armazenagem Box nao Encontrado.<br>";
		}
	
		// se chogou ate aqui esta tudo ok
		return $_return;
	
	}

	public function CarregaEnderecoEstoque($_como, $_empresa, $_filial, $_armazem, $_sub_armazem, $_rua, $_nivel, $_box, $_endereco_estoque) {
		// Carregando dados de Box
		// $_como pode ser: ID ou COD
		$_como = strtoupper($_como);
	
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
	
		// Chave
		$_where = " emp_id = " . $_empresa
		     ." AND fil_id = " . $_filial
		     ." AND arm_id = " . $_armazem
		     ." AND sba_id = " . $_sub_armazem
		     ." AND rua_id = " . $_rua
		     ." AND niv_id = " . $_nivel
		     ." AND box_id = " . $_box;
		
		if ( $_como == "ID" ) {
			$_where = " ede_id = " . $_endereco_estoque;
		}
		$_dados = $this->_Model->select("endereco_estoque", $_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Endereco_Estoque = $_dados[0];
		} else {
			$_return['status'] = false;
			$_return['error'] .= "Endereco de Estoque nao Encontrado.<br>";
		}
	
		// se chogou ate aqui esta tudo ok
		return $_return;
	
	}
	
	public function AtualizaEstoque($_quantidade) {
		
		$_CURRENT_DATE     = date('Y-m-d');
		
		$_CURRENT_TIME     = date('H:i:s');
		
		$_CURRENT_DATETIME = date('Y-m-d H:i:s');
		
		$_return['message'] = "";
		$_return['error'] = "";
		$_return['status'] = true;
		
		// Parte da premissa que existe
		$_flg_estoque = true;
		$_flg_estoque_endereco = true;
		$_flg_kardex = true;
				
		//echo "<br>-------------------------- Quantidade-------------------> ".$_quantidade;
		
		// Atualizar Estoque
		$_stk_where = " emp_id = " . $this->_Empresa["emp_id"]
		            . " AND fil_id = " . $this->_Filial["fil_id"]
		            . " AND arm_id = " . $this->_Armazem_Origem["arm_id"]
		            . " AND sba_id = " . $this->_SubArmazem_Origem["sba_id"]
		            . " AND itm_id = " . $this->_Item["itm_id"]		        		
		            . " AND prd_id = " . $this->_Produto["prd_id"]
		            . " AND qld_id = " . $this->_Qualidade["qld_id"];
		
		$_dados = $this->_Model->select("estoque", $_stk_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Estoque = $_dados[0];
		} else {
		    //Se nao achou o estoque, indica que nao achou
		    $_flg_estoque = false;
		  
		}
		//echo "<br>--------------------------------------------------------> Como esta o estoque: ".print_r($_flg_estoque);
		//echo "<br>--------------------------------------------------------> Quantidade Estoque : ".$this->_Estoque['stq_qtddis'];
		
		$_stk = new estoqueModel();
		$_stk->init();

		$_stk_dados["stq_id"] = 0;
		$_stk_dados["emp_id"] = $this->_Empresa["emp_id"];
		$_stk_dados["fil_id"] = $this->_Filial["fil_id"];
		$_stk_dados["arm_id"] = $this->_Armazem_Origem["arm_id"];
		$_stk_dados["sba_id"] = $this->_SubArmazem_Origem["sba_id"];
		$_stk_dados["prd_id"] = $this->_Produto["prd_id"];
		$_stk_dados["itm_id"] = $this->_Item["itm_id"];
		$_stk_dados["qld_id"] = $this->_Qualidade["qld_id"];
		
		if ( $_flg_estoque == true ) {
 		   // Existe, carrega o que tem
		   $_stk_dados["stq_id"]     = $this->_Estoque["stq_id"];
		   $_stk_dados["stq_qtddis"] = $this->_Estoque["stq_qtddis"];
		   $_stk_dados["stq_qtdres"] = $this->_Estoque["stq_qtdres"];
		   $_stk_dados["stq_qtdbko"] = $this->_Estoque["stq_qtdbko"];
		   $_stk_dados["stq_qtdlit"] = $this->_Estoque["stq_qtdlit"];
		   $_stk_dados["stq_qtdqua"] = $this->_Estoque["stq_qtdqua"];
		   $_stk_dados["stq_qtdfat"] = $this->_Estoque["stq_qtdfat"];
		   $_stk_dados["stq_qtdtrn"] = $this->_Estoque["stq_qtdtrn"];
		   $_stk_dados["stq_qtdemr"] = $this->_Estoque["stq_qtdemr"];
		   $_stk_dados["stq_qtdrec"] = $this->_Estoque["stq_qtdrec"];
		   $_stk_dados["stq_qtdmet"] = $this->_Estoque["stq_qtdmet"];
		   $_stk_dados["stq_qtdorf"] = $this->_Estoque["stq_qtdorf"];
		   $_stk_dados["stq_ativo"]  = $this->_Estoque["stq_ativo"];
		   $_stk_dados["stq_valido"] = $this->_Estoque["stq_valido"];
		   $_stk_dados["stq_dtmreg"] = $this->_Estoque["stq_dtmreg"];
		   $_stk_dados["stq_dtmupd"] = $this->_Estoque["stq_dtmupd"];
		   $_stk_dados["stq_dtmsyn"] = $this->_Estoque["stq_dtmsyn"];
		   $_stk_dados["usr_id_reg"] = $this->_Estoque["usr_id_reg"];
		   $_stk_dados["usr_id_upd"] = $this->_Usuario["usr_id"];
		   $_stk_dados["usr_id_syn"] = $this->_Estoque["usr_id_syn"];
		} else {
		   // Nao existia
		   $_stk_dados["stq_qtddis"] = 0;			
		   $_stk_dados["stq_qtdres"] = 0;
		   $_stk_dados["stq_qtdbko"] = 0;
		   $_stk_dados["stq_qtdlit"] = 0;
		   $_stk_dados["stq_qtdqua"] = 0;
		   $_stk_dados["stq_qtdfat"] = 0;
		   $_stk_dados["stq_qtdtrn"] = 0;
		   $_stk_dados["stq_qtdemr"] = 0;
		   $_stk_dados["stq_qtdrec"] = 0;
		   $_stk_dados["stq_qtdmet"] = 0;
		   $_stk_dados["stq_qtdorf"] = 0;
		   $_stk_dados["stq_ativo"]  = "N";
		   $_stk_dados["stq_valido"] = "N";
		   $_stk_dados["stq_dtmreg"] = "0000-00-00 00:00:00";
		   $_stk_dados["stq_dtmupd"] = "0000-00-00 00:00:00";
		   $_stk_dados["stq_dtmsyn"] = "0000-00-00 00:00:00";
		   $_stk_dados["usr_id_reg"] = $this->_Usuario["usr_id"];
		   $_stk_dados["usr_id_upd"] = $this->_Usuario["usr_id"];
		   $_stk_dados["usr_id_syn"] = 0;			
		}
		
		//echo "<br>----------------------- {$this->_Transacao['trn_entsai']} ---------------- Antes: ".$_stk_dados["stq_qtddis"];
		
		// Verificando Transacao
		$_quantidade_antes = $_stk_dados["stq_qtddis"];
		if ( $this->_Transacao["trn_entsai"] == "E") {
		   $_stk_dados["stq_qtddis"] = $_stk_dados["stq_qtddis"] + $_quantidade;
		} else {
		   $_stk_dados["stq_qtddis"] = $_stk_dados["stq_qtddis"] - $_quantidade;
		}
		$_quantidade_depois = $_stk_dados["stq_qtddis"];
		
		//echo "<br>------------------------- Depois: ".$_stk_dados["stq_qtddis"];
		
		// Verifica se o registro existia e executa a operacao de acordo
		// com esse controle
		if ( $_flg_estoque == true ) {
			$_sql = "UPDATE estoque "
			      . "   SET stq_qtddis = '{$_stk_dados["stq_qtddis"]}'"
			      . " WHERE ".$_stk_where;
		   $_ret_db = $_stk->freeHandUpdate($_sql);
		} else {
		   $_ret_db = $_stk->insert($_stk_dados);
		}
		
		//echo "<br>------------------------------------> Retorno Estoque: ";
		//print_r($_ret_db);
		
		//echo "<br>------------------------------------> LAST SQL: ". $_stk->_sql_ultimo;
		
		// Atualizando o endereco
		$_flg_estoque_endereco = true;
		$_ste_where = " emp_id = " . $this->_Empresa["emp_id"]
		        . " AND fil_id = " . $this->_Filial["fil_id"]
		        . " AND arm_id = " . $this->_Armazem_Origem["arm_id"]
		        . " AND sba_id = " . $this->_SubArmazem_Origem["sba_id"]
		        . " AND ede_id = " . $this->_Endereco_Estoque["ede_id"]
		        . " AND itm_id = " . $this->_Item["itm_id"]
		        . " AND prd_id = " . $this->_Produto["prd_id"]
		        . " AND qld_id = " . $this->_Qualidade["qld_id"];		
		$_dados = $this->_Model->select("estoque_endereco", $_ste_where, 1);
		if ( count($_dados) > 0 ) {
			$this->_Estoque_Endereco = $_dados[0];
		} else {
			//Se nao achou o estoque, indica que nao achou
			$_flg_estoque_endereco = false;		
		}
		
		$_ste = new estoqueenderecoModel();
		$_ste->init();
		
		$_ste_dados["ste_id"]         = 0;
		$_ste_dados["emp_id"]         = $this->_Empresa["emp_id"];
		$_ste_dados["fil_id"]         = $this->_Filial["fil_id"];
		$_ste_dados["arm_id"]         = $this->_Armazem_Origem["arm_id"];
		$_ste_dados["sba_id"]         = $this->_SubArmazem_Origem["sba_id"];		
		$_ste_dados["ede_id"]         = $this->_Endereco_Estoque["ede_id"];
		$_ste_dados["itm_id"]         = $this->_Item["itm_id"];
		$_ste_dados["prd_id"]         = $this->_Produto["prd_id"];		
		$_ste_dados["qld_id"]         = $this->_Qualidade["qld_id"];
	
		if ( $_flg_estoque_endereco == true ) {
			// Existe, carrega o que tem
			$_ste_dados["ste_id"]     = $this->_Estoque_Endereco["ste_id"];
			$_ste_dados["stq_qtddis"] = $this->_Estoque_Endereco["stq_qtddis"];
			$_ste_dados["stq_qtdres"] = $this->_Estoque_Endereco["stq_qtdres"];
			$_ste_dados["stq_qtdbko"] = $this->_Estoque_Endereco["stq_qtdbko"];
			$_ste_dados["stq_qtdlit"] = $this->_Estoque_Endereco["stq_qtdlit"];
			$_ste_dados["stq_qtdqua"] = $this->_Estoque_Endereco["stq_qtdqua"];
			$_ste_dados["stq_qtdfat"] = $this->_Estoque_Endereco["stq_qtdfat"];
			$_ste_dados["stq_qtdtrn"] = $this->_Estoque_Endereco["stq_qtdtrn"];
			$_ste_dados["stq_qtdemr"] = $this->_Estoque_Endereco["stq_qtdemr"];
			$_ste_dados["stq_qtdrec"] = $this->_Estoque_Endereco["stq_qtdrec"];
			$_ste_dados["stq_qtdmet"] = $this->_Estoque_Endereco["stq_qtdmet"];
			$_ste_dados["stq_qtdorf"] = $this->_Estoque_Endereco["stq_qtdorf"];
			$_ste_dados["stq_ativo"]  = $this->_Estoque_Endereco["stq_ativo"];
			$_ste_dados["stq_valido"] = $this->_Estoque_Endereco["stq_valido"];
			$_ste_dados["stq_dtmreg"] = $this->_Estoque_Endereco["stq_dtmreg"];
			$_ste_dados["stq_dtmupd"] = $this->_Estoque_Endereco["stq_dtmupd"];
			$_ste_dados["stq_dtmsyn"] = $this->_Estoque_Endereco["stq_dtmsyn"];
			$_ste_dados["usr_id_reg"] = $this->_Estoque_Endereco["usr_id_reg"];
			$_ste_dados["usr_id_upd"] = $this->_Usuario["usr_id"];
			$_ste_dados["usr_id_syn"] = $this->_Estoque_Endereco["usr_id_syn"];
		} else {
			// Nao existia
			$_ste_dados["stq_qtddis"] = 0;
			$_ste_dados["stq_qtdres"] = 0;
			$_ste_dados["stq_qtdbko"] = 0;
			$_ste_dados["stq_qtdlit"] = 0;
			$_ste_dados["stq_qtdqua"] = 0;
			$_ste_dados["stq_qtdfat"] = 0;
			$_ste_dados["stq_qtdtrn"] = 0;
			$_ste_dados["stq_qtdemr"] = 0;
			$_ste_dados["stq_qtdrec"] = 0;
			$_ste_dados["stq_qtdmet"] = 0;
			$_ste_dados["stq_qtdorf"] = 0;
			$_ste_dados["stq_ativo"]  = "N";
			$_ste_dados["stq_valido"] = "N";
			$_ste_dados["stq_dtmreg"] = "0000-00-00 00:00:00";
			$_ste_dados["stq_dtmupd"] = "0000-00-00 00:00:00";
			$_ste_dados["stq_dtmsyn"] = "0000-00-00 00:00:00";
			$_ste_dados["usr_id_reg"] = $this->_Usuario["usr_id"];
			$_ste_dados["usr_id_upd"] = $this->_Usuario["usr_id"];
			$_ste_dados["usr_id_syn"] = 0;
		}

		//echo "<br>----------------------- {$this->_Transacao['trn_entsai']} ---------------- Antes: ".$_ste_dados["stq_qtddis"];
		
		//print_r($_ste_dados);
		
		// Verificando Transacao
		$_quantidade_antes = $_ste->validaDecimalNulo($_ste_dados["stq_qtddis"]);
		
		if ( $this->_Transacao["trn_entsai"] == "E") {
			$_ste_dados["stq_qtddis"] = $_ste->validaDecimalNulo($_ste_dados["stq_qtddis"]) + 
			                            $_ste->validaDecimalNulo($_quantidade);
		} else {
			$_ste_dados["stq_qtddis"] = $_ste->validaDecimalNulo($_ste_dados["stq_qtddis"]) -
			                            $_ste->validaDecimalNulo($_quantidade);
		}
		
		$_quantidade_depois = $_ste->validaDecimalNulo($_ste_dados["stq_qtddis"]);
		
		//echo "<br>------------------------- Depois: ".$_ste_dados["stq_qtddis"];
		
		//echo "<br>------------------------- Kardex  Antes: ".$_quantidade_antes;
		//echo "<br>-------------------------   Movimentada: ".$_quantidade;
		//echo "<br>-------------------------        Depois: ".$_quantidade_depois;		
		
		// Verifica se o registro existia e executa a operacao de acordo
		// com esse controle
		if ( $_flg_estoque_endereco == true ) {
			$_sql = "UPDATE estoque_endereco "
					. "   SET stq_qtddis = '{$_ste_dados["stq_qtddis"]}'"
					. " WHERE ".$_ste_where;
			$_ret_db = $_ste->freeHandUpdate($_sql);
		} else {
			$_ret_db = $_ste->insert($_ste_dados);
		}

		//echo "<br>------------------------------------> Retorno Estoque_Endereco: ";
		//print_r($_ret_db);
		
		//echo "<br>------------------------------------> LAST SQL: ". $_ste->_sql_ultimo;
		
				
		// Ataualizar EstoqueEndereco
		// Gerar Kardex
		$_flg_kardex = true;
		$_kdx_dados["kdx_id"]         = 0;
		$_kdx_dados["emp_id"]         = $this->_Empresa["emp_id"];
		$_kdx_dados["fil_id"]         = $this->_Filial["fil_id"];
		$_kdx_dados["arm_id"]         = $this->_Armazem_Origem["arm_id"];
		$_kdx_dados["sba_id"]         = $this->_SubArmazem_Origem["sba_id"];
		$_kdx_dados["emp_id_para"]    = $this->_Empresa["emp_id"];
		$_kdx_dados["fil_id_para"]    = $this->_Filial["fil_id"];
		$_kdx_dados["arm_id_para"]    = $this->_Armazem_Destino["arm_id"];
		$_kdx_dados["sba_id_para"]    = $this->_SubArmazem_Destino["sba_id"];
		$_kdx_dados["itm_id"]         = $this->_Item["itm_id"];
		$_kdx_dados["prd_id"]         = $this->_Produto["prd_id"];
		$_kdx_dados["dec_id_01"]      = $this->_Produto["dec_id_01"];
		$_kdx_dados["dec_id_02"]      = $this->_Produto["dec_id_02"];
		$_kdx_dados["dec_id_03"]      = $this->_Produto["dec_id_03"];
		$_kdx_dados["qld_id"]         = $this->_Qualidade["qld_id"];
		$_kdx_dados["ttr_id"]         = $this->_Transacao["ttr_id"];
		$_kdx_dados["trn_id"]         = $this->_Transacao["trn_id"];
		$_kdx_dados["ent_id"]         = 0;
		$_kdx_dados["ede_id"]         = $this->_Endereco_Estoque["ede_id"];
		$_kdx_dados["rua_id"]         = $this->_Rua["rua_id"];
		$_kdx_dados["niv_id"]         = $this->_Nivel["niv_id"];
		$_kdx_dados["box_id"]         = $this->_Box["box_id"];
		$_kdx_dados["kdx_datmov"]     = $_CURRENT_DATETIME;
		$_kdx_dados["kdx_datefe"]     = $_CURRENT_DATE;
		$_kdx_dados["kdx_refmvt"]     = "";
		$_kdx_dados["kdx_obsmvt"]     = "";
		$_kdx_dados["kdx_prcmvt"]     = 0;
		$_kdx_dados["kdx_cusmvt"]     = 0;
		$_kdx_dados["kdx_qtdant"]     = $_quantidade_antes;
		$_kdx_dados["kdx_qtdmvt"]     = $_quantidade;
		$_kdx_dados["kdx_qtddep"]     = $_quantidade_depois;
		$_kdx_dados["usr_id_reg"]     = $this->_Usuario["usr_id"];
		$_kdx_dados["kdx_dtmreg"]     = $_CURRENT_DATETIME;		
		$_kdx = new kardexModel();
		$_kdx->init();
		
		$_ret_db = $_kdx->insert($_kdx_dados);
		
		//echo "<br>----------------------------SQL: ".$_kdx->_sql_ultimo;
		
		// Carregando os SubArmazens
		
		// Carregando SubArmazem: ORIGEM
				
		// se chogou ate aqui esta tudo ok
		return $_return;		
	}
}
?>