<?php
   class FiltroCondicao{	   
	   public $_codigo           = null;
	   public $_nome             = null;
	   public $_simbolo          = null;
	   public $_descricao        = null;
	   public $_selecionado      = "";
	   
	    
	   public function __construct($_codigo, $_simbolo, $_descricao, $_selecionado) {
	   	  $this->_codigo           = $_codigo;
	   	  $this->_nome             = $_codigo;
	   	  $this->_simbolo          = $_simbolo;
		  $this->_descricao        = $_descricao;
		  $this->_selecionado      = $_selecionado;
	   }
   }
?>