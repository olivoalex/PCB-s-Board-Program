<?php

   class Ajax extends Controller {      	   
	 public $_system; 

     public function init($_start) {
		   
		  //echo "<br>----------> Ajax->init";		  
		  
          // Um INIT nao herda o anterior da classe da qual foi derivado, cada um executa apenas o seu
		  $this->_system = $_start;		  
		  
          //print_r($this->_system);		  
	 }   	 
	 
   	 public function index_action() {
   	 	 
   	 	//-- Identificacao
   	 	$MODULO= "ajaxController";   	 	 
   	 	
   	 	//echo "<br>----------> Ajax->index_action";		  
		//print_r($this->_system);
		
   	 	// Abre Arquivo JSON conforme a Tarefa Ex. tarafa_JSON.php
   	 	$this->loadJSON($this->_system->_task, $this->_system->_dados);
   	 	
   	 }
	 
	 public function index() {
		
		//echo "<br>----------> Ajax->index";		   
		
	    $this->index_action();
	 }   	 
	 
	 public function login() {
		
		//echo "<br>----------> Ajax->login";		   
		
	    $this->index_action();
	 } 
  	 
	 public function load() {
		
		//echo "<br>----------> Ajax->Load";		   
		
   	 	//-- Identificacao
   	 	$MODULO= "ajaxController.load";   	 	 
   	 	
   	 	//echo "<br>----------> Ajax->load";
		//print_r($this->_system);
		
   	 	// Abre Arquivo JSON conforme a ACAO Ex. stkm000100_AJAX.php
   	 	$this->loadJSON($this->_system->_action, $this->_system->_dados);
   	 	
		
	 } 	 
   }
?>