<?php
class  TipoDado{
   	 public $_nome = null;
   	 public $_descricao = null;
     public $_tipo = "S";  // I-nteiro, N-umber decimal, S-tring, P-assword, D-ata, T-ime, DT-Datetime apenas os basicos
   	 public $_default = ""; // valor da coluna em texto


   	 public $_formato = ""; 
   	 public $_validador = ""; 
   	 public $_tipo_html = ""; 

   	 public $_array = array(); // array associativo da coluna
   	 
   	 public function __construct($_tipo) {
   	 	
		$this->setTipo($_tipo);

	 }

     public function setTipo($_tipo)	 {

   	    $this->_tipo = $_tipo;
		
   	 	switch ($this->_tipo) {
   	 		case "N" :
   	 			$this->_nome      = "Decimal";
				$this->_descricao = "Decimal";
   	 			$this->_validador = "decimal";
				$this->_default = "0";
   	 			$this->_formato = "0.0";
   	 			$this->_tipo_html = 'text';
   	 			break;
   	 		case "D" :
   	 			$this->_nome      = "Date";
				$this->_descricao = "Date";
   	 			$this->_validador = "date";
				$this->_default = "31/12/1899";
   	 			$this->_formato = "dd/mm/yyyy";
   	 			$this->_tipo_html = 'text';
   	 			break;
   	 		case "T" :
   	 			$this->_nome      = "Time";
				$this->_descricao = "Time";
   	 			$this->_validador = "time";
   	 			$this->_formato = "hh:mm:ss";
				$this->_default = "00:00:00";
   	 			$this->_tipo_html = 'text';
   	 			break;
   	 		case "DTM" :
   	 			$this->_nome      = "DateTime";
				$this->_descricao = "DateTime";
   	 			$this->_validador = "datetime";
   	 			$this->_formato   = "yyyy-mm-dd hh:mm:ss";
				$this->_default   = "1899-12-31 59:59:59";
   	 			$this->_tipo_html = 'text';
   	 			break;
   	 		case "S" :
   	 			$this->_nome      = "Text";
				$this->_descricao = "Text";
   	 			$this->_validador = "texto";
   	 			$this->_formato   = "texto";
				$this->_default   = " ";
   	 			$this->_tipo_html = 'text';
   	 			break;
   	 		case "M" :
   	 			$this->_nome      = "Memo";
   	 			$this->_descricao = "Memo";
   	 			$this->_validador = "texto";
   	 			$this->_formato   = "texto";
   	 			$this->_default   = " ";
   	 			$this->_tipo_html = 'textarea';
   	 			break;   	 			
   	 		case "I" :
   	 			$this->_nome      = "Integer";
				$this->_descricao = "Integer";
   	 			$this->_validador = "inteiro";
   	 			$this->_formato   = "0";
				$this->_default   = "0";
   	 			$this->_tipo_html = 'text';
   	 			break;
   	 		case "P" :
   	 			$this->_nome      = "Password";
				$this->_descricao = "Password";
   	 			$this->_validador = "password";
   	 			$this->_formato   = "*";
				$this->_default   = "123456";
   	 			$this->_tipo_html = 'password';
   	 			break;
   	 		default :
   	 			// trata como texto
   	 			$this->_nome      = "Undefined/Text";
				$this->_descricao = "Undefined/Text";
   	 			$this->_validador = "texto";
   	 			$this->_formato   = "texto";
				$this->_default   = " ";
   	 			$this->_tipo_html = 'text';
   	 	}
   	 	 
   	 	return $this;
    }

    public function setNome($_valor) {
    	$this->_tipo = $_valor;
    }
    public function setDescricao ($_valor) {
    	$this->_descricao = $_valor;
    }
    public function setDefault ($_valor) {
    	$this->_default = $_valor;
    }
    public function setFormato ($_valor) {
    	$this->_formato = $_valor;
    }
    public function setValidador ($_valor) {
    	$this->_validador = $_valor;
    }
    public function setTipoHtml ($_valor) {
    	$this->_tipo_html = $_valor;
    }
    
    public function getNome() {
    	return $this->_nome;
    }
    public function getTipo() {
    	return $this->_tipo;
    }
    public function getDescricao() {
    	return $this->_descricao; 
    }
    public function getDefault() {
    	return $this->_default; 
    }
    public function getFormato() {
    	return $this->_formato; 
    }
    public function getValidador() {
    	return $this->_validador; 
    }
    public function getTipoHtml() {
    	return $this->_tipo_html; 
    }

    public function retWhereValor($_valor) {
    	// Exclusivamente nesse caso o GET recebe um valor para a coluna que esta sendo tratada na Query
    	// Prepara para ser considerado como valor para SQL em query trata o seu tipo  coloca o q precisa
    	// para poder ser usado na condicao
    	$_ret_valor = $_valor;
    	switch ($this->_tipo) {
    		case "N" :
    			$_ret_valor = $_valor;
    			break;
    		case "D" :
    			$_ret_valor = "'".$_valor."'";
    			break;
    		case "T" :
    			$_ret_valor = "'".$_valor."'";
    			break;
    		case "DTM" :
    			$_ret_valor = "'".$_valor."'";
    			break;
    		case "S" :
    			$_ret_valor = "'".$_valor."'";
    			break;
    		case "I" :
    			$_ret_valor = $_valor;
    			break;
    		case "P" :
    			$_ret_valor = "'".$_valor."'";
    			break;
    		default :
    			// trata como texto
    			$_ret_valor = "'".$_valor."'";
    	}
    	
    	return $_ret_valor;
    }
    
    public function bsField() {
    	$html = "";
    
    	$_id = $this->_id_edit;
    
    	$_tipo = $this->_tipo_html;
    
    	$_valor = $this->_valor;
    
    	$html  = "<div class='form-group'>";
    
    	$html .= "<label class='control-label' for='{$this->_id_edit}'>" . $this->_descricao. "</label>";
    
    	$html .= "<input class='form-control' id='{$_id}' name='{$_id}' type='{$_tipo}' value='{$_valor}' >";
    
    	$html .= "</div>";
    
    	return $html;
    	 
    }

    public function bsFieldReadOnly() {
    	$html = "";
    
    	$_id = 'rdo_'.$this->_nome;
    
    	$_tipo = $this->_tipo_html;
    
    	$_valor = $this->_valor;
    
    	$html  = "<div class='form-group'>";
    
    	$html .= "<label class='control-label' for='{$this->_id_edit}'>" . $this->_descricao. "</label>";
    
    	$html .= "<input class='form-control' id='{$_id}' name='{$_id}' type='{$_tipo}' value='{$_valor}' >";
    
    	$html .= "</div>";
    
    	return $html;
    	 
    }

    public function bsStaticText($aCols) {
    	$html = "";
    
    	$_id = $this->_id_edit;
    
    	$_tipo = $this->_tipo_html;
    
    	$_valor = $this->_valor;
    
    	$html  = "<div class='form-group {$aCols}'>";
    
    	$html .= "<label class='control-label {$aCols}' for='{$this->_id_edit}'>" . $this->_descricao. "</label>";
    
    	$html .= "<div class='{$aCols}> <p class='form-control-static' id='{$_id}' name='{$_id}'>{$_valor}</p></div>";
    
    	$html .= "</div>";
    
    	return $html;
    }
}
?>