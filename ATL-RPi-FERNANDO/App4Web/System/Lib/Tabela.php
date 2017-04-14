<?php
class Tabela{
      private $_nome;
      private $_idf;
      private $_descricao;
	  private $_tipo;
	  private $_tab_cod_original;
	  private $_orderby;
	  
	  
	  private $_ativo;
	  private $_valido;
	  private $_id_coluna;
	  private $_ativo_coluna;
	  private $_valido_coluna;
	  
	  private $_ent_id_coluna;
	  private $_org_id_coluna;
	  private $_emp_id_coluna;
	  private $_fil_id_coluna;
	  
	  private $_dtm_reg_coluna;
	  private $_dtm_upd_coluna;
	  private $_dtm_syn_coluna;
	  
	  private $_usr_reg_coluna;
	  private $_usr_upd_coluna;
	  private $_usr_syn_coluna;
	  
	  public $_tcd_id; // Para tipo codigo quando origem for uma view de codigo
	  
	  public $_colunas;
	  public $_colunas_array;
      
     public function __construct($nome, $descricao, $idf, $tipo, $tab_cod_original, $orderby){
     	$this->_nome = $nome;
     	$this->_descricao = $descricao;
     	$this->_idf = $idf;
		$this->_tipo = $tipo;	
		$this->_tab_cod_original = $tab_cod_original;
		$this->_orderby = $orderby;
		
		$this->_id_coluna = strtolower($idf)."_id";
		$this->_ativo = "N";
		$this->_ativo_coluna = strtolower($idf)."_ativo";
		$this->_valido = "N";
		$this->_valido_coluna = strtolower($idf)."_valido";

		$this->_dtm_reg_coluna = strtolower($idf)."_dtmreg";
		$this->_dtm_upd_coluna = strtolower($idf)."_dtmupd";
		$this->_dtm_syn_coluna = strtolower($idf)."_dtmsyn";
		
		$this->_ent_id_coluna  = "ent_id";
		$this->_org_id_coluna  = "org_id";
		$this->_emp_id_coluna  = "emp_id";
		$this->_fil_id_coluna  = "fil_id";
		 
		$this->_usr_reg_coluna = "usr_id_reg";
		$this->_usr_upd_coluna = "usr_id_upd";
		$this->_usr_syn_coluna = "usr_id_syn";
		
		$this->_tcd_id = 0;
		
		//echo "Tabela->Constructor : {$this->_id_coluna}<br>";
     }
     
     public function setNome($valor) {
     	$this->_nome = $valor;
     }

     public function setIdf($valor) {
     	$this->_idf = $valor;
     	$this->_id_coluna = strtolower($valor)."_id";
     	$this->_ativo_coluna = strtolower($valor)."_ativo";
     	$this->_valido_coluna = strtolower($valor)."_valido";
     }

     public function setDescricao($valor) {
     	$this->_descricao = $valor;
     }

	 public function setTipo($valor) {
     	$this->_tipo = $valor;
     }

     public function setTabCodOriginal($valor) {
     	$this->_tab_cod_original = $valor;
     }    
     
     public function setTcdId($valor) {
     	$this->_tcd_id = $valor;
     }
     
	 public function setOrderBy($valor) {
     	$this->_orderby = $valor;
     }	 

     public function setColunas($arrColunas) {
     	 
     	//print_r($arrColunas);
     	 
     	$this->_colunas_array = $arrColunas;
     	 
     	for ( $i=0; $i < count($arrColunas); $i++ ){
     		$ky = $arrColunas[$i]->_col_nome;
     		//echo "Tabela-SetColunas : {$ky}<br>";
     		$this->_colunas[$ky] = $arrColunas[$i];
     	}
     }
     
	 public function setIdColuna($valor) {
	 	$this->_id_coluna = strtolower($valor);
	 	//echo "<br>Tabela->Id: {$valor}";
	 }

	 public function setAtivoColuna($valor) {
	 	$this->_ativo_coluna = $valor;
	 }
	 
	 public function setValidoColuna($valor) {
	 	$this->_valido_coluna = $valor;
	 }	 

	 public function setEntIdColuna($valor) {
	 	$this->_ent_id_coluna = $valor;
	 }
	 public function setOrgIdColuna($valor) {
	 	$this->_org_id_coluna = $valor;
	 }
	 public function setEmpIdColuna($valor) {
	 	$this->_emp_id_coluna = $valor;
	 }
	 public function setFilIdColuna($valor) {
	 	$this->_fil_id_coluna = $valor;
	 }
	 public function setAtivo($valor) {
	 	// Inica se tabela em esse controle
	 	$this->_ativo = $valor;
	 }
	 
	 public function setValido($valor) {
	 	// Inica se tabela em esse controle	 	
	 	$this->_valido = $valor;
	 }	 
	 
	 public function setDtmRegColuna($valor) {
	 	$this->_dtm_reg_coluna = $valor;
	 }
	 
	 public function setDtmUpdColuna($valor) {
	 	$this->_dtm_upd_coluna = $valor;
	 }
	 
	 public function setDtmSynColuna($valor) {
	 	$this->_dtm_syn_coluna = $valor;
	 }
	 
	 public function setUsrRegColuna($valor) {
	 	$this->_usr_reg_coluna = $valor;
	 }
	 public function setUsrUpdColuna($valor) {
	 	$this->_usr_upd_coluna = $valor;
	 }
	 public function setUsrSynColuna($valor) {
	 	$this->_usr_syn_coluna = $valor;
	 }
	 
     public function getNome() {
     	return $this->_nome;
     }

     public function getIdf() {
     	return $this->_idf;
     }

     public function getDescricao() {
     	return $this->_descricao;
     }

	 public function getTipo() {
     	return $this->_tipo;
     }	 

     public function getTabCodOriginal() {
     	return $this->_tab_cod_original;
     }

     public function getTcdId() {
     	return $this->_tcd_id;
     }
     
	 public function getOrderBy() {
     	return $this->_orderby;
     }	 
	 	 
	 public function getColunas() {
		 return $this->_colunas;
	 }	
	 
	 public function getColuna($aColuna) {
		 return $this->_colunas[$aColuna];
	 }
	 public function getColunaArray($aPos) {
	 	return $this->_colunas_array[$aPos];
	 }	 

	 public function getAtivo() {
	 	return $this->_ativo;
	 }

	 public function getValido() {
	 	return $this->_valido;
	 }
	 
	 public function getEntIdColuna() {
	 	return $this->_ent_id_coluna;
	 }

	 public function getOrgIdColuna() {
	 	return $this->_org_id_coluna;
	 }
	 
	 public function getEmpIdCOluna() {
	 	return $this->_emp_id_coluna;
	 }
	 
	 public function getFilIdColuna() {
	 	return $this->_fil_id_coluna;
	 }
	  
	 public function getIdColuna() {
	 	return $this->_id_coluna;
	 }
	 
	 public function getAtivoColuna() {
	 	return $this->_ativo_coluna;
	 }	 

	 public function getValidoColuna() {
	 	return $this->_valido_coluna;
	 }
	 
	 public function getDtmRegColuna() {
	 	return $this->_dtm_reg_coluna;
	 }
	 
	 public function getDtmUpdColuna() {
	 	return $this->_dtm_upd_coluna;
	 }
	 
	 public function getDtmSynColuna() {
	 	return $this->_dtm_syn_coluna;
	 }
	 
	 public function getUsrRegColuna() {
	 	return $this->_usr_reg_coluna;
	 }
	 
	 public function getUsrUpdColuna() {
	 	return $this->_usr_upd_coluna;
	 }
	 
	 public function getUsrSynColuna() {
	 	return $this->_usr_syn_coluna;
	 }	 
}
?>