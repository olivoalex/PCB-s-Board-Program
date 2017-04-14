<?php
   class GrupoInformacao {	   
   	   public $_id               = null;
   	   public $_codigo           = null;
	   public $_descricao        = null;
	   public $_ordem            = null;
	   public $_selecionado      = false;

	   public function __construct($_id, $_codigo, $_descricao, $_ordem, $_selecionado) {
	   	  $this->_id               = $_id;
	   	  $this->_codigo           = $_codigo;
		  $this->_descricao        = $_descricao;
		  $this->_ordem            = $_ordem;
		  $this->_selecionado      = $_selecionado;
	   }
   }
?>