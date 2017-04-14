<?php
   class Index extends Controller {
	   public $_system; 

       public function init($_start) {
          // Um INIT nao herda o anterior da classe da qual foi derivado, cada um executa apenas o seu
          //Identificacao do Tela a ser processada
		  $this->_system = $_start;
		  		   
	   }	 	  
	  
       public function index_action() {
		   //-- Chegou ate aqui, pode aqbrir a view, ufa

		   //echo "<br>----------> Index->index_action";

		   //-- Identificacao
		   $MODULO="index";		   
		   $LINK_MODULO = "{$this->_system->_link}index/index/{$MODULO}/";
		   
		   //echo "<br>Aqui no Controler esta como {$this->_dominio}";
		   //-- Identificacao do Tela a ser processada
		   $this->_system->_dados['tel_nome']           = "Portal ERP4web - Pagina Principal";
		   $this->_system->_dados['tel_titulo']         = "Pagina Principal";
		   $this->_system->_dados['tel_id']             = "0";
		   $this->_system->_dados['tel_cod']            = "XXXXX";
		   
		   //print_r($this->_system->_params);
		   
		   // Atualizando variaveis e controles basicos
		   //include("Include/portalInclude.php");
		   
		   //-- Avionando a View
		   //if ( $this->_auth->userData("ent_login") != "prvadm" ) {
			 
		   // Verificando se tem uma pagina a ser aberta, normalmente um HASH
		   $_pagina = $this->_system->getParam("pages");
		   
		   //echo "<br>----------------->Controller: {$this->_system->_controller}";
		   //echo "<br>----------------->Task: {$this->_system->_task}";
		   //echo "<br>----------------->Action: {$this->_system->_action}";
		   		   
		   // Definindo a VIEW a ser aberta :)
		   $_view = "";
		   if ( strtolower($this->_system->_task ) == "index" || strtolower($this->_system->_task ) == "index_action" ) { 
		      $_view = "portal";
		   } else {
			  $_view = $this->_system->_task;
		   }
		   
		   //echo "<br>----------------->Pagina: {$_pagina}";
		   //echo "<br>----------------->View: {$_view}";
		   
		   if ( $_pagina ) {
             $_view = $_pagina;
			 //echo "<br>----------------->Abrindo Pagina : {$_view}";
			 $this->page($_view,$this->_system->_dados);
		   } else {
			 //echo "<br>----------------->Abrindo View : {$_view}";
			 $this->view($_view,$this->_system->_dados);   
		   }		   
		   
	   }
	   
	   public function menu() {

		  // Caso nao achou o programa indicado pelo menu
		  //echo "<br>---ANTES---INDEX/MENU--> Dados:";
		  //echo "<br>------------------> Server: " . $this->_system->_server;
		  //echo "<br>------------------> Ambiente: " . $this->_system->_ambiente;
		  //echo "<br>------------------> Dominio: " . $this->_system->_dominio;
		  //echo "<br>------------------> Controller: " . $this->_system->_controller;
		  //echo "<br>------------------> Task: " . $this->_system->_task;
		  //echo "<br>------------------> Action: " . $this->_system->_action;
		  //echo "<br>------------------> Parametros:<br>";
          //echo "<br>------------------> Menu: " . $this->_system->_params["mnu"];
		  //echo "<br>------------------> Opcao: " . $this->_system->_params["opc"];
		  //echo "<br>------------------> Programa: " . $this->_system->_params["prg"];
		  
		  //echo "<br>Index->Menu>Parametros: <br>";
		  //print_r($this->_system->_params);
		  
		  // Tenho que empilhar como PARAMETROS pois eh perdido da forma como esta pois ja foi desempilhado da URL
		  $this->_system->_redirector->loadToUrlParameter($this->_system->_params);
		  
		  if (array_key_exists('prg',$this->_system->_params) ) {
			 
			  //echo "<br>---- Aqui 001 -- :) ---";
			  
			  $_prg_id = $this->_system->_params["prg"];
			  // Verificar se programa existe
			  $_prg = new programaModel();
			  $_prg->init();
			  
			  //print_r($_prg->_colunas);
			  
			  //echo "<br>---- Aqui 002 -- :) ---";
			  
			  $_where = " prg_id = ".$this->_system->_params["prg"];
			  
			  //echo "<br>---- Aqui 003 -- :) ---";
			  
			  $_qry = $_prg->read($_where,1,0);
			  
			  //echo "<br>---- Aqui 004 -- :) ---";
			  
			  //print_r($_qry);
			  
			  //echo "<br>------------- Ultimo SQl: " . $_prg->_sql_ultimo;			 
			  
			  if ( $_qry ) {
				// Achou o programa, redireciona
				$_prg_controller = $_qry[0]["prg_controller"];
				$_prg_task       = $_qry[0]["prg_task"];
				$_prg_action     = $_qry[0]["prg_action"];
				
				//echo "<br>-----Achou -------> Programa : {$this->_system->_params['prg']}";
				//echo "<br>------------------> Controller: " . $this->_system->_controller;
		        //echo "<br>------------------> Task: " . $this->_system->_task;
		        //echo "<br>------------------> Action: " . $this->_system->_action;
				
				//echo "<br>Programa:<br>";
				//print_r($_qry[0]);
				
				//echo "<br>URL Parameters: <br>";
				//print_r($this->_system->_redirector->getUrlParameters());
				//echo "<br>-----";
				
				$this->_system->_redirector->goToControllerTaskAction($_prg_controller, $_prg_task, $_prg_action);
				return;
			  }
		  }
		  
		  // Caso nao achou o programa indicado pelo menu
		  //echo "<br>Dados a INDEX Executar: <br>";
		  //echo "<br>Server: " . $this->_system->_server;
		  //echo "<br>Ambiente: " . $this->_system->_ambiente;
		  //echo "<br>Dominio: " . $this->_system->_dominio;
		  //echo "<br>Controller: " . $this->_system->_controller;
		  //echo "<br>Task: " . $this->_system->_task;
		  //echo "<br>Action: " . $this->_system->_action;
		  //echo "<br>Parametros:<br>";
          //echo "<br>Menu: " . $this->_system->_params["mnu"];
		  //echo "<br>Opcao: " . $this->_system->_params["opc"];
		  //echo "<br>Programa: " . $this->_system->_params["prg"];
		  
		  //if (array_key_exists('EMAILID',$this->_system->_params) ) {
			//  echo "<br>EMAIL ID: " . $this->_system->_params["EMAILID"];
		  //}	
		  //if (array_key_exists('NTFID',$this->_system->_params) ) {
			  //echo "<br>NOTIFICATION ID: " . $this->_system->_params["NTFID"];
		  //}	
		  //if (array_key_exists('TSKID',$this->_system->_params) ) {
			  //echo "<br>TASK ID: " . $this->_system->_params["TSKID"];
		  //}		  
	   }
	   
	   public function lock() {
		  $this->index_action();
	   }
	   
	   public function open() {
	   	//-- Chegou ate aqui, pode aqbrir a view, ufa
	   
	    echo "<br>----------> Index->open";
	   
	   	//-- Identificacao
	   	$MODULO="index";
	   	$LINK_MODULO = "{$this->_link}index/index/{$MODULO}/";
	   	 
	   	//echo "<br>Aqui no Controler esta como {$this->_dominio}";
	   	//-- Identificacao do Tela a ser processada
	   	$this->_dados['tel_nome']           = "Portal ERP4web - Pagina Principal";
	   	$this->_dados['tel_titulo']         = "Dialog";
	   	$this->_dados['tel_id']             = "0";
	   	$this->_dados['tel_cod']            = "XXXXX";
	   	 
	   	// Atualizando variaveis e controles basicos
	   	include("Include/portalInclude.php");
	   	
	   	//echo "<br>Controller: {$this->_controller}";
	   	//echo "<br>Task: {$this->_task}";
	   	//echo "<br>Action: {$this->_action}";
	   	
	   	// Determinando o nome da janela de dialogo
	   	$_DIALOG = $this->_action;
	   	 
	   	//-- Avionando a View
  		$this->dialog($_DIALOG,$this->_dados);
	   	 
	   }	   
   }
?>