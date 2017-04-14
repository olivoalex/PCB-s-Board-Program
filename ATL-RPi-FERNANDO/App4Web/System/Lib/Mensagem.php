<?php
   class Mensagem {
   	 public $_nome = null;
   	 public $_descricao = null;
     public $_tipo = "S";  // I-nteiro, N-umber decimal, S-tring, P-assword, D-ata, T-ime, DT-Datetime apenas os basicos
   	 public $_default = ""; // valor da coluna em texto
   	 public $_array = array(); // array associativo da coluna

   	 public $_msg_erro = null;   /// Array com erros e incosnsistencias geradas pelos metodos;
   	 public $_msg_ok   = null;   /// Array com mensagens geradas pelos metodos;
   	 
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

    public function getNome() {
    	return $this->_nome;
    }
}
?>   