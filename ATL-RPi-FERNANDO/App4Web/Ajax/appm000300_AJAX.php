<?php
   class appm000300_AJAX extends Controller {
	   public $_system; 

       public function init($_start) {
          // Um INIT nao herda o anterior da classe da qual foi derivado, cada um executa apenas o seu
          //Identificacao do Tela a ser processada
		  $this->_system = $_start;
		  		   
	   }
	   
       public function index_action() {
       	   global $start;
		   //-- Chegou ate aqui, pode aqbrir a view, ufa

		   //-- Identificacao
		   $MODULO="index";
		   $LINK_MODULO = "{$this->_system->_link}appm000300/index/{$MODULO}/";
		   
		   //-- Identificacao do Tela a ser processada
		   $this->_system->_dados['tel_nome']           = "Ajax appm000300";
		   $this->_system->_dados['tel_titulo']         = "Estação Climática";
		   $this->_system->_dados['tel_id']             = "0";
		   $this->_system->_dados['tel_cod']            = "appm000300";
		   
		   // Include padrao verificacoes basicas
		   //include("Include/portalInclude.php");
		   
		   //-- Acionando o PHP por detras do AJAX/JSON o _JSON poe automatico cuidado :|
		   $this->loadJSON('appm000300',$this->_system->_dados);
	   }	
	   
	   public function ajax() {
	       $this->index_action();
	   }
   }
?>