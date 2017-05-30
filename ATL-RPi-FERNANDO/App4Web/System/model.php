<?php
class Model{
	  protected $db;
	  public $_tabela_nome;   // objeto table data gatway - trabalha com uma tabela por model
	  public $_tabela;        // table data gatway - trabalha com uma tabela por model
	  public $_colunas;       // objeto com as Colunas da tabela a ser manipulada
	  
	  //public $_coluna_atributos;   // objeto com as Colunas Adicionais de CFG da tabela a ser manipulada
	  
	  public $_paginacao;     // objeto com as Colunas da tabela a ser manipulada
	  public $_tabs;          // Lista de TAB-SETS 
	  public $_last_insert_id; // Ultimo ID inserido
	  public $_mode;         // S-elect/Nevegando, I-nsert ou U-pdate
	  
	  public $_col_key;       // Coluna usada para buscar o codigo e descricao usada pelas select: Tabela, Codigo e TipoCodigo
	  public $_col_descricao;
	  public $_col_valor;
	  
	  private $_session;
	  
	  public $_sql_ultimo = null;
	  
	  public function __construct() {
	  	 
	  	  //echo "Model->Construct: aqui<br>";
	  	  // Iniciando a sessao para esse objeto e pegando os dados de la :)
	  	  $this->_session = new sessionHelper();
	  	  
	  	  // Pegando as credenciais do DOMINIO logado conforme config.php
	  	  $_db_server = $this->_session->selectSession("DOM_DB_SERVER");
	  	  $_db_name   = $this->_session->selectSession("DOM_DB_NAME");
	  	  $_db_login  = $this->_session->selectSession("DOM_DB_USER");
	  	  $_db_passwd = $this->_session->selectSession("DOM_DB_PASSWD");
	  	  
	  	  //echo "Model->DB: {$_db_name}";
	  	  $_pdo_string = "mysql:host={$_db_server};dbname={$_db_name};charset=utf8";
	  	  
	  	  //echo "--------------------> Model->DB: {$_pdo_string}, User:{$_db_login} e Passwd: {$_db_passwd}";
	  	  
		  if ( $_db_server == null or empty($_db_server) ) {
			$this->db = null;
		  } else {
			$this->db = new PDO($_pdo_string,$_db_login,$_db_passwd);
		  }		  
		  
		  //$this->db = new PDO('mysql:host=11.12.13.30;dbname=clog_erp4web','clog_erpadmin','it#123456');
	  	  //$this->db = new PDO('mysql:host=11.12.13.30;dbname=dblux','root','vertrigo');
		  //$this->db = new PDO('mysql:host=11.12.13.30;dbname=db','root','vertrigo'); //Vulka
	  	
	  	  $this->_paginacao["corrente"] = 0;
	  	  $this->_paginacao["primeira"] = 0;
	  	  $this->_paginacao["proxima"] = 0;
	  	  $this->_paginacao["anterior"] = 0;
	  	  $this->_paginacao["ultima"] = 0;
	  	  $this->_paginacao["total"] = 0;
	  	  $this->_paginacao["linhas_por_pagina"] = 0;
	  	  $this->_paginacao["linhas_total"] = 0;
	  	  $this->_paginacao["linhas_ultima_pagina"] = 0;
	  	  $this->_paginacao["offset"] = 0;
	  	  $this->_last_insert_id = 0;
	  	  
 	  	  $this->_mode = "SELECT"; 
	  	  
	  }
	  
	  public function getSqlUltimo() {
	  	return $this->_sql_ultimo;
	  }
	  
	  public function load() {
	    // Carrega a _tabela_nom e suas colunas do banco de dados e transforma em objeto
	    
	    // Carrega tabela
	    $_where = "tab_cod = '{$this->_tabela_nome}'";	    
	    $_tab = $this->select( "_tabela", $_where, 1);
	    if ( count($_tab) <= 0 ) {
	       // Tabela nao cadastrada
	       return null;
	    }	    
	    $this->_tabela = new Tabela($_tab[0]['tab_cod'], $_tab[0]['tab_descr'], $_tab[0]['idf_cod'],$_tab[0]['tab_tipo'], $_tab[0]['tab_cod_original'], $_tab[0]['tab_order_by']);

	    // Completando Objeto com dados da base que nao fazem parte da assinatura
	    $this->_tabela->setTcdId($_tab[0]['tcd_id']);
	    $this->_tabela->setEntIdColuna($_tab[0]['col_ent_id']);
	    $this->_tabela->setOrgIdColuna($_tab[0]['col_org_id']);
	    $this->_tabela->setEmpIdColuna($_tab[0]['col_emp_id']);
	    $this->_tabela->setFilIdColuna($_tab[0]['col_fil_id']);
	    
	    //echo "Tabela: ".$this->_tabela->getNome()."<br>";
	        
	    // Varrendo colunas da tabela e gerando a classe para cada coluna
	    // Carrega Informacoes de Configura��o da Coluna como FK e como buscar descricao para
	    // o valor carregado na tabela
		$_select   = "col.*";
		$_from     = "_tabela_coluna tco, _coluna col";
		$_where    = " tco.tab_cod = '{$this->_tabela_nome}'".
		             " and tco.col_cod = col.col_cod";
		$_order_by = "tco.col_ordem asc";
		
		$_col = $this->query($_select, $_from, $_where, null, null, $_order_by);
		
		//echo "<br>--------------------------------------------------->QUERY: ".$this->_sql_ultimo;
		
		if ( count($_col) <= 0 ) {
	    	// Colunas da Tabela nao cadastrada
	    	return null;
	    }
	    $arrColunas = null;
	    $arrInd = 0; 
	    for ($c =0; $c < count($_col) ; $c++) {
	    	//Criando a coluna e colocando num array
	    	//echo "Coluna: ".$_col[$c]['col_cod']."<br>";	    
			
	    	$arrColunas[$arrInd] = new Coluna($_col[$c]['col_id'],                           // ID
	    			                          $_col[$c]['col_cod'],                          // Codigo 
	    			                          $_col[$c]['col_descr']);                       // Descricao, 
	    	// Completando os dados da Coluna		                              
			$arrColunas[$arrInd]->setColCodOriginal($_col[$c]['col_cod_original']);                // Coluna Original para View
			$arrColunas[$arrInd]->setColCodOriginalSubstr($_col[$c]['col_cod_original_substr']);   // Conmtrole de Substring para Coluna Original
			$arrColunas[$arrInd]->setColTipo($_col[$c]['col_tipo']);                               // Tipo
			$arrColunas[$arrInd]->setColTamanho($_col[$c]['col_tamanho']);                   // Tamanho
			$arrColunas[$arrInd]->setColTamanhoDecinal($_col[$c]['col_tamanho_decimal']);    // Tamanho Decimal
			$arrColunas[$arrInd]->setColNulo($_col[$c]['col_nulo']);                         // Nulo			
			$arrColunas[$arrInd]->setColGrupoInformacao($_col[$c]['col_grupo_informacao']);  // Grupo Informacao			
			$arrColunas[$arrInd]->setColGrupoFormulario($_col[$c]['col_grupo_formulario']);  // Grupo Formulario
			$arrColunas[$arrInd]->setColDefault($_col[$c]['col_default']);                   // Default					
	    	$arrColunas[$arrInd]->setColStaticText($_col[$c]['col_statictext']);             // Static Text
	    	$arrColunas[$arrInd]->setColReadOnly($_col[$c]['col_readonly']);                 // ReadOnly
	    	$arrColunas[$arrInd]->setColCurrent($_col[$c]['col_current']);                   // Current
	    	$arrColunas[$arrInd]->setColCurrentAcao($_col[$c]['col_current_acao']);          // Current Acao: INS ou UPD
	    	$arrColunas[$arrInd]->setColShiftCase($_col[$c]['col_shiftcase']);               // Shift Case
	    	$arrColunas[$arrInd]->setColEntId($_col[$c]['col_ent_id']);                      // Usa Ent_id S/N
	    	$arrColunas[$arrInd]->setColTabCod($_col[$c]['col_tab_cod']);                    // Tabela 
	    	$arrColunas[$arrInd]->setColCodId($_col[$c]['col_cod_id']);                      // Coluna ID
	    	$arrColunas[$arrInd]->setColCodCod($_col[$c]['col_cod_cod']);                    // Coluna Codigo
	    	$arrColunas[$arrInd]->setColCodDescr($_col[$c]['col_cod_descr']);                // Coluna Descricao
	    	$arrColunas[$arrInd]->setCodCod($_col[$c]['cod_cod']);                           // Codigo pelo ID ?
	    	$arrColunas[$arrInd]->setTcdCod($_col[$c]['tcd_cod']);                           // Tipo Codigo  pelo Tipo Codigo e Valor
	    	
	    	$arrInd++;
	    }
        
        // Continuo carregando os dados da _tabela_coluna
	    // Carrega colunas
	    $_where = "tab_cod = '{$this->_tabela_nome}'";
	    $_order_by = "col_ordem asc";
	    $_tco = $this->select("_tabela_coluna", $_where, null, null, $_order_by);
	    
	    //echo "<br>--------------------------------------------------->SQL: ".$this->_sql_ultimo;
	    
	    if ( count($_tco) <= 0 ) {
	    	// Colunas da Tabela nao cadastrada
	    	return null;
	    }
	    $arrInd = 0;
	    $_tot = count($_tco);
	    //echo "Colunas da Tabela Carregada: {$_tot}<br>";
	    for ($c =0; $c < count($_tco) ; $c++) {
	    	//Criando a coluna e colocando num array	    	
	    	$arrColunas[$arrInd]->setTcoId($_tco[$c]['tco_id']);                             // ID na _tabela_coluna
	    	$arrColunas[$arrInd]->setColOrdem($_tco[$c]['col_ordem']);                       // Ordem
	    	$arrColunas[$arrInd]->setColAuto($_tco[$c]['col_auto']);                         // Autoincremnt/Serial
	    	$arrColunas[$arrInd]->setColPk($_tco[$c]['col_pk']);                             // Chave Primaria
	    	$arrColunas[$arrInd]->setColKey($_tco[$c]['col_key']);                           // Chave Unica para validacao de dados
	    	$arrColunas[$arrInd]->setColValorFixo($_tco[$c]['col_valor_fixo']);              // Valor Fixo
	    	$arrColunas[$arrInd]->setColCodOriginalExcecao($_tco[$c]['col_cod_original_excecao']);          // Coluna Original Excecao para View
	    	$arrColunas[$arrInd]->setColFiltro($_tco[$c]['col_filtro']);                     // Pode usar para Filtro
			$arrColunas[$arrInd]->setColRelatorio($_tco[$c]['col_relatorio']);               // Pode usar para Relatorio
	    	$arrColunas[$arrInd]->setColEhId($_tco[$c]['col_eh_id']);                        // Eh a Coluna de ID ?
	    	$arrColunas[$arrInd]->setColEhCod($_tco[$c]['col_eh_cod']);                      // Eh a coluna de Cod ?
	    	$arrColunas[$arrInd]->setColEhDescr($_tco[$c]['col_eh_descr']);                  // Eh a coluna de DESCR ?
	    	$arrColunas[$arrInd]->setColVisible($_tco[$c]['col_visible']);                   // Eh Visivel ?
	    	$arrColunas[$arrInd]->setColFixedWText($_tco[$c]['col_fixed_w_text']);           // Width para tabelas
	    	$arrColunas[$arrInd]->setColFixedSInput($_tco[$c]['col_fixed_s_input']);         // Width para INPUT
	    	

	    	//echo "id#:{$_tco[$c]['tco_id']}  no# {$c} pos:{$arrInd} -->Coluna: ".$_tco[$c]['col_cod']." Excecao: {$_tco[$c]['col_cod_original_excecao']} <br>";
	    	
	    	if ( $_tco[$c]['col_grupo_informacao'] <> "NON" ) {
	    	   $arrColunas[$arrInd]->setColGrupoInformacao($_tco[$c]['col_grupo_informacao']);  // Grupo Informacao
	    	}
	    	
	    	// Caso seja um ID ele tem q esta na Guia Basica
	    	if ( $_tco[$c]['col_eh_id'] == true ) {
	    	   //forcando 
	    	   $arrColunas[$arrInd]->setColGrupoInformacao("BAS");                           // Grupo Informacao
	    	}
	    	$arrInd++;
	    }
	    // Atribuindo colunas para a tabela
	    $this->_tabela->setColunas($arrColunas);
	    
		// Carrega Grupos de Informacao
		$_select   = "DISTINCT gri.gri_id, gri.gri_cod, gri.gri_descr, gri.gri_ordem, 0 gri_selecionado";
		$_from     = "_tabela_coluna tco, _coluna col, _grupo_informacao gri";
		$_where    = " tco.tab_cod = '{$this->_tabela_nome}'".
			         " and tco.col_cod = col.col_cod ".
			         " and tco.col_visible = 'S' ".
		             " and IF(tco.col_grupo_informacao = 'NON',col.col_grupo_informacao,tco.col_grupo_informacao) = gri.gri_cod";
		$_order_by = "gri.gri_ordem asc";
		$_gri = $this->query($_select, $_from, $_where, null, null, $_order_by);
		if ( count($_gri) <= 0 ) {
			// Colunas da Tabela nao cadastrada
			return null;
		}
		// Varrendo colunas da tabela e gerando a classe para cada coluna
		$this->_tabs = array();
		$arrTabs = 0;
		for ($c =0; $c < count($_gri) ; $c++) {
			//Criando o grupo na lista  TABS
			//echo "Gri: ".$_gri[$c]['gri_cod']."<br>";
		
			if ( $_gri[$c]['gri_cod'] == "BAS") {
				// Selcionado por DEFAULT
				$_gri[$c]['gri_selecionado'] = true;
			} else { 
				$_gri[$c]['gri_selecionado'] = false;
			}
			// Cria o objeto e adiciona no array de TABS
			$this->_tabs[] = new GrupoInformacao($_gri[$c]['gri_id'],           // ID
					                             $_gri[$c]['gri_cod'],          // Codigo 
					                             $_gri[$c]['gri_descr'],        // Descricao 
					                             $_gri[$c]['gri_ordem'],        // Ordem
					                             $_gri[$c]['gri_selecionado']); // Selecionado
		}		
		return $this;
	  }
	  
	  public function add_frhTabs($aId, $aCod, $aDescr, $aOrdem, $aSelected) {
	  	// Gera uma Free Hand Tab Set
	  	// Varrendo Tabs para ver se ja nao existe a solicitada
	  	$ja = false;
	  	for ($c=0; $c < count($this->_tabs); $c++) {
	  		if ( $this->_tabs[$c]->_codigo == $aCod ) {
	  			$ja = true;
	  			break;
	  		}
	  	}
	  	
	  	if ($ja == false) {
	  		// Adicionando TAB
	  		$this->_tabs[] = new GrupoInformacao($aId,           // ID
	  				                             $aCod,          // Codigo
	  				                             $aDescr,        // Descricao
	  				                             $aOrdem,        // Ordem
	  				                             $aSelected);    // Selecionado	  		
	  	}
	  	
	  	return $this;
	  	
	  }
	  
	  public function add_griTabs($aTabCod) {
	  	// Verifica se o Grupo Informacao solicitado existe
	  	$_select   = "gri.gri_id, gri.gri_cod, gri.gri_descr, gri.gri_ordem, 0 gri_selecionado";
	  	$_from     = "_grupo_informacao gri";
	  	$_where    = "gri.gri_cod = '{$aTabCod}'";
	  	$_order_by = "";
	  	$_gri = $this->query($_select, $_from, $_where, null, null, $_order_by);
	  
	  	//echo "<br>-------------------------------------------> add_tabs -> ".$this->_sql_ultimo;
	  
	  	if ( count($_gri) <= 0 ) {
	  		// Aba nao cadastrada
	  		return null;
	  	}
	  	// Varrendo Tabs para ver se ja nao existe a solicitada
	  	$ja = false;
	  	for ($c=0; $c < count($this->_tabs); $c++) {
	  		if ( $this->_tabs[$c]->_codigo == $_gri[0]['gri_cod'] ) {
	  			$ja = true;
	  			break;
	  		}
	  	}
	  
	  	if ($ja == false) {
	  		// Adicionando TAB
	  		$this->_tabs[] = new GrupoInformacao($_gri[0]['gri_id'],           // ID
	  				$_gri[0]['gri_cod'],          // Codigo
	  				$_gri[0]['gri_descr'],        // Descricao
	  				$_gri[0]['gri_ordem'],        // Ordem
	  				$_gri[0]['gri_selecionado']); // Selecionado
	  	}
	  
	  	return $this;
	  
	  }	  
	  
	  public function select_tabs($aTabs) {
	     // Percorre array de TABS e indica qual esta selecionada
	     // Se nao acha, aciona a BAS
	     $_selected = null;
	     for ($i=0; $i < count($this->_tabs); $i++ ) {
	     	if ( $this->_tabs[$i]->_codigo == $aTabs) {
	     		$this->_tabs[$i]->_selecionado = true;
	     		$_selected = $this->_tabs[$i];
	     	} else {
	     		$this->_tabs[$i]->_selecionado = false;
	     	}
	     }
	      
	     //retorna a TAB selcionada
	     return $_selected;
	  }
	  
	  public function initialize() {
	    // Inicializa colunas com o DEFAULT ou com os valores fixos pre-definidos
	    $_valor_fixo = $this->_tabela->_colunas[$aColuna]->getColValorFixo(); 
	  	if ( $_valor_fixo == "NOME" ) {
	  		$_valor_fixo = $this->_tabela->_colunas[$aColuna]->getColValorDefault();
	  	}
	  	$this->_tabela->_colunas[$aColuna]->setColValor($_valor_fixo);
	  }
	  	  
	  public function load_colunas($aEntId, $aColuna, $aValor) {
	    // Monta uma query para buscar os dados da coluna de FK ou TCD
	  	// Carrega Grupos de Informacao
	  	
	  	$rValor = $aValor;
	  	
	  	if ( $this->_tabela->_colunas[$aColuna]->_col_auto != "S" ) {
	  		
	  		$rValor = "NONE";
	  		
	  	    $_id            = $this->_tabela->_colunas[$aColuna]->_col_cod_id;
		  	$_cod           = $this->_tabela->_colunas[$aColuna]->_col_cod_cod;		
		  	$_statictet     = $this->_tabela->_colunas[$aColuna]->_col_statictext;
		  	$_readonly      = $this->_tabela->_colunas[$aColuna]->_col_readonly;
		  	$_current       = $this->_tabela->_colunas[$aColuna]->_col_current;
		  	$_visible       = $this->_tabela->_colunas[$aColuna]->_col_visible;
		  	$_fixed_w_text  = $this->_tabela->_colunas[$aColuna]->_col_fixed_w_text;
		  	$_fixed_s_input = $this->_tabela->_colunas[$aColuna]->_col_fixed_s_input;
		  	$_shiftcase     = $this->_tabela->_colunas[$aColuna]->_col_shiftcase;
		  	$_col_ent_id    = $this->_tabela->_colunas[$aColuna]->_col_ent_id;
		  	$_descr         = $this->_tabela->_colunas[$aColuna]->_col_cod_descr;
		  	$_tabela        = $this->_tabela->_colunas[$aColuna]->_col_tab_cod;
		  	$_codigo        = $this->_tabela->_colunas[$aColuna]->_cod_cod;
		  	$_tipocodigo    = $this->_tabela->_colunas[$aColuna]->_tcd_cod;
		  	$_valor         = $aValor;

		  	//echo "COLUNA: {$aColuna} ID: {$_id} CODIGO: {$_codigo}  VALOR: {$aValor}";
		  	//echo "<br>---------------------------------------------> ATR Coluna: {$aColuna} Fixed Width -> ".$this->_tabela->_colunas[$aColuna]->_col_fixed_w_text;
		  	
		  	if ( $_id != "NONE") {
		  		$_select   = $_id.",".$_cod.",".$_descr;	  
		  	    $_from     = $_tabela;
		  	    $_where    = $_id." = ".$_valor;	
		  	    
		  	    if ( $this->_tabela->_colunas[$aColuna]->_col_ent_id == "S" ) {
		  	    	$_where    .= " AND ent_id = ".$aEntId;
		  	    	//echo "Coluna: {$aColuna} e Condicao: {$_where}";
		  	    }
		  	    
		  	    $_qry = $this->query($_select, $_from, $_where, 1, null, null);	  	
		  	    //echo "Selected: ".$_qry[0][$_descr]."<br>";
		  	    if ($_qry) {
		  	    	$rValor = $_qry[0][$_descr];
		  	    } else {
		  	    	// Se nao achou o proprio codigo volta no lugar :)
		  	    	$rValor = $aValor;
		  	    }
		  	} elseif ( $_cod != "NONE" ) {
		  		$_select   = $_cod.",".$_descr;
		  		$_from     = $_tabela;
		  		$_where    = $_cod." = ".$_valor;
		  			
		  		if ( $this->_tabela->_colunas[$aColuna]->_col_ent_id == "S" ) {
		  			$_where    .= " AND ent_id = ".$aEntId;
		  		}
		  			
		  		$_qry = $this->query($_select, $_from, $_where, 1, null, null);
		  		if ($_qry) {
		  			$rValor = $_qry[0][$_descr];
		  		} else {
		  			// Se nao achou o proprio codigo volta no lugar :)
		  			$rValor = $aValor;
		  		}		  		
		  	} elseif ( $_codigo == "S" ) {
		  		//echo "Valor = {$aValor}";
		  		$_select   = "cod_id, cod_cod, cod_descr";
		  		$_from     = "codigo";
		  		$_where    = "cod_id = ".$_valor;
		  		$_qry = $this->query($_select, $_from, $_where, 1, null, null);
		  		// Verifica se os dados existem
		  		if ($_qry) {
		  			$rValor = $_qry[0]['cod_descr'];
		  		} else {
		  			// Se nao achou o proprio codigo volta no lugar :)
		  			$rValor = $aValor;
		  		}	  			
		  	} elseif ( $_tipocodigo != "NON" ) {
		  		$_select   = "cod.cod_id, cod.cod_cod, cod.cod_descr";
		  		$_from     = "codigo cod, tipo_codigo tcd";
		  		$_where    = " tcd.tcd_cod = '{$_tipocodigo}'".
						     " AND tcd.tcd_id = cod.tcd_id ".
		  		             " AND cod.cod_cod = '".$_valor."'";
		  		$_qry = $this->query($_select, $_from, $_where, 1, null, null);
		  		if ($_qry) {
		  			//print_r($_qry);
		  			$rValor = $_qry[0]['cod_descr'];
		  		} else {
		  			// Se nao achou o proprio codigo volta no lugar :)
		  			$rValor = $aValor;
		  		}		  				
		  	}
	  	}
	  	
		return $rValor;
		
	  }
	  
	  public function setColunas($arrColunas) {
	  	$this->_colunas = $arrColunas;
	  }
	  
	  public function verifyPK($_carregar) {
	  	$_where = " ";
	  	$_ctd = 0;
	  	
	  	// fase significa que nao existe e 
	  	// true significa que PK existe :)
	  	// Nao carrega nenhum dado
	  	$_ret = false;
	  	 	  	
	  	foreach ($this->_tabela->_colunas as $_col) {
	  		//echo "<br>Vendo coluna {$_col->getColNome()} eh ou nao {$_col->getColPk()}";
	  		if ( $_col->getColPk() == "S" ) {
	  			if ( $_ctd == 0 ) {
	  				$_where = $_col->getColNome() . " = " . $_col->_col_tipo->retWhereValor($_col->getColValor());
	  			} else {
	  			    $_where .= " and ".$_col->getColNome() . " = " . $_col->_col_tipo->retWhereValor($_col->getColValor());
	  			}	  				
	  			$_ctd++;
	  		}
	  	}
	  	// Executando q QUERY com a chave da PK
	  	//echo "<br>VerifyPK->read {$_where}";
	  	$_qry = $this->read($_where,1,0);
	  	
	  	//echo "SQL: ". $this->getSqlUltimo();
	  	//print_r($_qry);
	  	
	  	// Verificando se selecionou alguma coisa :)
	  	//echo "<br><br>Model->VerifyPK: [antes] ".$_where." Status : {$_ret}";
	  	//print_r($this->_tabela->_colunas['apl_id']);
	  	//echo "oputro teste: ";
	  	//echo $this->_tabela->_colunas['apl_descr']->getColValor();
	  	//echo " e mais esse ".$_qry[0]['apl_descr'];
	  	
	  	if ($_qry) {
	  		$_ret = true;
	  		if ($_carregar == true) {
	  		    foreach ($this->_tabela->_colunas as $_inds => $_vals){
	  		    	$this->_tabela->_colunas[$_inds]->setColValor($_qry[0][$_inds]);
	  		    	//echo "Valores {$_inds} valor: {$this->_tabela->_colunas[$_inds]->getColValor()}";
	  			}
	  		}
	  	} else {
	  		$_ret = false;
	  	}	  	
	  	
	  	//echo "<br><br>Model->VerifyPK: [depois] ".$_where." Status : {$_ret}";
	  	//print_r($_ret);
	  	
	  	return $_ret;
	  }

	  public function verifyKey($_carregar) {
	  	$_where = " ";
	  	$_ctd = 0;
	  
	  	// fase significa que nao existe e
	  	// true significa que PK existe :)
	  	// Nao carrega nenhum dado
	  	$_ret = false;
	  	 
	  	foreach ($this->_tabela->_colunas as $_col) {
	  		//echo "<br>Vendo coluna {$_col->getColNome()} eh ou nao {$_col->getColKey()}";
	  		if ( $_col->getColKey() == "S" ) {
	  			//$_col->setColValor(1);
	  			if ( $_ctd == 0 ) {
	  				$_where = $_col->getColNome() . " = " . $_col->_col_tipo->retWhereValor($_col->getColValor());
	  			} else {
	  				$_where .= " and ".$_col->getColNome() . " = " . $_col->_col_tipo->retWhereValor($_col->getColValor());
	  			}
	  			$_ctd++;
	  		}
	  	}
	  	// Executando q QUERY com a chave da PK
	  	//echo "<br>Paginacao->verifyKey {$_where}";
	  	$_qry = $this->read($_where,1,0);
	  
	  	//print_r($_qry);
	  
	  	// Verificando se selecionou alguma coisa :)
	  	//echo "<br><br>Model->VerifyPK: [antes] ".$_where." Status : {$_ret}";
	  	//print_r($this->_tabela->_colunas['apl_id']);
	  	//echo "oputro teste: ";
	  	//echo $this->_tabela->_colunas['apl_descr']->getColValor();
	  	//echo " e mais esse ".$_qry[0]['apl_descr'];
	  
	  	if ($_qry) {
	  		$_ret = true;
	  		if ($_carregar == true) {
	  			foreach ($this->_tabela->_colunas as $_inds => $_vals){
	  				//echo "<br>Valores {$_inds} valor: {$this->_tabela->_colunas[$_inds]->getColValor()}";
	  				$this->_tabela->_colunas[$_inds]->setColValor($_qry[0][$_inds]);	  				
	  			}
	  		}
	  	} else {
	  		$_ret = false;
	  	}
	  
	  	//echo "<br><br>Model->VerifyPK: [depois] ".$_where." Status : {$_ret}";
	  	//print_r($_ret);
	  
	  	return $_ret;
	  }
	  
	  public function setRowID($_id) {
	  	$_ret = false;
	  	
	  	//echo "<br> Recebeu o id : {$_id}";
	  	
	  	foreach ($this->_tabela->_colunas as $_col) {
	  		//echo "<br>Vendo coluna {$_col->getColNome()} eh ou nao {$_col->getColKey()}";
	  		if ( $_col->getColAuto() == "S" ) {
	  			$_col_name = $_col->getColNome();
	  			$this->_tabela->_colunas[$_col_name]->_col_valor = $_id;
	  			//echo "<br>Achou RowID: {$_col_name} valor a colocar: {$_id} Ficou: ".$this->_tabela->_colunas[$_col_name]->_col_valor;
	  			$_ret = true;
	  			break;
	  		}
	  	}
	  	return $_ret;
	  }
	  
	  public function verifyColumnSubstr() {
	  	// Identifica as COLUNAS com tratamento de SUBSTRING e monta a String Final para a operacao de banco: INSERT ou UPDATE
	  	$_campos = array();
	  	$_string = array();
	  	$_str    = "                                                                               ";  //80 char
	  	
	  	$_col_cod = "none";
	  	$_ctd = -1;
	  	$_de = 0;
	  	$_ate = 0;
	  	$_len = 0;
	  	$_ret = "";
	  		    	 
	  	// Varrendo as Colunas para identificar colunas com esse controle
	  	foreach ($this->_tabela->_colunas as $_col) {
	  	   $_substr = $_col->getColCodOriginalSubstr();
	  	   if ( $_substr != "0..0" ) {
	  	   	  $_col_cod = $_col->getColCodOriginal();
	  	      $_campos[$_col_cod][] = $_col;
	  	   }
	  	}
	  	
	  	//echo "<br>------------------------------------------------------------> Aqui";
	  	
	  	foreach ($_campos as $inds => $vals){
  	  	   
  	  	   //Correndo as colunas originais com controle de substring apenas
	  	   $_col_cod_ori = $inds;
	  	   $_tot = count($_campos[$inds]);
	  	   $_string[$_col_cod_ori] = $_str;
	  	   
	  	   for ( $_ind=0; $_ind < $_tot; $_ind++) {

	  	   	  // Correndo as colunas que compoem essa coluna original
	  	   	  $_col = $_campos[$_col_cod_ori][$_ind];
	  	   	  $_col_cod = $_col->getColCod(); // Coluna base

	  	   	  // Pegando configuracao de posicoes para compor o texto final
	  	   	  $_substr = $_col->getColCodOriginalSubstr();
	  	   	  
	  	   	  // Determiando as posicoes
	  	   	  //echo "<br>Montando Resultado: {$_col_cod} -> {$_substr}";
	  	   	  $_pos = explode("..",$_substr);	  	   	  
	  	   	  $_de = $_pos[0];
	  	   	  $_de--;
	  	   	  $_ate = $_pos[1];	  	   	  
	  	   	  $_len = $_ate - $_de;
	  	   	  
	  	   	  // Pegando Valor a concatenar
	  	   	  $_texto = $_col->getColValor();
	  	   	  
	  	   	  // Determinando a string Final :)
	  	   	 // echo "<br>Original: {$_col_cod_ori} Coluna: {$_col->getColCod()} valor: {$_texto} Substring: {$_substr} Coluna Original: {$_col->getColCodOriginal()} Posicao Tamanho: {$_len} onde de: {$_de} ate {$_ate}";
	  	   	  $_string[$_col_cod_ori] = substr_replace($_string[$_col_cod_ori],$_texto,$_de,$_len);
	  	   	  //echo "<br>Montando Resultado: {$_col_cod} -> {$_string[$_col_cod_ori]}";	  
	  	   	  
	  	   	  //Mostrando o texto na posicao que foi concatenado :()
	  	   	  //$_ok = substr($_string[$_col_cod_ori],$_de,$_len);
	  	   	  //echo "<br>--------------- {$_col_cod_ori} --- {$_substr} --------------------------------> Sera q deu certo? {$_ok}";
	  	   }
	  	}
	  	
	  	// Para todas as colunas com concatenacao, deve atribuir o valor concatenado para todas ficarem iguais
	  	foreach ($_campos as $inds => $vals){
	  	   //Correndo as colunas originais com controle de substring apenas
	  	   $_col_cod_ori = $inds;
	  	   $_tot = count($_campos[$inds]);
	  	   $_valor = $_string[$_col_cod_ori];
	  	   for ( $_ind=0; $_ind < $_tot; $_ind++) {
  	  	   	  // Correndo as colunas que compoem essa coluna original
	  	   	  $_col = $_campos[$_col_cod_ori][$_ind];	
	  	   	  $_col_cod = $_col->getColCod();
	  	   	  $_col->setColValor($_valor); // Coluna base	  
	  	   	  $this->_tabela->_colunas[$_col_cod]->setColValor($_valor);
	  	   	  //echo "<br>------------------------------>  Coluna Original: {$_col_cod_ori}, Coluna Concatenada: {$_col_cod}  Valor Final: {$_valor}";
	  	   }
	  	}	  	   	
	  }	  
	  
	  public function separaColumnSubstr() {
	  	// Identifica as COLUNAS com tratamento de SUBSTRING e monta a String Final para a operacao de banco: INSERT ou UPDATE
	  	$_campos = array();
	  	$_string = array();
	  	$_str    = "                                                                               ";  //80 char
	  
	  	$_col_cod = "none";
	  	$_ctd = -1;
	  	$_de = 0;
	  	$_ate = 0;
	  	$_len = 0;
	  	$_ret = "";
	  	 
	  	// Varrendo as Colunas para identificar colunas com esse controle
	  	foreach ($this->_tabela->_colunas as $_col) {
	  		$_substr = $_col->getColCodOriginalSubstr();
	  		if ( $_substr != "0..0" ) {
	  			$_col_cod = $_col->getColCodOriginal();
	  			$_campos[$_col_cod][] = $_col;
	  		}
	  	}
	  
	  	//echo "<br>------------------------------------------------------------> Separando";
	  
	  	foreach ($_campos as $inds => $vals){
	  		 
	  		//Correndo as colunas originais com controle de substring apenas
	  		$_col_cod_ori = $inds;
	  		$_tot = count($_campos[$inds]);
	  		$_string[$_col_cod_ori] = $_str;
	  			
	  		for ( $_ind=0; $_ind < $_tot; $_ind++) {
	  
	  			// Correndo as colunas que compoem essa coluna original
	  			$_col = $_campos[$_col_cod_ori][$_ind];
	  			$_col_cod = $_col->getColCod(); // Coluna base
	  
	  			// Pegando configuracao de posicoes para compor o texto final
	  			$_substr = $_col->getColCodOriginalSubstr();
	  
	  			// Determiando as posicoes
	  			//echo "<br>Montando Resultado: {$_col_cod} -> {$_substr}";
	  			$_pos = explode("..",$_substr);
	  			$_de = $_pos[0];
	  			$_de--;
	  			$_ate = $_pos[1];
	  			$_len = $_ate - $_de;
	  
	  			// Pegando Valor a concatenar
	  			$_texto = $_col->getColValor();

	            //echo "<br>----------------------------------------------> Texto: {$_texto}";
	  			// Determinando a string Final :)
	  			// echo "<br>Original: {$_col_cod_ori} Coluna: {$_col->getColCod()} valor: {$_texto} Substring: {$_substr} Coluna Original: {$_col->getColCodOriginal()} Posicao Tamanho: {$_len} onde de: {$_de} ate {$_ate}";
	  			//$_string[$_col_cod_ori] = substr_replace($_string[$_col_cod_ori],$_texto,$_de,$_len);	  			
	  			//echo "<br>Montando Resultado: {$_col_cod} -> {$_string[$_col_cod_ori]}";	  
	  			//Mostrando o texto na posicao que foi concatenado :()
	  			//$_ok = substr($_texto,$_de,$_len);
	  			
	  			$_col->setColValor(substr($_texto,$_de,$_len));
	  			
	  			//echo "<br>-----Coluna: --{$_col_cod}------- {$_col_cod_ori} --- texto {$_string[$_col_cod_ori]}  posicoes:{$_substr} --------------------------------> Sera q deu certo? {$_col->getColValor()}";
	  		}
	  	}	  
	  }	  
	  	  	  
	  public function hasColuna($_coluna) {
	  	$_ret = false;
	  
	  	foreach ($this->_tabela->_colunas as $_col) {
	  		//echo "<br>Vendo coluna {$_col->getColNome()} eh {$_coluna}";
	  		if ( $_col->getColNome() == $_coluna ) {
	  			$_ret = true;
	  			break;
	  		}
	  	}
	  	return $_ret;
	  }

	  // Verificando se a tabela trata Telefone
	  public function verifyEntIdRegistro($_dados) {
	  	 // Zero nao tem a coluna na tabela
	  	 $_ret = 0;
	  
	  	foreach ($this->_tabela->_colunas as $_col) {
	  		//echo "<br>Vendo coluna {$_col->getColNome()} eh tel_id";
	  		if ( $_col->getColNome() == "ent_id" ) {
	  			
	  			if ( array_key_exists('ent_id', $_dados) == true ) {
	  				$_ret = $_dados['ent_id'];
	  			} else {
	  				$_ret = 0;
	  			}
	  			break;
	  		}
	  	}
	  	return $_ret;
	  }
	   
	  
	  // Verificando se a tabela trata Telefone
	  public function hasTelefone() {  	
	  	$_ret = false;
	  	
	  	foreach ($this->_tabela->_colunas as $_col) {  		
	  		//echo "<br>Vendo coluna {$_col->getColNome()} eh tel_id";
	  		if ( $_col->getColNome() == "tel_id" ) {
	  			if ( $_col->getColVisible() == "S" ) {
	  			   $_ret = true;
	  			   break;
	  			}
	  		}
	  	}
	  	return $_ret;
	  }
	  
	  // Verificando se a tabela trata endereco
	  public function hasEndereco() {
	  
	  	$_ret = false;
	  
	  	foreach ($this->_tabela->_colunas as $_col) {
	  		//echo "<br>Vendo coluna {$_col->getColNome()} eh end_id";
	  		if ( $_col->getColNome() == "end_id" ) {
	  			if ( $_col->getColVisible() == "S" ) {
	  			   $_ret = true;
	  			   break;
	  			}
	  		}
	  	}
	  	return $_ret;
	  }

	  // Verificando se a tabela trata eMail
	  public function hasEmail() {
	  	 
	  	$_ret = false;
	  	 
	  	foreach ($this->_tabela->_colunas as $_col) {
	  		//echo "<br>Vendo coluna {$_col->getColNome()} eh end_id";
	  		if ( $_col->getColNome() == "eml_id" ) {
	  			if ( $_col->getColVisible() == "S" ) {
	  			   $_ret = true;
	  			   break;
	  			}
	  		}
	  	}
	  	return $_ret;
	  }
	    
	  public function setMode($aMode) {
	    
	  	if ( $aMode != "SELECT" && $aMode != "INSERT" && $aMode != "UPDATE" ) {
	  		$aMode = "SELECT";
	  	}
	  	
	  	$this->_mode = $aMode;
	  }
	  
	  public function getMode() {
	  	return $this->_mode;
	  }

	  public function setAtributos($arrAtributos) {
	  	$this->_coluna_atributos = $arrAtributos;
	  }
	  
	  public function setTabela($aTabela) {
	    $this->_tabela_nome = $aTabela->_nome; 
		$this->_tabela = $aTabela; 
	  }

	  public function showColunas() {
	  	
	    foreach ($this->_colunas as $col)
           echo "Colunas : {$col->getNome()}\n<br>";
	  }
	  
	  public function insert( Array $dados){
		 	 
		 //foreach ($dados as $inds => $vals){
		 //	 $campos[] = $inds;
		 //	 $valores[] = $vals;			 
		 //} o mesmo que array_keys		
		 //print_r($dados);
	  	// Atribuindo os valores para as colunas para uso no UPDATE
	  	
	  	foreach ($dados as $inds => $vals){
	  		$_col_cod = $inds;
	  		//echo "<br>Coluna: {$inds} e Valor: {$vals}";
	  		$this->_tabela->_colunas[$inds]->setColValor($vals);
	  	}
	  	
	  	// tratando os campos com RANGE de substring
	  	// garantir que o objeto esta com os dados da tela nesse ponto :)
	  	$this->verifyColumnSubstr();

	  	$_tabela = $this->_tabela_nome;
	  	if ( strtoupper($this->_tabela->getTipo()) != "TAB" ) {
	  	 	// Tratando VIEW e pegando apenas as colunas que interessam :)
	  	 	$_tabela = $this->_tabela->getTabCodOriginal();
	  	 	foreach ($dados as $inds => $vals){
 	 			if ( $this->_tabela->_colunas[$inds]->_col_cod_original != "NONE" ) {
 	 				    // Monta um array apenas com as chaves que fazem referencia com a tabela original
 	 				    $_col = $this->_tabela->_colunas[$inds]->_col_cod_original;
	  	 				$_dados[$_col] = $this->_tabela->_colunas[$inds]->getColValor();
	  	 				//echo "<br>Coluna: {$inds} Original:{$_col} e Valor: {$_dados[$_col]}";
	  	 		}	  	 		
	  	 	}
	  	} else {
	  	 	// Trata tudo pela tabela
	  	 	$_dados = $dados;
	  	}
	  	
	  	// Tratando VIEWS derivadas de TCD_ID (tipo_codigo/codigo)
	  	if ( $this->_tabela->getTcdId() != 0 ) {
	  		// Precisa forcar esse valor para essa tabela
	  		$_dados['tcd_id'] = $this->_tabela->getTcdId();
	  	}
	  	 
		$campos = implode(' ,',array_keys($_dados));		 
		$valores = "'" . implode("', '",array_values($_dados)) . "'";
		 
		// Zerando ultimo adi sempre q executa um comando insert
		$this->_last_insert_id = 0;
		 
        $sql = "INSERT INTO `{$_tabela}` ( {$campos} ) VALUES ( {$valores} )";
         
        //echo "<br>Model.Insert: ".$sql;
        
        //Faz esse comando como o ultimo executado
        $this->_sql_ultimo = $sql;
		 
        $qry = $this->db->query($sql);

        //$qry = null;
         
        if ( $qry ) {
		    // buscando o ultimo ID inserido, acho eu q para a sessao q esta aberta para o DB indicado pelo modelo :)
            $this->_last_insert_id = $this->db->lastInsertId();
            
        } else {
        	$this->_last_insert_id = 0;
        }
        
        // Coloca no array e na coluna correspondente;
        $this->setRowID($this->_last_insert_id);
         
        return $qry;
         
	  }

	  public function freeHandSQL( $select, $limit = null, $offset = null ){
	  	 
	  	$limit = ($limit != null ? "LIMIT {$limit}" : "");
	  	$offset = ($offset != null ? "OFFSET {$offset}" : "");
	  	 
	  	$sql = "{$select} {$limit} {$offset}";
	  
	  	//echo "<br>model-freeHandSQL [ {$this->_mode} ]->sql : {$sql}";
	  	//Faz esse comando como o ultimo executado
	  	$this->_sql_ultimo = $sql;
	  
	  	$this->setDBCharSet();
	  
	  	$q = $this->db->query($sql);
	  	 
	  	if ( $q ) {
	  		$q->setFetchMode(PDO::FETCH_ASSOC);
	  			
	  		return $q->fetchAll();
	  	}
	  	 
	  	return null;
	  
	  }
	  
	  public function freeHandInsert($_sql){
	  	 
	  	// Zerando ultimo adi sempre q executa um comando insert
	  	$this->_last_insert_id = 0;
	  	 
	  	//echo "<br>Model.freeHandInsert: ".$_sql;
	  	 
	  	//Faz esse comando como o ultimo executado
	  	$this->_sql_ultimo = $_sql;
	  	 
	  	$qry = $this->db->query($_sql);
	  	 
	  	if ( $qry ) {
	  		// buscando o ultimo ID inserido, acho eu q para a sessao q esta aberta para o DB indicado pelo modelo :)
	  		$this->_last_insert_id = $this->db->lastInsertId();
	  	} else {
	  		$this->_last_insert_id = 0;
	  	}
	  	 
	  	return $qry;
	  }
	  
	  public function freeHandUpdate($_sql){
	  	 
	    //echo "<br>Model.freeHandUpdate: ".$_sql;
	  
	  	//Faz esse comando como o ultimo executado
	  	$this->_sql_ultimo = $_sql;
	  		
	  	$qry = $this->db->query($_sql);
	  	 
	  	return $qry;	  	 
	  }	  
	  
	  public function setDBCharSet() {
	  	$this->db->query("SET NAMES 'utf8'");
	  	$this->db->query("SET character_set_connection=utf8");
	  	$this->db->query("SET character_set_client=utf8");
	  	$this->db->query("SET character_set_results=utf8");
	  }
	  
	  public function read( $where = null, $limit = null, $offset = null, $orderby = null ){

	  	  $tabela = $this->_tabela_nome;
	  	  
		  $where = ($where != null && !empty($where) && $where != " " ? "WHERE {$where}" : "");
		  $limit = ($limit != null ? "LIMIT {$limit}" : "");
		  $offset = ($offset != null ? "OFFSET {$offset}" : "");
		  $orderby = ($orderby != null ? "ORDER BY {$orderby}" : "");		  
		   		 
		  $sql = "SELECT * FROM `{$tabela}` {$where} {$orderby} {$limit} {$offset}";

		  //if ( $tabela == "programa") {
		  //   echo "<br><br>model->read : {$sql}";
		  //   die();
		  //}
		  
		  //Faz esse comando como o ultimo executado
		  $this->_sql_ultimo = $sql;
		  
		  $this->setDBCharSet();
		  
		  $q = $this->db->query($sql);
		  
		  $q->setFetchMode(PDO::FETCH_ASSOC);
		  
		  $q_ret = $this->selectDadosSubstring($q->fetchAll());
		  
		  return $q_ret;
	  }
	  
	  public function selectDadosSubstring($_registros) {
	  	
	  	//print_r($_registros);
		
		//print_r($this->_tabela->_colunas);
	  	
	  	for ( $i=0; $i < count($_registros); $i++) {
	  		$_dados = $_registros[$i];
	  		// Atribuindo os valores para as colunas para uso desmembrado
	  		foreach ($_dados as $inds => $vals){
	  			$_col_cod = $inds;
				//echo "<br>-----selectDadosSubstring--------------->{$inds} : ---> {$vals}";
				// Verificando e coluna existe
				if ( array_key_exists($inds, $this->_tabela->_colunas) ) {
	  			   $this->_tabela->_colunas[$inds]->setColValor($vals);
				}
	  		}
	  		// tratando os campos com RANGE de substring
	  		// garantir que o objeto esta com os dados da tela nesse ponto :)
	  		$this->separaColumnSubstr();
	  		foreach ($_dados as $inds => $vals){
	  			$_col_cod = $inds;
				// verificando se coluna existe
				if ( array_key_exists($inds, $this->_tabela->_colunas) ) {
	  			   $_dados[$inds] = $this->_tabela->_colunas[$inds]->getColValor();
				}
	  		}
	  		$_registros[$i] = $_dados;
	  	}
	  	
	  	return $_registros;
	  }

	  public function select( $tabela, $where = null, $limit = null, $offset = null, $orderby = null ){
	  
	  	$where = ($where != null ? "WHERE {$where}" : "");
	  	$limit = ($limit != null ? "LIMIT {$limit}" : "");
	  	$offset = ($offset != null ? "OFFSET {$offset}" : "");
	  	$orderby = ($orderby != null ? "ORDER BY {$orderby}" : "");
	  
	  	$sql = "SELECT * FROM `{$tabela}` {$where} {$orderby} {$limit} {$offset}";
	  
	  	//echo "<br>model-slect->sql : {$sql}";
	  	
	  	//Faz esse comando como o ultimo executado
	  	$this->_sql_ultimo = $sql;
	  
	  	$this->setDBCharSet();
	  	
	  	$q = $this->db->query($sql);
	  
	  	$q->setFetchMode(PDO::FETCH_ASSOC);
	  	  
	  	//$q_ret = $this->selectDadosSubstring($q->fetchAll());
	  	$q_ret = $q->fetchAll();
	  	
	  	return $q_ret;
	  }

	  public function query( $select, $from, $where = null, $limit = null, $offset = null, $orderby = null ){
	  	 
	  	$where = ($where != null ? "WHERE {$where}" : "");
	  	$limit = ($limit != null ? "LIMIT {$limit}" : "");
	  	$offset = ($offset != null ? "OFFSET {$offset}" : "");
	  	$orderby = ($orderby != null ? "ORDER BY {$orderby}" : "");
	  	 
	  	$sql = "SELECT {$select} FROM {$from} {$where} {$orderby} {$limit} {$offset}";
	  
	  	//echo "<br>model-query [ {$this->_mode} ]->sql : {$sql}";
	  	//Faz esse comando como o ultimo executado
	  	$this->_sql_ultimo = $sql;
	  
	  	$this->setDBCharSet();
	  
	  	$q = $this->db->query($sql);
	  	 
	  	if ( $q ) {
	  		$q->setFetchMode(PDO::FETCH_ASSOC);
	  				  		
	  		//$q_ret = $this->selectDadosSubstring($q->fetchAll());
	  		$q_ret = $q->fetchAll();
	  		
	  		return $q_ret;
	  	}
	  	 
	  	return null;
	  
	  }
	  
	  public function count( $where = null ){
	  
	  	/*
	  	 * Retorna o total de linhas da pesquisa
	  	 */
	  	if ( empty($where) || $where = " " ){ 
	  		$where = null;
	  	}
	  	
	  	$where = ($where != null ? "WHERE {$where}" : "");	  	
	  	
	  	$sql = "SELECT count(*) linhas FROM `{$this->_tabela_nome}` {$where} ";
	  	 	  	
	  	//Faz esse comando como o ultimo executado
	  	$this->_sql_ultimo = $sql;
	  	
	  	$q = $this->db->query($sql);
	  
	  	$q->setFetchMode(PDO::FETCH_ASSOC);
	  	
	  	$res = $q->fetchAll();

	  	$ret = 0;
	  	if ( count($res) > 0)
	  	   $ret = $res[0]['linhas'];

	  	//echo "<br>Count->Linhas: {$ret}";
	  	
	  	return $ret;
	  }
	  
	  public function update( Array $dados, $where){
		
	  	$_columns = array();
	  	
	  	$this->setDBCharSet();
	  	
	  	// Atribuindo os valores para as colunas para uso no UPDATE
	  	foreach ($dados as $inds => $vals){
	  		$_col_cod = $inds;
	  		$this->_tabela->_colunas[$inds]->setColValor($vals);
	  	}
	  	
	  	// tratando os campos com RANGE de substring
	  	// garantir que o objeto esta com os dados da tela nesse ponto :)
	  	$this->verifyColumnSubstr();
	  	
	  	// Dterminando as colunas para o UPDATE
		foreach ($dados as $inds => $vals){
		   
   		   $_col_cod = $inds;
   		   $_col_cod_ori = $inds;
   		   //echo "<br>Antes: Coluna {$_col_cod} para Original {$_col_cod_ori}";
   		   // Pega valor ajustada para casos com concatenacao
   		   $vals = $this->_tabela->_colunas[$inds]->getColValor();
   		   
   		   if ( strtoupper($this->_tabela->getTipo()) != "TAB" ) {
   		   	  // Tratamento aprqa view
			  if ( $this->_tabela->_colunas[$inds]->_col_cod_original != "NONE" ) {
				  $_col_cod = $inds;
				  $_col_cod_ori = $this->_tabela->_colunas[$inds]->_col_cod_original;
				  //echo "<br>&nbsp;&nbsp;Analisar Coluna {$_col_cod} tem original: {$_col_cod_ori} valor esta {$vals}";
			  } else {
			  	// Colunas com codigo original NONE nao sao consideradas
			  	continue;
			  }
		   }
		   //echo "     Depois: Coluna {$_col_cod} para Original {$_col_cod_ori}";
		   
		   // Verificando se essa coluna ja foi colocada no UPDATE
		   // usar a col_cod_ori		   
		   if ( array_key_exists($_col_cod_ori, $_columns) == false ) {
			  $_columns[$_col_cod_ori] = $_col_cod_ori;
		   } else {
		   	  //echo "<br>&nbsp;&nbsp;Coluna {$_col_cod} para Original {$_col_cod_ori} ja considerada";
			  continue;
		   }
			
		   // Nesse momento, a coluna ORIGINAL eh a propria para a TABELA e se existir uma regra a ORIGINAL para a VIEW ou o mesmo que esta na definicao sem conversao.
	       $campos[] = "{$_col_cod_ori} = '{$vals}'";	
		}	  
		
		$campos = implode(", ", $campos);
		
		if ( strtoupper($this->_tabela->getTipo()) == "TAB" ) {
		   $sql = "UPDATE `{$this->_tabela_nome}` SET {$campos} WHERE {$where} ";
		} else {
		   $sql = "UPDATE `{$this->_tabela->getTabCodOriginal()}` SET {$campos} WHERE {$where} ";
		}
		
		//echo "<br>Model [ {$this->_mode } ]->UPD: ".$sql;
		
		//Faz esse comando como o ultimo executado
		$this->_sql_ultimo = $sql;
		
		return  $this->db->query($sql);		  
		
	  }
	  
	  public function where($aCondicao, $aAlias, $aCol, $aOperacao, $aValor, $aConvOriginal = true) {	  	
	  	$_where = "";
	  	
	  	//echo "<br>model->where: condicao: {$aCondicao}, alias: {$aAlias}, Coluna: {$aCol}, Operacao: {$aOperacao}, Valor: {$aValor}";
	  	
	  	if ( ( strtoupper($this->_tabela->getTipo()) == "TAB") or ( $aConvOriginal == false ) ) {
	  	   $_where = $aCondicao." ".$aAlias.$aCol." ".$aOperacao." ".$aValor;
	  	} else {
	  	   if ( $this->_tabela->_colunas[$aCol]->_col_cod_original != "NONE" ) {
	  	      $_where = $aCondicao." ".$aAlias.$this->_tabela->_colunas[$aCol]->_col_cod_original." ".$aOperacao." ".$aValor;
	  	   }
	  	}	  	
	  	//echo "<br>Model->Where: {$_where}";
	  	
	  	return $_where;
	  }
	  	  
	  public function delete($where){
		  	  	  
	  	  $_sql = "";
	  	  
	  	  if ( strtoupper($this->_tabela->getTipo()) == "TAB" ) {
	  	  	 $_sql = "DELETE FROM `{$this->_tabela_nome}` WHERE {$where}";
		     
	  	  } else {	  	  
	  	  	 $_sql = "DELETE FROM `{$this->_tabela->getTabCodOriginal()}` WHERE {$where}";
	  	  }
	  	  
	  	  //echo "Model->Delete: {$_sql}";
	  	  		
	  	  return $this->db->query($_sql);
	  	  
	  }

	  public function update_trigger(Array $dados, $coluna_dtm, $coluna_usrupd, $valor_usrupd, $where){

	  	$this->setDBCharSet();
	  	
	  	foreach ($dados as $inds => $vals){
	  		if ( strtoupper($this->_tabela->getTipo()) == "TAB" ) {
	  		   $campos[] = "{$inds} = '{$vals}'";
	  		} else {
	  		   if ( $this->_tabela->_colunas[$inds]->_col_cod_original != "NONE" ) {
	  		      $campos[] = "{$this->_tabela->_colunas[$inds]->_col_cod_original} = '{$vals}'";
	  		   }
	  		}
	  	}
	  	$campos = implode(", ", $campos);

	  	$sql = "";
	  	if ( strtoupper($this->_tabela->getTipo()) == "TAB" ) {
	  	   $sql = "update `{$this->_tabela_nome}` "
	  	   . " set {$campos}, "
	  	   . "     {$coluna_dtm} = CURRENT_TIMESTAMP, "
	  	   . "     {$coluna_usrupd} = {$valor_usrupd} "
	  	   . " WHERE {$where}";
	  	} else {
	  	   $sql = "update `{$this->_tabela->getTabCodOriginal()}` "
	  	   . " set {$campos}, "
	  	   . "     {$this->_tabela->_colunas[$coluna_dtm]->col_cod_original} = CURRENT_TIMESTAMP, "
	  	   . "     {$this->_tabela->_colunas[$coluna_usrupd]->_col_cod_original} = {$valor_usrupd} "
	  	   . " WHERE {$where}";
	  	}
	  	//echo "<br>UpdateTrigger Model->SQL -> ".$sql;
	  	//Faz esse comando como o ultimo executado
	  	$this->_sql_ultimo = $sql;
	  
	  	return $this->db->query($sql);
	  
	  }
	  
	  public function delete_triger($coluna_id, $coluna_dtm, $coluna_usrupd, $valor_usrupd, $where){
	  
	  	$sql = "";
	  	if ( strtoupper($this->_tabela->getTipo()) == "TAB" ) {
	  	   $sql = "update `{$this->_tabela_nome}` "
	  	        . " set {$coluna_id} = {$coluna_id} * -1 , "
	  	        . "     {$coluna_dtm} = CURRENT_TIMESTAMP, "
   	  	        . "     {$coluna_usrupd} = {$valor_usrupd} "
	  		    . " WHERE {$where}";
	  	} else {
	  	   $sql = "update `{$this->_tabela->getTabCodOriginal()}` "
	  		    . " set {$this->_tabela->_colunas[$coluna_id]->_col_cod_original} = {$this->_tabela->_colunas[$coluna_id]->_col_cod_original} * -1 , "
	  		    . "     {$this->_tabela->_colunas[$coluna_dtm]->_col_cod_original} = CURRENT_TIMESTAMP, "
	  		    . "     {$this->_tabela->_colunas[$coluna_usrupd]->_col_cod_original} = {$valor_usrupd} "
	  		    . " WHERE {$where}";
	  	}

	    //echo "<br>Model->SQL -> ".$sql;
	    
	  	//Faz esse comando como o ultimo executado
	  	$this->_sql_ultimo = $sql;

	  	return $this->db->query($sql);
	  	
	  }	  
	  
	  public function default_paginacao($pagina, $where = null, $orderby = null) {
	  	//echo "indo pelo Default";
	  	$ret = $this->paginacao($pagina, $where, 1, 0, $orderby);
	  	
	  	if ( $ret ) {
	  	   $arr_cols = array_keys($ret[0]); 
	  	   $cols = $this->_tabela->_colunas;
	  	   //print_r($this);
	  	   for ( $i = 0; $i < count($arr_cols) ; $i++ ) {
	  	   	$key = $arr_cols[$i];
	  	   	if ( array_key_exists($key,$cols) ) {
	  	   	  $ret[0][$key] = $cols[$key]->_col_default;
	  	   	  if ( $cols[$key]->_col_valor_fixo != "NONE" ) {
	  	   	  	$_valor =  $cols[$key]->_col_valor_fixo;
  	   	  	    $ret[0][$key] = $_valor;
	  	   	  }
	  	   	}
	  	   	//echo "{$key} -> {$ret[0][$key]}";
	       }
	  	}
	  	
	  	return $ret;
	  	
	  }
	  public function paginacao($pagina, $where = null, $limit = null, $offset = null, $orderby = null) {

	  	//echo "<br><br>Model->Paginacao: {$pagina}, limit: {$limit}, offset: {$offset}";
	  	
	  	if ($pagina == 0 || $pagina == 1 || $pagina < 0) {
	  	   // Iniciar a paginacao com pagina ZERO pois eh nesse momento apenas que faz o count ou na 1a pagina
	  	   $pagina = 1;
	  	   $this->_paginacao["corrente"] = 1;
	  	}

	  	$this->_paginacao["linhas_total"] = $this->count($where);
	  	
	  	$this->_paginacao["linhas_por_pagina"] = $limit;
	  	
	    // Determinana pagina recebida como a corrente a que vai ser carregada	
	  	$this->_paginacao['corrente'] = $pagina;

	  	// Determinando Total de Paginas para a Where
	  	$this->_paginacao['total'] = floor($this->_paginacao['linhas_total'] / $this->_paginacao["linhas_por_pagina"]);

	  	// Determianndo quantas linhas vai ter na ultima pagina
	  	$this->_paginacao['linhas_ultima_pagina'] = $this->_paginacao['linhas_total'] % $this->_paginacao['linhas_por_pagina']; 
	  	
	  	if ( $this->_paginacao['linhas_ultima_pagina'] > 0 ) {
           // Se tiver resto, adiciona uma pagina
	  	   $this->_paginacao['total']++; 
	  	}

        // Posicionando controladores de pagina
        $this->_paginacao['primeira'] = 1;
        $this->_paginacao['proxima'] = $pagina + 1;
        $this->_paginacao['anterior'] = $pagina - 1;
        $this->_paginacao['ultima'] = $this->_paginacao['total'];

        if ( $this->_paginacao['anterior'] <= 0 ) {
        	$this->_paginacao['anterior'] = 1;
        }
        
        if ( $this->_paginacao['proxima'] > $this->_paginacao['total']) {
        	$this->_paginacao['proxima'] = $this->_paginacao['total'];
        }
       
	  	// Apos identificar par qual pagina saltar, calcula o offset
	  	//echo "<br> IF Pagina: {$pagina} ou Limit: {$limit}";
	  	if ($pagina == 1 || $limit == 1) {
	  		//echo "<br>Primeira pagina ????";
	  		// Esta na primeira pagina ou indo para ela ou vai exibir apenas uma linha
	  		$this->_paginacao['offset'] = 0;
	  	} else {
	  		// o deslocamento e a pagina anterior em registros
	  		$this->_paginacao['offset'] = $this->_paginacao['anterior'] * $this->_paginacao['linhas_por_pagina'];
	  	}
	    //echo "<br>----------------------------------> aqui cara: {$this->_paginacao['offset']}";
	    //echo "<br>----------------------------------> Linhas total: {$this->_paginacao['linhas_total']}";

	  	// Esta indo para a ultima pagina, para eibicao de apenas um registro o offset tm q ser ZERO como indicado acima
	  	if ( $pagina > 1 ) {
	       if ($pagina == $this->_paginacao['total'] && $limit != 1) {
	       	   if ( $this->_paginacao['linhas_ultima_pagina'] != 0 ) {
	  		     $this->_paginacao['offset'] = $this->_paginacao['linhas_total'] - $this->_paginacao['linhas_ultima_pagina'];
	       	   }
	  	   }
	  	}
	  
	  	//echo "<br>----------------------------------> ultimapagina: ".$this->_paginacao['linhas_ultima_pagina'];
	  	//echo "<br>----------------------------------> pagina: ".$pagina;
	  	//echo "<br>----------------------------------> offset: ".$this->_paginacao['offset'];
	  	//echo "<br>Paginacao->read";
	  	return $this->read($where, $this->_paginacao['linhas_por_pagina'], $this->_paginacao['offset'], $orderby);

	  }
	  
	  public function bsComboBox( $id, $texto, $nome, $valor, $selecionada, $where = null, $orderby = null ){
	  	// Monta um ComboBox/elect
		  
		$where = ($where != null ? "WHERE {$where}" : "");

		$orderby = ($orderby != null ? "ORDER BY {$orderby}" : "");		  
		  
		$sql = "SELECT * FROM `{$this->_tabela_nome}` {$where} {$orderby} ";

		$q = $this->db->query($sql);
		  
		$q->setFetchMode(PDO::FETCH_ASSOC);

		$id_sel = $id;
		$txt_sel = $texto;
		$_html  = "<DIV CLASS='form-group'>";
		$_html .= "<LABEL FOR='{$id_sel}'>{$txt_sel}</LABEL>";
		$_html .= "<SELECT CLASS='form-control' id='{$id_sel}'>";
		  
		$ret = $q->fetchAll();
		
		for ( $i=0; $i < count($ret) ; $i++ ) {
			$_html .= "<OPTION value='{$ret[$i][$valor]}'>{$ret[$i][$nome]}</OPTION>";
			if ( $ret[$i][$valor] == $selecionada ) {
			   $_html .= " SELECTED";
			}
			$_html .= " {$ret[$i][$nome]}</OPTION>";
		}
	    $_html .= "</SELECT>";
	    $_html .= "</DIV>";
		
		return $_html;
	  }
	
	  public function bsFieldControl($aId, $aTitulo = 'Sem Titulo', $aTipo = 'text', $aValor = 0, $aIcon = null, $aStatic = false, $aReadOnly = false, $aEnabled = true, $aValidador = 'std') {
	  	// Validador : 
	  	//      std - standard - ainda nao digitou fez nada , estado inicial do input
	  	//      ok  - ok - validado  esta ok
	  	//      nok/err - ERRO - nao sta ok tem algum erro
	  	//      wrn - warning - campo obrigatorio
	  	$readonly = "";
	  	if ($aReadOnly == true) {
	  	  $readonly = "readonly";
	  	}
	  	
	  	$html = "";
	  	  		 
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitulo. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='{$aTipo}' {$readonly} value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;	  
	  }
	  
	  public function bsFieldComposed($aId_1, $aId_2, $aTitulo, $aTipo_1 = 'text', $aValor_1, $aValor_2, $aIcon = null, $aStatic = false, $aReadOnly = false, $aEnabled = true, $aValidador = 'std') {
	  	// Validador :
	  	//      std - standard - ainda nao digitou fez nada , estado inicial do input
	  	//      ok  - ok - validado  esta ok
	  	//      nok/err - ERRO - nao sta ok tem algum erro
	  	//      wrn - warning - campo obrigatorio
	  	$html = "";
	  	 
	  	$html  = "<div class='form-group'>";
	  	 
	  	$html .= "<label class='control-label' for='{$aId_1}'>" . $aTitulo. "</label>";
	  	 
	  	$html .= "<input class='form-control' id='{$aId_1}' name='{$aId_1}' type='{$aTipo_1}' readonly value='{$aValor_1}'>";
	  	
	  	$html .= "<input class='form-control' id='{$aId_2}' name='{$aId_2}' type='hidden' value='{$aValor_2}'>";
	  	 
	  	$html .= "</div>";
	  	 
	  	return $html;
	  }	  
	  
	  public function bsSelectColumns($aId, $aDescr, $aSel) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>{$aDescr}</label>";
	  
	  	$html .= "<select class='form-control' id='{$aId}' name='{$aId}'>";
	  	 
	  	$_sel = "";
	  	 
	  	//print_r($this->_colunas);
		
		foreach ($this->_tabela->_colunas as $_col) {
	  		//echo "<br>Vendo coluna {$_col->getColNome()} eh ou nao {$_col->getColCod()}";
			
	  		if ( $_col->getColFiltro() == true ) {
				if ( $_col->getColCod() == $aSel) {
				    $_sel = " selected";
	  			} else {
	  				$_sel = "";
	  			}
				$html .= "<option {$_sel} value='{$_col->getColCod()}'>{$_col->getColDescr()}</option>";
	  		}
	  	}
	  	   
	  	$html .= "</select>";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  }
	  
	  public function bsSelectOperacao($aId, $aDescr, $aSel) {
	  	$html = "";
	  	 
	  	if (empty($aSel) ) {
	  		$aSel = "IGUAL";
	  	}
	  	 
	  	$_operacao[] = new FiltroOperacao("IGUAL","=", "Igual","");
	  	$_operacao[] = new FiltroOperacao("DIFERENTE","!=", "Diferente","");
	  	$_operacao[] = new FiltroOperacao("MAIOR",">", "Maior","");
	  	$_operacao[] = new FiltroOperacao("MENOR","<", "Menor","");
	  	$_operacao[] = new FiltroOperacao("MAIOR_IGUAL",">=", "Maior Igual","");
	  	$_operacao[] = new FiltroOperacao("MENOR_IGUAL","<=", "Menor Igual","");
		
		$_operacao[] = new FiltroOperacao("NA_LISTA_1","IN", "Esta na Lista Text#1","");
		$_operacao[] = new FiltroOperacao("NAO_LISTA_1","NOT IN", "Não esta na Lista Text#1","");

        $_operacao[] = new FiltroOperacao("NA_LISTA_2","IN", "Esta na Lista Text#2","");
		$_operacao[] = new FiltroOperacao("NAO_LISTA_2","NOT IN", "Não esta na Lista Text#2","");		
		
		$_operacao[] = new FiltroOperacao("TEM_INICIO_1","LIKE_LEFT", "Tem Texto#1 no Inicio do texto.","");
		$_operacao[] = new FiltroOperacao("TEM_MEIO_1","LIKE_CENTER", "Tem Texto#1 no Meio do texto.","");
		$_operacao[] = new FiltroOperacao("TEM_INICIO_1","LIKE_RIGHT", "Tem Texto#1 no final do texto.","");
		
		$_operacao[] = new FiltroOperacao("TEM_INICIO_2","LIKE_LEFT", "Tem Texto#2 no Inicio do texto.","");
		$_operacao[] = new FiltroOperacao("TEM_MEIO_2","LIKE_CENTER", "Tem Texto#2 no Meio do texto.","");
		$_operacao[] = new FiltroOperacao("TEM_INICIO_2","LIKE_RIGHT", "Tem Texto#2 no final do texto.","");

		$_operacao[] = new FiltroOperacao("NAO_TEM_INICIO_1","NOT_LIKE_LEFT", "Não tem Texto#1 no Inicio do texto.","");
		$_operacao[] = new FiltroOperacao("NAO_TEM_MEIO_1","NOT_LIKE_CENTER", "Não tem Texto#1 no Meio do texto.","");
		$_operacao[] = new FiltroOperacao("NAO_TEM_INICIO_1","NOT_LIKE_RIGHT", "Não tem Texto#1 no final do texto.","");
		
		$_operacao[] = new FiltroOperacao("NAO_TEM_INICIO_2","NOT_LIKE_LEFT", "Não tem Texto#2 no Inicio do texto.","");
		$_operacao[] = new FiltroOperacao("NAO_TEM_MEIO_2","NOT_LIKE_CENTER", "Não tem Texto#2 no Meio do texto.","");
		$_operacao[] = new FiltroOperacao("NAO_TEM_INICIO_2","NOT_LIKE_RIGHT", "Não tem Texto#2 no final do texto.","");
		
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>{$aDescr}</label>";
	  
	  	$html .= "<select class='form-control' id='{$aId}' name='{$aId}'>";
	  
	  	$_sel = "";
	  		
	  	for ( $i=0; $i <count($_operacao); $i++) {
	  		if ( $_operacao[$i]->_codigo == $aSel) {
	  			$_sel = " selected";
	  		} else {
	  			$_sel = "";
	  		}
	  		$_operacao[$i]->_selecionado = $_sel;
	  		$html .= "<option {$_sel} value='{$_operacao[$i]->_codigo}'>{$_operacao[$i]->_descricao}</option>";
	  	}
	  	$html .= "</select>";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  }
	  
	  public function bsSelectCondicao($aId, $aDescr, $aSel) {
	  	$html = "";
	  
	  	if (empty($aSel) ) {
	  		$aSel = "E";
	  	}
	  
	  	$_condicao[] = new FiltroCondicao("E","\&\&", " E ","");
	  	$_condicao[] = new FiltroCondicao("OU","\|\|", " OU ","");
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>{$aDescr}</label>";
	  
	  	$html .= "<select class='selectpicker form-control' data-style='btn-primary' id='{$aId}' name='{$aId}'>";
	  
	  	$_sel = "";
	  
	  	for ( $i=0; $i <count($_condicao); $i++) {
	  		if ( $_condicao[$i]->_codigo == $aSel) {
	  			$_sel = " selected";
	  		} else {
	  			$_sel = "";
	  		}
	  		$_condicao[$i]->_selecionado = $_sel;
	  		$html .= "<option {$_sel} value='{$_condicao[$i]->_codigo}'>{$_condicao[$i]->_descricao}</option>";
	  	}
	  	$html .= "</select>";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  }

	  public function bsDivButton($aId, $aDescr, $aHint, $aClass, $aIcon) {
	  	$html = "";
	  	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'></label><br>";
	  
	    $html .= "<center><button id='{$aId}' class='{$aClass}' data-toggle='tooltip' data-placement='bottom' title='{$aHint}'>"
			   . "<i class='{$aIcon}'></i>{$aDescr}</button></center>";
			   	  
	  	$html .= "</div>";
	  
	  	return $html;
	  }
	  
	  public function bsEditDBField($aEntId, $aId, $aCol, $aTitle, $aValor) {
		// Variaveis de controle 
	  	$html = null;

	  	// Montar comando com COMBO (select) ou Edit (input). 
	  	// Considerar o EDIT como default 
	  	$_flg_por_tipo = true;
	  
	  	// Identificar situacoes e INPUT ou SELECT e montar automaticament os comandos :)
	  	// 1o. Idntificar a Coluna
	  	$_col_cod = $aCol->_col_nome;
	  	$_col_descr = $aCol->_col_descr;
	  	$_col_tipo = $aCol->_col_tipo->_tipo;
	  	
	  	// 2o. Pegar a configuracao de DB da Coluna
	  	$_tamanho = $aCol->_col_tamanho;
	  	$_inteiro = $aCol->_col_tamanho;
	  	$_decimal = $aCol->_col_tamanho_decimal;
	  	$_formato_date = "DD/MM/YYYY";
	  	$_formato_hora = "HH:MM:SS";
	  	$_formato_datetime = "YYYY-MM-DD HH:MM:SS";

	  	//print_r($aCol['col_tamanho_decimal']);
	  	//echo "<br>Coluna: {$_col_cod}";
	  	//echo "<br>Eh Auto ? : {$this->_tabela->_colunas[$_col_cod]->_auto}";
	  	//echo "<br> COD: {$_col_cod}, DESCR: {$_col_descr} e Tipo: {$_col_tipo}";
	  	//echo "<br> ID: {$aId}, Valor: {$aValor} e Titulo: {$aTitle}";

	  	// Atribuindo valor a Coluna
	  	$this->_tabela->_colunas[$_col_cod]->setColValor($aValor);
	  	
	  	// Pegando Atributos da coluna	 
 		$_atr_id         = $this->_tabela->_colunas[$_col_cod]->_col_cod_id;
 		$_atr_cod        = $this->_tabela->_colunas[$_col_cod]->_col_cod_cod;
  		$_atr_descr      = $this->_tabela->_colunas[$_col_cod]->_col_cod_descr;
  		$_atr_tabela     = $this->_tabela->_colunas[$_col_cod]->_col_tab_cod;
  		$_atr_codigo     = $this->_tabela->_colunas[$_col_cod]->_cod_cod;
  		$_atr_tipocodigo = $this->_tabela->_colunas[$_col_cod]->_tcd_cod;
  		
  		$_valor    = strval($this->_tabela->_colunas[$_col_cod]->_col_valor_fixo);
  		
  		//echo "Coluna {$_col_cod} e valor fixo {$_valor}<br>";
  		//print_r(array_keys($this->_tabela->_colunas));
  		if ( array_key_exists($_valor,$this->_tabela->_colunas) == true ) {
  			//echo "Coluna: {$_col_cod} Valor -> ".$this->_tabela->_colunas[$_valor]->getColValor()."<br>";
  			// Se for uma coluna da propria tabela, busca o valor dela como FIXO
  			//echo "Existe";
  			$atr_valor_fixo = strval($this->_tabela->_colunas[$_valor]->getColValor());
  			$this->_tabela->_colunas[$_col_cod]->setColValor(strval($atr_valor_fixo));
  			$aValor = strval($atr_valor_fixo);
  		} else {
  			$atr_valor_fixo = $_valor;
  		}
  		//echo "finalmente ficou o Fixo: {$atr_valor_fixo}";
  		
  		$atr_statictext    = $this->_tabela->_colunas[$_col_cod]->_col_statictext;
  		$atr_readonly      = $this->_tabela->_colunas[$_col_cod]->_col_readonly;
  		$atr_current       = $this->_tabela->_colunas[$_col_cod]->_col_current;
  		$atr_current_acao  = $this->_tabela->_colunas[$_col_cod]->_col_current_acao;
  		$atr_current_insert= $this->_tabela->_colunas[$_col_cod]->_col_current_insert;
  		$atr_current_update= $this->_tabela->_colunas[$_col_cod]->_col_current_update;
  		$atr_visible       = $this->_tabela->_colunas[$_col_cod]->_col_visible;
  		$atr_fixed_w_text  = $this->_tabela->_colunas[$_col_cod]->_col_fixed_w_text;
  		$atr_fixed_w_input = $this->_tabela->_colunas[$_col_cod]->_col_fixed_s_input;
  		$atr_shiftcase     = $this->_tabela->_colunas[$_col_cod]->_col_shiftcase;
  		$atr_col_ent_id    = $this->_tabela->_colunas[$_col_cod]->_col_ent_id;

  		// Antes de mais nada verifica os atributos novos kkk
  		// Mesmo que carregou um SELECT/COMBO, se for read only forca ser um EDIT com os dados
  		// agora preciso ver isso pois tem que mostrar algo que seja facil de identificar para o usuario e nao um id ou codigo
  		
  		if ( $atr_readonly == "S" ) {
  			$_col_tipo = "READONLY";  	
  		} elseif ( $atr_statictext == "S" ) {
  			$_col_tipo = "STATICTEXT";
  		} elseif ( $atr_valor_fixo != "NONE" ) {
  			//echo "sim {$_col_cod} kkk";
  			//echo "Coluna: {$_col_cod} Fixo: {$atr_valor_fixo} tipo: {$_col_tipo}";
  			$_col_tipo = "READONLY";  
  		}
  		//echo "Coluna: {$_col_cod} Fixo: {$atr_valor_fixo} tipo: {$_col_tipo}";
  		  		
  		if ( $_atr_id != "NONE") {
  			$_flg_por_tipo = false;
  			
  			// Passa como parametro e se for <> 0 forca usar como condicao
  			$_col_ent_id = 0;
  			if ( $atr_col_ent_id == "S" ) {
  				$_col_ent_id = $aEntId;
  			}
  			
  			$html = $this->bsSelectTabela($_col_ent_id, $aId, $aTitle, $_col_tipo, $aValor, $_atr_tabela, $_atr_id, $_atr_cod, $_atr_descr);
  			if ( !$html) {
  				// Nao achou a tabela referenciada ou nao tinha dados na tabela, forca EDIT
  				$_flg_por_tipo = true;
  			}
			//echo "<br> Aqui HTML #1";
  		} elseif ( $_atr_cod != "NONE") {
  		    $_flg_por_tipo = false;
  					
  			// Passa como parametro e se for <> 0 forca usar como condicao
  			$_col_ent_id = 0;
  			if ( $atr_col_ent_id == "S" ) {
  				$_col_ent_id = $aEntId;
  			}
  				
  			$html = $this->bsSelectTabela($_col_ent_id, $aId, $aTitle, $_col_tipo, $aValor, $_atr_tabela, $_atr_id, $_atr_cod, $_atr_descr);
  			if ( !$html) {
  				// Nao achou a tabela referenciada ou nao tinha dados na tabela, forca EDIT
  				$_flg_por_tipo = true;
  			}  
		    //echo "<br> Aqui HTML #2 id: {$_atr_id}, Cod: {$_atr_cod}, Flag: {$_atr_codigo}";			
  		} elseif ( $_atr_codigo == "S" ) {
  			$_flg_por_tipo = false;
  			$html = $this->bsSelectCodigoTipo( $aId, $aTitle, $_col_tipo, $aValor, $_atr_tipocodigo);
  			// Verifica se os dados existem
  			if ( !$html) {
      		   // Nao achou a tabela referenciada ou nao tinha dados na tabela, forca EDIT
  			   $_flg_por_tipo = true;
  			} 
			//echo "<br> Aqui HTML #3";
  		} elseif ( $_atr_tipocodigo != "NON" && $_atr_tipocodigo != "NONE" ) {
  			$_flg_por_tipo = false;
  			$html = $this->bsSelectTipoCodigo( $aId, $aTitle, $_col_tipo, $aValor, $_atr_tipocodigo,"COD");
  			if ( !$html) {
      		   // Nao achou a tabela referenciada ou nao tinha dados na tabela, forca EDIT
  			   $_flg_por_tipo = true;
  			} 
			//echo "<br> Aqui HTML #4";
  		}
  		
  		// 3o. Pegar informacao de Atributos da coluna :)
  		if ( $this->_tabela->_colunas[$_col_cod]->_col_auto == "S" ) {
  			// Quando estivermos falando da coluna de autoident
  			// iremos carregar por tipo de dado, as demais analisar se tem por regra para COMBO ou n�o
  			$_flg_por_tipo = true;
  			// Forca ser READONLY
  			$_col_tipo = "READONLY";
  		}
  		
	    // Como montar o comando COMBO ou tipo de dado
	    if ( $_flg_por_tipo == false ) {
	        // Montar por COMBO, seguir as regras do Atributo (dicionario de dados tabela: _coluna)
	        //-- 4o. Verifica se tem que forcar um Static Text
	    	if ( $this->_tabela->_colunas[$_col_cod]->_col_force_edit_readonly == "S" ) {
	    	   $_col_descricao = $this->_col_descricao;
	    	   $html = $this->bsReadOnlyField($aId, $aTitle, $_col_descricao);	    	   
	    	}
			//echo "<br> Aqui HTML #5 id: {$_atr_id}, Cod: {$_atr_cod}, Flag: {$_atr_codigo}";			
	    } else {
		    //Determinano se deve montar o comando por tipo de DADO  	
			//echo "<br>Tipo a ser definido: {$_col_tipo}";
	    	switch ( $_col_tipo ) {
		  		case "N" :
		  			$html = $this->bsEditNumber($aId, $aTitle, $_inteiro, $_decimal, $aValor);
		  			break;
		  		case "D" :
		  			$html = $this->bsEditDate($aId, $aTitle, $_formato_date, $aValor);
		  			break;
		  		case "T" :
		  			$html = $this->bsEditTime($aId, $aTitle, $_formato_hora, $aValor);
		  			break;
		  		case "DTM" :
		  			$html = $this->bsEditDateTime($aId, $aTitle, $_formato_datetime, $aValor);
		  			break;
		  		case "S" :
		  			$html = $this->bsEditString($aId, $aTitle, $_tamanho, $aValor);
		  			break;
		  		case "I" :
		  			$html = $this->bsEditInteiro($aId, $aTitle, $_tamanho, $aValor);
		  			break;
		  		case "P" :
		  			$html = $this->bsEditPassword($aId, $aTitle, $_tamanho, $aValor);
		  			break;
		  		case "READONLY" :
		  			$html = $this->bsReadOnlyField($aId, $aTitle, $aValor);
		  			break;		  	
		  		case "STATICTEXT" :
		  			$html = $this->bsStaticText($aId, $aTitle, "Texto: {$aId}", null);
		  		default :
		  			// trata como texto
		  			$html = $this->bsEditString($aId, $aTitle, $_tamanho, $aValor);
		  	}	  		  
	    }
			
	  	return $html;
	  
	  }
	  
	  public function bsSelectTabela( $aEntId, $aId, $aTitle, $aColTipo, $aValor, $aAtrTabela, $aAtrId, $aAtrCod, $aAtrDescr  ){
	  	// Monta um ComboBox/elect	
	  	//$aTitle .= "[ {$aValor}/{$aColTipo} ] - Tabela";
	  	$_select   = "";
	  	if ( $aAtrId == "NONE" ) {	  	   
	  	   $_select   = $aAtrCod.",".$aAtrDescr;
	  	} else {
	  		$_select   = $aAtrId.",".$aAtrCod.",".$aAtrDescr;
	  	}
	  	$_from     = $aAtrTabela;
	  	$_orderby  = $aAtrDescr." ASC";
	  	$_where    = null;
	  	$_readonly = "";
	  	  	
	  	// Para INSERT, busca todas as op��es da tabela :)
	  	if ( $aColTipo == "READONLY" || $aColTipo == "STATICTEXT" ) {
	  		$_readonly = "READONLY";
	  	}
	  	
	  	if ( $this->_mode == "INSERT" ) {
	  	  $_where = "";
	  	} else {
	  		if ( $_readonly  == "READONLY" ) {
	  			if ( $aAtrId != "NONE") {
	  			   $_where = $aAtrId." = ".$aValor;
	  			} else {
	  			   $_where = $aAtrCod." = ".$aValor;
	  			}
	  		}
	  	}
	  	
	  	// Para manipular os arrays associativos abaixo faz esas jogada para nao ter q fazer muitos ifs :)
	  	if ( $aAtrId == "NONE" ) {
	  		$aAtrId = $aAtrCod;
	  	}
	  	
	  	// Tem que colocar a Entidade q esta logada como parametro ?
	  	// Para isso o parametro tem que ser diferente de ZERO
	  	if ($aEntId != 0 ) {
	  		$_where .= " AND ent_id = {$aEntId}";
	  	}
	  	
	  	$_qry = $this->query($_select, $_from, $_where, null, null, $_orderby);	  		  	   
	  		  
	  	$id_sel = $aId;
	  	$txt_sel = $aTitle;
	  	$selecionada = $aValor;
	  	
	  	$_html  = "<DIV CLASS='form-group'>";
	  	$_html .= "<LABEL FOR='{$id_sel}'>{$txt_sel}</LABEL>";
	  	$_html .= "<SELECT {$_readonly} CLASS='form-control' id='{$id_sel}'>";
	  	
	  	// Identificando Valores ZERADOS
	  	if ( $selecionada == "0" ) {
	  	   $_html .= "<OPTION SELECTED value='0'>Nenhum</option>";
	  	} else {	  	
	  	   $_html .= "<OPTION value='0'>Nenhum</option>";
	  	}
	  	  	 
	  	for ( $i=0; $i < count($_qry) ; $i++ ) {
	  		
	  		$_html .= "<OPTION value='{$_qry[$i][$aAtrId]}'";
	  		
	  		if ( $_qry[$i][$aAtrId] == $selecionada ) {
	  			$_html .= " SELECTED";
	  			// Determinando o conteudo para o codigo ou id
	  			if ( $aAtrId != 'NONE') {
	  			   $this->_col_key = $aAtrId;	  			
	  			   $this->_col_valor = $_qry[$i][$aAtrId];
	  			} else {
	  				$this->_col_key = $aAtrCod;
	  				$this->_col_valor = $_qry[$i][$aAtrCod];
	  		    }
	  			$this->_col_descricao = $_qry[$i][$aAtrDescr];
	  		}
	  		$_html .= " >{$_qry[$i][$aAtrDescr]}</OPTION>";
	  	}
	  	$_html .= "</SELECT>";
	  	$_html .= "</DIV>";
	  
	  	return $_html;
	  }
	  
	  public function bsEditTabela( $aId, $aTitle, $aColTipo, $aValor, $aAtrTabela, $aAtrId, $aAtrCod, $aAtrDescr  ){
	  	// Monta um Input
	    // Seleciona e monta o Input o valor deve ser informado
	  	$_select   = $aAtrId.",".$aAtrCod.",".$aAtrDescr;
	  	$_from     = $aAtrTabela;
	  	$_orderby  = $aAtrDescr." ASC";
	  	$_where    = null;
	  	$_readonly = "";
	  		
	  	// Para INSERT, busca todas as op��es da tabela :)
	  	if ( $aColTipo == "READONLY" || $aColTipo == "STATICTEXT" ) {
	  		$_readonly = "READONLY";
	  	}
	  
	  	if ( $this->_mode == "INSERT" ) {
	  		$_where = "";
	  	} else {
	  		if ( $_readonly  == "READONLY" ) {
	  			$_where = $aAtrId." = ".$aValor;
	  		}
	  	}
	  
	  	$_qry = $this->query($_select, $_from, $_where, null, null, $_orderby);
	  		
	  	//Se achou busca do banco senao usa o ID q passou
	  	if ( count($_qry) > 0 ) {
  	  	   $aValor  = $_qry[0][$aAtrDescr];
	  	} else {
	  		$aValor = "Valor: ". $aValor;
	  	}

	  	$_html = $this->bsReadOnlyField($aId, $aTitle, $aValor);	  	
	  	 
	  	return $_html;
	  }	  
	  
	  public function bsSelectTipoCodigo( $aId, $aTitle, $aColTipo, $aValor, $aTipoCodigo, $aControle ){
	  	// Monta um ComboBox/elect
	  	//$aTitle .= "[ {$aValor}/{$aColTipo} ] - TipoCodigo - [{$aControle}]";
	  	
	  	$_select   = "cod.cod_id, cod.cod_cod, cod.cod_descr";
	  	$_from     = "codigo cod, tipo_codigo tcd";
	  	$_where    = " tcd.tcd_cod = '{$aTipoCodigo}'".
	  			" AND tcd.tcd_id = cod.tcd_id ";
	  			//" AND cod.cod_cod = '".$_valor."'";
	  	$_orderby  = "cod.cod_descr ASC";
	  	$_readonly = "";

	  	// Complementando informacoes para esse tipo de controle
	  	$id_sel = $aId;
	  	$txt_sel = $aTitle;
	  	$selecionada = $aValor;
	  	$aAtrId = "cod_id";
	  	$aAtrCod = "cod_cod";
	  	$aAtrDescr = "cod_descr";
	  	
	  	// O read only nao pode ser condicionar tem q respeitar como ta no banco
	  	if ( $aColTipo == "READONLY" || $aColTipo == "STATICTEXT" ) {
	  		$_readonly = "READONLY";
	  	}
	  	
	  	// Para INSERT, busca todas as op��es da tabela :)
	  	if ( $this->_mode != "INSERT" ) {
	  		// Busca apenas o solicitado
	  		// Verificando se readonly ou statico, forca ser readonly mas mantem o SELECT
	  		if ( $aColTipo == "READONLY" || $aColTipo == "STATICTEXT" ) {
	  			if ( $aControle == "ID" ) {
	  			   $_where .= "cod.".$aAtrId." = ".$aValor;
	  			} else {
	  			   $_where .= "cod.".$aAtrCod." = '".$aValor."'";
	  			}
	  		}	  			  	
	  	}
	  	
	  	$_qry = $this->query($_select, $_from, $_where, null, null, $_orderby);
	  			  
	  	$_html  = "<DIV CLASS='form-group'>";
	  	$_html .= "<LABEL FOR='{$id_sel}'>{$txt_sel}</LABEL>";
	  	$_html .= "<SELECT {$_readonly} CLASS='form-control' id='{$id_sel}'>";
	  
	  	// Identificando Valores ZERADOS
	  	if ( $selecionada == "0" ) {
	  		$_html .= "<OPTION SELECTED value='0'>Nenhum</option>";
	  	} else {
	  		$_html .= "<OPTION value='0'>Nenhum</option>";
	  	}
	  	
	  	for ( $i=0; $i < count($_qry) ; $i++ ) {
	  	  
	  		if ( $aControle == "ID" ) {
	  		   $_html .= "<OPTION value='{$_qry[$i][$aAtrId]}'";
	  		   if ( $_qry[$i][$aAtrId] == $selecionada ) {
	  		   	   $_html .= " SELECTED";
	  			   $this->_col_key = $aAtrId;
	  			   $this->_col_valor = $_qry[$i][$aAtrId];
	  			   $this->_col_descricao = $_qry[$i][$aAtrDescr];
	  		   }
	  		} else {
	  		   $_html .= "<OPTION value='{$_qry[$i][$aAtrCod]}'";
	  		   if ( $_qry[$i][$aAtrCod] == $selecionada ) {
	  				$_html .= " SELECTED";
	  				$this->_col_key = $aAtrCod;
	  				$this->_col_valor = $_qry[$i][$aAtrCod];
	  				$this->_col_descricao = $_qry[$i][$aAtrDescr];
	  		   }	  			
	  		}
	  		$_html .= " >{$_qry[$i][$aAtrDescr]}</OPTION>";
	  	}
	  	$_html .= "</SELECT>";
	  	$_html .= "</DIV>";
	  	 
	  	return $_html;
	  }	  

	  public function bsSelectCodigoTipo( $aId, $aTitle, $aColTipo, $aValor, $aTipoCodigo){
	  	// Monta um ComboBox/Select
	  	
	  	//$aTitle .= "[ {$aValor}/{$aColTipo} ] - Codigo";
	  	
	  	$_select   = "cod_id, cod_cod, cod_descr";
	  	$_from     = "codigo ";
	  	$_where    = "tcd_id = (SELECT tcd.tcd_id FROM codigo cod, tipo_codigo tcd WHERE tcd.tcd_id = cod.tcd_id AND cod.cod_id = ".$aValor.")";
	  	$_orderby  = "cod_descr ASC";
	  	$_readonly = "";
	  	
	  	// Completando informacoes para esse caso de controle :)
	  	$id_sel = $aId;
	  	$txt_sel = $aTitle;
	  	$selecionada = $aValor;
	  	$aAtrId = "cod_id";
	  	$aAtrCod = "cod_cod";
	  	$aAtrDescr = "cod_descr";
	  	
	  	// O read only nao pode ser condicionar tem q respeitar como ta no banco
	  	if ( $aColTipo == "READONLY" || $aColTipo == "STATICTEXT" ) {
	  		$_readonly = "READONLY";
	  	}
	  	
	  	// Para INSERT, busca todas as op��es da tabela :)
	  	if ( $this->_mode != "INSERT" ) {
	  		// Busca apenas o solicitado
	  		// Verificando se readonly ou statico, forca ser readonly mas mantem o SELECT
	  		if ( $aColTipo == "READONLY" || $aColTipo == "STATICTEXT" ) {
	  			$_where .= " and ".$aAtrId." = ".$aValor."";
	  		}	  		
	  	} else {
	  		// Quando for INSERT vai pelo TipoCodigo
	  		return $this->bsSelectTipoCodigo($aId, $aTitle, $aColTipo, $aValor, $aTipoCodigo,"ID");
	  	}
	  		  	
	  	$_qry = $this->query($_select, $_from, $_where, null, null, $_orderby);
	  	 
	  	if (! $_qry) {
	  	  // Se nao achou, vaipor TipoCodigo
	  	  return $this->bsSelectTipoCodigo($aId, $aTitle, $aColTipo, $aValor, $aTipoCodigo,"ID");
	  	}
	  	
	  	$_html  = "<DIV CLASS='form-group'>";
	  	$_html .= "<LABEL FOR='{$id_sel}'>{$txt_sel}</LABEL>";
	  	$_html .= "<SELECT {$_readonly} CLASS='form-control' id='{$id_sel}'>";
	  	 
	  	// Identificando Valores ZERADOS
	  	if ( $selecionada == "0" ) {
	  		$_html .= "<OPTION SELECTED value='0'>Nenhum</option>";
	  	} else {
	  		$_html .= "<OPTION value='0'>Nenhum</option>";
	  	}
	  	
	  	for ( $i=0; $i < count($_qry) ; $i++ ) {
	  
	  		$_html .= "<OPTION value='{$_qry[$i][$aAtrId]}'";
	  
	  		if ( $_qry[$i][$aAtrId] == $selecionada ) {
	  			$_html .= " SELECTED";
	  			$this->_col_key = $aAtrId;
	  			$this->_col_valor = $_qry[$i][$aAtrId];
	  			$this->_col_descricao = $_qry[$i][$aAtrDescr];
	  		}
	  		$_html .= " >{$_qry[$i][$aAtrDescr]}</OPTION>";
	  	}
	  	$_html .= "</SELECT>";
	  	$_html .= "</DIV>";
	  	 
	  	return $_html;
	  }
	  
	  public function bsEditField($aId, $aTitle, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsReadOnlyField($aId, $aTitle, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label negrito' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' readonly id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsDivTexto($aId, $aTitle, $aValor) {
	  	$html = "";
	  	 
	  	$html  = "<div class='form-group'>";
	  	 
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  	 
	  	$html .= "<div id='{$aId}'>{$aValor}</div>";
	  	 
	  	$html .= "</div>";
	  	 
	  	return $html;
	  	 
	  }
	  
	  public function bsStaticText($aId, $aTitle, $aDescr, $aClass) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label negrito' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<div class='{$aClass}'> <p class='form-control-static' id='{$aId}' name='{$aId}'>{$aDescr}</p></div>";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  }
	  
	  public function bsEditSelect($aId, $aTitle, $aLista, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }

	  public function bsEditHidden($aId, $aTitle, $aValor) {
	  	$html = "";
	  	 
	  	$html  = "<div class='form-group'>";
	  	 
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  	 
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  	 
	  	$html .= "</div>";
	  	 
	  	return $html;
	  	 
	  }
	  
	  public function bsEditHidden2($aId, $aTitle, $aValor) {
	  	$html = "";	  
	  	
	  	//echo "<br>bsEditHidden2: {$aId}, {$aTitle} e {$aValor}";
	  	
	  	$html = "<input class='form-control' id='{$aId}' name='{$aId}' type='hidden' readonly value='{$aValor}' >";	 
	  	return $html;
	  
	  }
	  
	  public function bsEditCNPJ($aId, $aTitle, $aFormat, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsEditCPF($aId, $aTitle, $aFormat, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsEditZipCode($aId, $aTitle, $aFormat, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsEditImage($aId, $aTitle, $aWidth, $aHeight, $aBorder, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsEditPhone($aId, $aTitle, $aFormato, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsEditEMail($aId, $aTitle,$aDominio, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsEditDateTime($aId, $aTitle, $aFormato, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsEditTime($aId, $aTitle, $aFormato, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsEditDate($aId, $aTitle, $aFormato, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsEditMemo($aId, $aTitle, $aLinhas, $aColunas,  $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	    
	  	if ( $aColunas != 0 ) {
	  	   $html .= "<textarea rows='{$aLinhas}' class='form-control' id='{$aId}' name='{$aId}'>{$aValor}</textarea>";
	  	} else {
	  	   $html .= "<textarea rows='{$aLinhas}' cols='{$aColunas}' class='form-control' id='{$aId}' name='{$aId}'>{$aValor}</textarea>";
	  	}
	  	
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }

	  public function bsEditMemoFixed($aId, $aTitle, $aLinhas, $aColunas,  $aValor) {
	  	$html = "";
	  	 
	  	$html  = "<div class='form-group'>";
	  	 
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  	 
	  	if ( $aColunas != 0 ) {	  	
	  	   $html .= "<textarea rows='{$aLinhas}' class='form-control' id='{$aId}' name='{$aId}' style='resize:none'>{$aValor}</textarea>";
	  	} else {
	  	   $html .= "<textarea rows='{$aLinhas}' cols='{$aColunas}' class='form-control' id='{$aId}' name='{$aId}' style='resize:none'>{$aValor}</textarea>";
	  	}
	  	$html .= "</div>";
	  	 
	  	return $html;	  	 
	  }	  
	  
	  public function bsEditMemoReadOnly($aId, $aTitle, $aLinhas, $aColunas,  $aValor) {
	  	$html = "";
	  	 
	  	$html  = "<div class='form-group'>";
	  	 
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  	 
	  	if ( $aColunas != 0 ) {
	  		$html .= "<textarea readonly rows='{$aLinhas}' class='form-control' id='{$aId}' name='{$aId}' style='resize:none'>{$aValor}</textarea>";
	  	} else {
	  		$html .= "<textarea readonly rows='{$aLinhas}' cols='{$aColunas}' class='form-control' id='{$aId}' name='{$aId}' style='resize:none'>{$aValor}</textarea>";
	  	}
	  	$html .= "</div>";
	  	 
	  	return $html;
	  	 
	  }
	   
	  public function bsEditString($aId, $aTitle, $aTamanho, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsEditCaptcha($aId, $aTitle, $aTamanho, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsEditPassword($aId, $aTitle, $aTamanho, $aValor) {
	  	$html = "";
	  
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function bsEditInteiro($aId, $aTitle, $aInteiro, $aValor) {
	  	
	  	$html = "";
	  	 
	  	$aStep = $aInteiro;
	  	
	  	$html  = "<div class='form-group'>";
	  	 
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  	 
	  	$html .= "<input class='form-control' id='{$aId}' text-align='right' name='{$aId}' type='number'  step='{$aStep}' value='{$aValor}' >";
	  	 
	  	$html .= "</div>";
	  	
	  	return $html;
	  
	  }
	  
	  public function bsEditButton($aId, $aTitle, $aPlaceholder, $aBtnId, $aBtnType, $aBtnGlyph, $aBtnText, $aValor) {
	  		  	
	  	$html = "";
	  	 
	  	$html  = "<div class='form-group'>";
	  	
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  	
	  	$html .= "<div class='input-group'>";	  
	  	
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' placeholder='{$aPlaceholder}' value='{$aValor}' >";
	  	
	    $html .= "<span class='input-group-btn'> ";
	    
	    if ( $aBtnType == "" || $aBtnType == null ) {
	       // Tipo nao informado
     	   // Default sera o Primary (azul)
	       $aBtnType = "btn-primary";
	    }
        $html .= "<button id='{$aBtnId}' name='{$aBtnId}' class='btn {$aBtnType}' type='button'>";
        
        if ( $aBtnGlyph == " " || $aBtnGlyph == null ) {
          $aBtnGlyph = "fa-check";
        }
        
        if ( $aBtnText != " " && $aBtnText != null) {
           $aBtnText = "&nbsp;" . $aBtnText;
        } 	
	  	$html .= "&nbsp;<i class='fa {$aBtnGlyph}'></i>{$aBtnText}";
	  			
	  	$html .= "</button>";
	  	
	  	$html .= "</span>";
	  		  	
	    		  	
	  	$html .= "</div>";
	  	
	  	$html .= "</div>";
	  	 
	  	return $html;
	  	 
	  }
	  
	  public function bsEdit2Button($aId, $aTitle, $aPlaceholder, $aBtnId, $aBtnType, $aBtnGlyph, $aBtnText, $aBtn2Id, $aBtn2Type, $aBtn2Glyph, $aBtn2Text, $aValor) {
	  
	  	$html = "";
	  	 
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<div class='input-group'>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' placeholder='{$aPlaceholder}' value='{$aValor}' >";
	  
	  	$html .= "<span class='input-group-btn'> ";
	  	 
	  	if ( $aBtnType == "" || $aBtnType == null ) {
	  		// Tipo nao informado
	  		// Default sera o Primary (azul)
	  		$aBtnType = "btn-primary";
	  	}
	  	$html .= "<button id='{$aBtnId}' name='{$aBtnId}' class='btn {$aBtnType}' type='button'>";
	  
	  	if ( $aBtnGlyph == " " || $aBtnGlyph == null ) {
	  		$aBtnGlyph = "fa-check";
	  	}
	  
	  	if ( $aBtnText != " " && $aBtnText != null) {
	  		$aBtnText = "&nbsp;" . $aBtnText;
	  	}
	  	$html .= "&nbsp;<i class='fa {$aBtnGlyph}'></i>{$aBtnText}";
	  
	  	// Segundo botao
	  	if ( $aBtn2Type == "" || $aBtn2Type == null ) {
	  		// Tipo nao informado
	  		// Default sera o Primary (azul)
	  		$aBtn2Type = "btn-primary";
	  	}
	  	$html .= "<button id='{$aBtn2Id}' name='{$aBtn2Id}' class='btn {$aBtn2Type}' type='button'>";
	  	 
	  	if ( $aBtn2Glyph == " " || $aBtn2Glyph == null ) {
	  		$aBtn2Glyph = "fa-check";
	  	}
	  	 
	  	if ( $aBtn2Text != " " && $aBtn2Text != null) {
	  		$aBtn2Text = "&nbsp;" . $aBtn2Text;
	  	}
	  	$html .= "&nbsp;<i class='fa {$aBtn2Glyph}'></i>{$aBtn2Text}";
	  	
	  	$html .= "</button>";
	  
	  	$html .= "</span>";
	  
	  
	  	$html .= "</div>";
	  
	  	$html .= "</div>";
	  	 
	  	return $html;
	  	 
	  }	  
	  	  
	  public function bsButton($aId, $aTitle, $aType, $aGlyph) {
	  	$html = "";
	  	
	  	if ( $aGlyph == " " || $aGlyph == null ) {
	  		$aGlyph = "fa-check";
	  	}
	  	
	  	$html .= "<button id='{$aId}' name='{$aId}' class='btn {$aType}' type='button'>";
	  	
	  	$html .= "&nbsp;<i class='fa {$aGlyph}'></i>&nbsp;{$aTitle}";
	  	
	  	$html .= "</button>";
	  	 
	  	return $html;
	  }
	  
	  public function bsEditNumber($aId, $aTitle, $aInteiro, $aDecimal, $aValor) {
	  	$html = "";
	  
	  	$aStep= "0." . $this->txtReplicate("0", $aDecimal)."1";
	  	
	  	$html  = "<div class='form-group'>";
	  
	  	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	  
	  	$html .= "<input class='form-control' id='{$aId}' text-align='right' name='{$aId}' type='number'  step='{$aStep}' value='{$aValor}' >";
	  
	  	$html .= "</div>";
	  
	  	return $html;
	  
	  }
	  
	  public function txtReplicate($aTxt, $aVezes) {
	  	$aRet = ""; 
	  	
	  	for ($x=1; $x < $aVezes; $x++) {
	  		$aRet .= $aTxt;
	  		//echo "------[".$x."]--------[".$aVezes."]-------------------->Step = ". $aRet;
	  	}
	  	
	  	return $aRet; 
	  }
	  
	  public function bsComboInformacao($aNome, $aId, $aText, $aMessage) {
	  
	  	$_lbl = "lbl_".$aNome;
	  	$_html = "<div class='form-group' id='{$aNome}'> " .
	  			 "<label id='{$_lbl}' class='form-label' for='{$aId}'>{$aText}:</label>" .
	  			 "<select class='form-control' id='{$aId}'> " .
	  			 "  <option value='0' >{$aMessage}</option> " .
	  			 "</select> " .
	  			 "</div>";
	  
	  	return $_html;
	  }
	   
	  public function bsComboContinente() {
	  	
	  	$_html = '<div class="form-group" id="continente"> ' .
	  	         '<label class="form-label" for="fld_con_id">Continente:</label>' .
	  	         '<select disabled readonly class="form-control" id="fld_con_id"> ' .
	  	         '  <option value="0" >Carregar Continentes</option> ' .
	  	         '</select> ' .
	  	         '</div> ';
	  	
	  	return $_html;
	  }

	  public function bsComboPais() {
	    $_html = '<div class="form-group" id="pais"> ' .
	             '<label class="form-label" for="fld_pai_id">Pais</label>' .
	             '<select disabled readonly id="fld_pai_id" class="form-control">' .
	             '  <option value="0" >Carregar Paises</option>' .
	             '</select>'.
	             '</div>';
	    return $_html;
	  }
	 
	  public function bsComboEstado() {
	    $_html = '<div class="form-group" id="estado"> ' . 
	             '<label class="form-label" for="fld_est_id">Estado</label>' .
	             '<select disabled readonly id="fld_est_id" class="form-control">' .
	             '  <option value="0">Carregar Estados</option>' .
	             '</select>'.
	             '</div>';
	    return $_html;
	  }
	  
	  public function bsComboCidade() {
	    $_html = '<div class="form-group" id="cidade"> ' .
	             '<label class="form-label" for="fld_cid_id">Cidade</label>' .
	             '<select id="fld_cid_id" class="form-control">' .
	             '  <option value="0">Carregar Cidades</option>' .
	             '</select>'.
	             '</div>';
	    return $_html;
	  }
	  
	  public function bsComboBairro() {
	    $_html = '<div class="form-group" id="bairro"> ' .
	             '<label class="form-label" for="fld_bai_id">Bairro</label>' .
	             '<select id="fld_bai_id" class="form-control">' .
	             '  <option value="0">Carregar Bairros</option>' .
	             '</select>'.
	             '</div>';
	    return $_html;
	  }
	  
	  public function bsComboCep() {
	    $_html = '<div class="form-group" id="cep"> ' .
	             '<label class="form-label" for="fld_cep_id">CEP</label>' .
	             '<select id="fld_cep_id" class="form-control">' .
	             '  <option value="0">Carregar CEP(s)</option>' .
	             '</select>'.
	             '</div>' ;
	    return $_html;
	  }
	  
	  function validaInteiroNulo($aVal) {	  	 
	  	 if ( $aVal == null ) {
	  	 	return 0;
	  	 }
	  	 return $aVal;
	  }
	  
	  function validaDecimalNulo($aVal) {
	  	
	  	if ( $aVal == null ) {
	  		return 0.0;
	  	}
	  	
	  	return $aVal;
	  	
	  }
	  
	  function validaDataNulo($aData) {
	  	  if ( $aData == null ) {
	  	  	return "0000-00-00"; 
	  	  }
	  	  return $aData;
	  }
	  
	  function validaHoraNulo($aHora) {
	  	if ( $aHora == null ) {
	  		return "00:00:00";
	  	}
	  	return $aHora;
	  }
	  
	  function BeginWork() {
	  	// Use TRY / Exception to CATCH the errors
	  	
	  	$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  	$this->db->beginTransaction();
	  	
	  }
	  
	  function CommitWork() {
	  	// Tudo ok, pode salvar definitivamente no banco
	  	$this->db->commit();
	  	
	  }
	  
	  function RollbackWork() {
	  	// Alguma coisa nao deu certo, desfaz tudo que ja foi feito
	  	$this->db->rollBack();
	  }
}
?>