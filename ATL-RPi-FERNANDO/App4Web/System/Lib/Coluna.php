<?php
class Coluna{
   	 public $_tco_id                = 0;   	 
   	 public $_col_id                = 0;
   	 public $_col_cod               = "none";
   	 public $_col_nome              = "none"; // igual ao col_cod
   	 public $_col_descr             = "None";   	 
   	 public $_col_ordem             = 0;
   	 public $_col_auto              = "N";
   	 public $_col_pk                = "N";
   	 public $_col_key               = "N";
   	 public $_col_filtro            = 0;
	 public $_col_relatorio         = 0;
   	 public $_col_eh_id             = 0;
   	 public $_col_eh_cod            = 0;
   	 public $_col_eh_descr          = 0;
   	 public $_col_cod_original         = "NONE";
   	 public $_col_cod_original_excecao = "NONE";
   	 public $_col_cod_original_substr  = "0..0"; // indica que nao faz nada
   	 public $_col_tipo              = null;  	 
   	 public $_col_tamanho           = 0;
   	 public $_col_tamanho_decinal   = 0;
   	 public $_col_nulo              = "N";
   	 public $_col_grupo_informacao  = "BAS";
   	 public $_col_grupo_formulario  = "000";
   	 public $_col_default           = "0";
   	 public $_col_valor_fixo        = "NONE";
   	 public $_col_statictext        = "N";   	 
   	 public $_col_readonly          = "N";
   	 public $_col_current           = "N";
   	 public $_col_current_acao      = "NN";
   	 public $_col_current_insert    = "N";
   	 public $_col_current_update    = "N";
   	 public $_col_current_syncronism= "N";
   	 public $_col_visible           = "S";
   	 public $_col_fixed_w_text      = 80;
   	 public $_col_fixed_s_input     = 50;
   	 public $_col_shiftcase         = "N";
   	 public $_col_ent_id            = "N";
   	 public $_col_tab_cod           = "NONE";
   	 public $_col_cod_id            = "NONE";
   	 public $_col_cod_cod           = "NONE";
   	 public $_col_cod_descr         = "NONE";
   	 public $_cod_cod               = "N";
   	 public $_tcd_cod               = "NON";
   	 public $_col_valor             = null;
   	 
   	 public $_col_force_edit_readonly = "N";
   	 
   	 public $_array                 = array(); // array associativo dos atributos da coluna
   	 
   	 public function __construct($_id, $_cod, $_descricao) {
   	 	
   	 	$this->_col_id              = $_id;
   	 	$this->_col_cod             = $_cod;
   	 	$this->_col_nome            = $_cod;
   	 	$this->_col_descr           = $_descricao;
  	 	
   	 	$this->_array['col_id']     = $this->_col_id;
   	 	$this->_array['col_cod']    = $this->_col_cod;
   	 	$this->_array['col_nome']   = $this->_col_nome;
   	 	$this->_array['col_descr']  = $this->_col_descr;
		
   	 	return $this;
    }
    
    // Determinando os SETs
    public function setTcoId($_valor) {
    	$this->_tco_id              = $_valor;
    	$this->_array['tco_id']     = $_valor;
    }
    public function setColId($_valor) {
        $this->_col_id                = $_valor;
        $this->_array['col_id']       = $_valor;
    }   
    public function setColCod($_valor) {
        $this->_col_cod               = $_valor;
        $this->_array['col_cod']      = $_valor;
    }
    public function setColNome($_valor) {
        $this->_col_nome              = $_valor;
        $this->_array['col_nome']     = $_valor;
    }   
    public function setColDescr($_valor) {
        $this->_col_descr             = $_valor;
        $this->_array['col_descr']    = $_valor;
    }
    public function setColOrdem($_valor) {
        $this->_col_ordem             = $_valor;
        $this->_array['col_ordem']    = $_valor;
    }
    public function setColAuto($_valor) {        
        $this->_col_auto              = $_valor;
        $this->_array['col_auto']     = $_valor;
    }
    public function setColPk($_valor) {    
        $this->_col_pk                = $_valor;
        $this->_array['col_pk']       = $_valor;
    }
    public function setColKey($_valor) {
    	$this->_col_key                = $_valor;
    	$this->_array['col_key']       = $_valor;
    }    
    public function setColFiltro($_valor) {
    	$this->_col_filtro             = $_valor;
    	$this->_array['col_filtro']    = $_valor;
    }
    public function setColRelatorio($_valor) {
    	$this->_col_relatorio          = $_valor;
    	$this->_array['col_relatorio'] = $_valor;
    }	
    public function setColEhId($_valor) {
        $this->_col_eh_id             = $_valor;
        $this->_array['col_eh_id']    = $_valor;
    }
    public function setColEhCod($_valor) {
        $this->_col_eh_cod            = $_valor;
        $this->_array['col_eh_cod']   = $_valor;
    }
    public function setColEhDescr($_valor) {
        $this->_col_eh_descr          = $_valor;
        $this->_array['col_eh_descr'] = $_valor;
    }
    public function setColCodOriginal($_valor) {
        $this->_col_cod_original           = $_valor;
        $this->_array['col_cod_original']  = $_valor;
    }
    public function setColCodOriginalExcecao($_valor) {
    	$this->_col_cod_original_excecao           = $_valor;
    	$this->_array['col_cod_original_excecao']  = $_valor;
    	if ($_valor != "NONE" ) {
    		$this->_col_cod_original = $_valor;
        }
    } 
    public function setColCodOriginalSubstr($_valor) {
      $this->_col_cod_original_substr           = $_valor;
      $this->_array['col_cod_original_substr']  = $_valor;
    }
    public function setColTipo($_valor) {
        $this->_col_tipo              = new TipoDado($_valor);
        $this->_array['col_tipo']     = new TipoDado($_valor);
    }
    public function setColTamanho($_valor) {
        $this->_col_tamanho           = $_valor;
        $this->_array['col_tamanho']  = $_valor;
    }
    public function setColTamanhoDecinal($_valor) {
        $this->_col_tamanho_decimal       = $_valor;
        $this->_array['col_tamanho_decimal']  = $_valor;
    }
    public function setColNulo($_valor) {
        $this->_col_nulo              = $_valor;
        $this->_array['col_nulo']     = $_valor;
    }
    public function setColGrupoInformacao($_valor) {
        $this->_col_grupo_informacao          = $_valor;
        $this->_array['col_grupo_informacao'] = $_valor;
    }
    public function setColGrupoFormulario($_valor) {
        $this->_col_grupo_formulario          = $_valor;
        $this->_array['col_grupo_formulario'] = $_valor;
    }
    public function setColDefault($_valor) {
        $this->_col_default           = $_valor;
        $this->_array['col_default']  = $_valor;
    }
    public function setColValorFixo($_valor) {
        $this->_col_valor_fixo          = $_valor;
        $this->_array['col_valor_fixo'] = $_valor;
    }
    public function setColStaticText($_valor) {
        $this->_col_statictext          = $_valor;
        $this->_array['col_statictext'] = $_valor;
    }
    public function setColReadOnly($_valor) {
        $this->_col_readonly          = $_valor;
        $this->_array['col_readonly'] = $_valor;
    }
    public function setColCurrent($_valor) {
        $this->_col_current           = $_valor;
        $this->_array['col_current']  = $_valor;
    }
    public function setColCurrentAcao($_valor) {
        $this->_col_current_acao                = $_valor;
        
        // Desmembrando Current_Acao
        $this->_col_current_insert              = substr($_valor,0,1);
        $this->_col_current_update              = substr($_valor,1,1);
        $this->_col_current_syncronism          = substr($_valor,2,1);
        
        //echo "<br>Coluna: {$this->_col_cod} Current: {$_valor} Insert {$this->_col_current_insert} e Update {$this->_col_current_update}\n";
        		
        $this->_array['col_current_acao']       = $_valor;
        $this->_array['col_current_insert']     = $this->_col_current_insert;
        $this->_array['col_current_update']     = $this->_col_current_update;
        $this->_array['col_current_syncronism'] = $this->_col_current_syncronism;
    }
    public function setColVisible($_valor) {    	
    	$this->_col_visible           = $_valor;
    	$this->_array['col_visible']  = $_valor;
    }
    public function setColFixedWText($_valor) {
    	// Width in a tab
    	$this->_col_fixed_w_text           = $_valor;
    	$this->_array['col_fixed_w_text']  = $_valor;
    }
    public function setColFixedSInput($_valor) {
    	// Size of a INPUT
    	$this->_col_fixed_s_input           = $_valor;
    	$this->_array['col_fixed_s_input']  = $_valor;
    }    
    public function setColShiftCase($_valor) {
        $this->_col_shiftcase           = $_valor;
        $this->_array['col_shiftcase']  = $_valor;
    }
    public function setColEntId($_valor) {
    	$this->_col_ent_id           = $_valor;
    	$this->_array['col_ent_id']  = $_valor;
    }    
    public function setColTabCod($_valor) {
        $this->_col_tab_cod           = $_valor;
        $this->_array['col_tab_cod']  = $_valor;
    }
    public function setColCodId($_valor) {
        $this->_col_cod_id            = $_valor;
        $this->_array['col_cod_id']   = $_valor;
    }
    public function setColCodCod($_valor) {
        $this->_col_cod_cod           = $_valor;
        $this->_array['col_cod_cod']  = $_valor;
    }
    public function setColCodDescr($_valor) {
        $this->_col_cod_descr          = $_valor;
        $this->_array['col_cod_descr'] = $_valor;
    }
    public function setCodCod($_valor) {
        $this->_cod_cod            = $_valor;
        $this->_array['cod_cod']   = $_valor;
    }
    public function setTcdCod($_valor) {
        $this->_tcd_cod            = $_valor;
        $this->_array['tcd_cod']   = $_valor;
    }
    public function setColValor($_valor) {
    	$this->_col_valor          = trim($_valor);
    	$this->_array['col_valor'] = trim($_valor);
    }
    
    // Determinando os GETs
    public function getTcoId() {
    	return $this->_tco_id;
    }
    public function getColId() {
    	return $this->_col_id;
    }
    public function getColCod() {
    	return $this->_col_cod;
    }
    public function getColNome() {
    	return $this->_col_nome;
    }
    public function getColDescr() {
    	return $this->_col_descr;
    }
    public function getColOrdem() {
    	return $this->_col_ordem;
    }
    public function getColAuto() {
    	return $this->_col_auto;
    }
    public function getColPk() {
    	return $this->_col_pk;
    }
    public function getColKey() {
    	return $this->_col_key;
    }    
    public function getColFiltro() {
    	return $this->_col_filtro;
    }    
    public function getColRelatorio() {
    	return $this->_col_relatorio;
    }    	
    public function getColEhId() {
    	return $this->_col_eh_id;
    }
    public function getColEhCod() {
    	return $this->_col_eh_cod;
    }
    public function getColEhDescr() {
    	return $this->_col_eh_descr;
    }
    public function getColCodOriginal() {
    	if ( $this->_col_cod_original_excecao != "NONE" ) {
    	  return $this->_col_cod_original_excecao;
    	} else {
    	  return $this->_col_cod_original;
    	}
    }
    public function getColCodOriginalExcecao() {
    	return $this->_col_cod_original_excecao;
    }    
    public function getColCodOriginalSubstr() {
    	return $this->_col_cod_original_substr;
    }
    public function getColTipo() {
    	return $this->_col_tipo;
    }
    public function getColTamanho() {
    	return $this->_col_tamanho;
    }
    public function getColTamanhoDecinal() {
    	return $this->_col_tamanho_decinal;
    }
    public function getColNulo() {
    	return $this->_col_nulo;
    }
    public function getColGrupoInformacao() {
    	return $this->_col_grupo_informacao;
    }
    public function getColGrupoFormulario() {
    	return $this->_col_grupo_formulario;
    }
    public function getColDefault() {
    	if ( $this->_col_valor_fixo != "NONE" ) {
    		return $this->_col_valor_fixo;
    	} else {
    	    return $this->_col_default;
    	}
    }
    public function getColValorFixo() {
    	return $this->_col_valor_fixo;
    }
    public function getColStaticText() {
    	return $this->_col_statictext;
    }
    public function getColReadOnly() {
    	return $this->_col_readonly;
    }
    public function getColCurrent() {
    	return $this->_col_current;
    }
    public function getColCurrentAcao() {
    	return $this->_col_current_acao;
    }
    public function getColCurrentInsert() {
    	return $this->_col_current_insert;
    }
    public function getColCurrentUpdate() {
    	return $this->_col_current_update;
    }
    public function getColCurrentSyncronism() {
    	return $this->_col_current_syncronism;
    }
    public function getColVisible() {
    	return $this->_col_visible;
    }
    public function getColFixedWText() {
    	return $this->_col_fixed_w_text;
    }
    public function getColFixedSInput() {
    	return $this->_col_fixed_s_input;
    }    
    public function getColShiftCase() {
    	return $this->_col_shiftcase;
    }
    public function getColEntId() {
    	return $this->_col_ent_id;
    }    
    public function getColTabCod() {
    	return $this->_col_tab_cod;
    }
    public function getColColId() {
    	return $this->_col_col_id;
    }
    public function getColColCod() {
    	return $this->_col_col_cod;
    }
    public function getColColDescr() {
    	return $this->_col_col_descr;
    }
    public function getCodCod() {
    	return $this->_cod_cod;
    }
    public function getTcdCod() {
    	return $this->_tcd_cod;
    }    
    public function getColValor() {
    	
    	$_ret = $this->_col_valor;
    	if ( $_ret == NULL || empty($_ret) ) {
    		$_ret = $this->_col_default;
    		if ( $_ret == NULL || empty($_ret) ) {
    			$_ret = 0;
    		}
    	}
    	return trim($_ret);
    }
}
?>