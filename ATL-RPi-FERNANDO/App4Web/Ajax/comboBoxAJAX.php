<?php
   class comboBoxAJAX extends Controller {
	   public $_system; 

       public function init($_start) {
          // Um INIT nao herda o anterior da classe da qual foi derivado, cada um executa apenas o seu
          //Identificacao do Tela a ser processada
		  $this->_system = $_start;
		  		   
	   }
	   
       public function index_action() {
		   //-- Chegou ate aqui, pode aqbrir a view, ufa
		   //-- Identificacao
		   $MODULO="index";
		   $LINK_MODULO = "{$this->_system->_link}perfil/index/{$MODULO}/";
		   
		   //-- Identificacao do Tela a ser processada
		   $this->_system->_dados['tel_nome']           = "Portal ERP4web - Ajax Profile";
		   $this->_system->_dados['tel_titulo']         = "Ajax Profile";
		   $this->_system->_dados['tel_id']             = "0";
		   $this->_system->_dados['tel_cod']            = "XXXXX";
		   
		   // Include padrao verificacoes basicas
		   include("Include/portalInclude.php");
		   
		   //echo "AJAX help.....";
		   
		   //-- Acionando o PHP por detras do AJAX/JSON
		   $this->loadJSON('comboBox',$this->_system->_dados);
	   }	
	   
	   public function ajax() {
	       $this->index_action();
	   }
   }
?>