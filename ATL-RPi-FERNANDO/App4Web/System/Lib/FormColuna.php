<?php
   class FormColuna {
	   public $_nome             = null;
	   public $_controle         = null;
	   public $_grupo_chave      = null;
	   public $_grupo_informacao = null;
	   public $_descricao        = null;
	   public $_hint             = null;
	   public $_tipo             = null;
	   public $_valor            = null;
	   public $_size             = null;
	   public $_class            = null;
	    
	   public function __construct($aControle, $aGrupoChave, $aGrupo, $aNome, $aDescr = null, $aHint = null, $aTipo = 'text', $aValor = null, $aSize = null, $aClass = null) {
		  // Controle:
		  //   edt
		  //   rdo
		  //   hnt
	   	  $this->_controle         = $aControle;
	   	  $this->_grupo_chave      = $aGrupoChave;
	   	  $this->_grupo_informacao = $aGrupo;
	   	  $this->_nome             = $aNome;
		  $this->_descricao        = $aDescr;
		  $this->_hint             = $aHint;
		  $this->_tipo             = $aTipo;
		  $this->_valor            = $aValor;
		  $this->_size             = $aSize;
		  $this->_class            = $aClass;
	   }
	   
	   public function bsEditText($aId, $aTitle, $aValor) {
	   	$html = "";
	   	 
	   	$html  = "<div class='form-group'>";
	   
	   	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	   
	   	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	   
	   	$html .= "</div>";
	   
	   	return $html;
	   
	   }
	   
	   public function bsEditReadOnly($aId, $aTitle, $aValor) {
	   	$html = "";
	   	 
	   	$html  = "<div class='form-group'>";
	   
	   	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	   
	   	$html .= "<input class='form-control' readonly id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	   
	   	$html .= "</div>";
	   
	   	return $html;
	   
	   }
	   
	   public function bsStaticText($aId, $aTitle, $aDescr, $aClass) {
	   	$html = "";
	   		
	   	$html  = "<div class='form-group'>";
	   
	   	$html .= "<label class='control-label ' for='{$aId}'>" . $aTitle. "</label>";
	   
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
	   
	   	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	   
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
	   
	   	$html  = "<div class='form-group'>";
	   
	   	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	   
	   	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	   
	   	$html .= "</div>";
	   
	   	return $html;
	   
	   }
	   
	   public function bsEditNumber($aId, $aTitle, $aInteiro, $aDecimalo, $aValor) {
	   	$html = "";
	   
	   	$html  = "<div class='form-group'>";
	   
	   	$html .= "<label class='control-label' for='{$aId}'>" . $aTitle. "</label>";
	   
	   	$html .= "<input class='form-control' id='{$aId}' name='{$aId}' type='text' value='{$aValor}' >";
	   
	   	$html .= "</div>";
	   
	   	return $html;
	   
	   }
   }
?>