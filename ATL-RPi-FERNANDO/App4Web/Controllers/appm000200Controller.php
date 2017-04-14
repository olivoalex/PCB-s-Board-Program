<?php

   class APPm000200 extends Controller {      	   
   	 
     public $_system; 

     public function init($_start) {
		   
	     // Um INIT nao herda o anterior da classe da qual foi derivado, cada um executa apenas o seu
          //Identificacao do Tela a ser processada
		  $this->_system = $_start;
		      		  	 
	   }	  
	   
   	 public function index_action() {

   	 	global $start ;
   	 	 	
   	 	//-- Identificacao
   	 	$MODULO= $this->_system->_action;   	 	 
   	 	$LINK_MODULO = "{$this->_system->_link}appm000200/index/{$MODULO}/";
		
   	 	$this->_system->_dados['modulo'] = $MODULO;
   	 	$this->_system->_dados['link_modulo'] = $LINK_MODULO;
		
   	 	//-- Identificacao do Tela a ser processada
   	 	$this->_system->_dados['tel_nome']           = "Acionadores";
   	 	$this->_system->_dados['tel_titulo']         = "Acionadores";
   	 	$this->_system->_dados['tel_id']             = "0";
   	 	$this->_system->_dados['tel_cod']            = "appm000200";
		
		include("Include/portalInclude.php");
		
	    // Colocando dados da URL
		$this->_system->_dados['server']     = $this->_system->_server;
		$this->_system->_dados['dominio']    = $this->_system->_dominio;
		$this->_system->_dados['controller'] = $this->_system->_controller;
		$this->_system->_dados['task']       = $this->_system->_task;
		$this->_system->_dados['action']     = $this->_system->_action;
		
	    // Transformando parametros em DADOS
		$this->_system->paramToDados();		
		  	 	
		$this->_system->_dados['link'] = "http://11.12.13.30/App4Web/Portal/";
		
        $this->_system->_dados['page_content'] = "APPM000200 - Acionadores";
		
		$this->_system->_dados['link_dominio'] = $_SESSION["PORTAL_DOMINIO_LINK"];
			
   	 	//AFV-REVER include("Include/portalInclude.php");   	 	
   	 	//-- Passando a Classe Mopdel para a View
   	 	// esse _db esta no profile :)
	    // Acertar isso aqui esta ERRADO
	    $this->_system->_dados['emp_id']  = 12;		
		$this->_system->_dados['fil_id']  = 22;
		$this->_system->_dados['login_id'] = 99;			
		
		// Criando uma Referencia com o Banco de Dados, acho que deveria estar no SYSTEM :)
		$_db = new Model();
		
   	 	$this->_system->_dados['database']  = $_db;
		
		// carregar dados do programa
		$_programa_versao_fisica = "V1.0r0";
		$_programa_cod           = "APPM000200";
        require_once("Include/Libs/programaCarrega.php");				
		$_programa_dados = programaCarrega($_programa_cod, $_programa_versao_fisica );
		$this->_system->loadToDados($_programa_dados);

   	 	// Carrega Visao
		$_view = ( $_programa_dados["prg_view"] != "NONE" ? $_programa_dados["prg_view"] : "appm000200.phtml" );
		
   	 	$this->page($_view,$this->_system->_dados);
   	 	
   	 }
   }
?>