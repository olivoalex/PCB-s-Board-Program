<?php
class Controller {

	   protected function view($nome, $vars = null) {
		   
		   if (is_array($vars) && count($vars) > 0) {
			   extract($vars, EXTR_PREFIX_ALL, 'view');
		   }
		   
		   $file = VIEWS . $nome .'.phtml';
		   
		   //echo "<br>---------- View: ". $file;
		   
		   if ( ! file_exists( $file ) )
		   	  die("controller.php: Houve um erro. View: $file, nao existe...");

		   require_once( $file );

	   }
	   
	   protected function page($nome, $vars = null) {
		   
		   // Adicionando controles de pagina no conversor de HASH para variavel	   
           $vars["PAGE_NOT_FOUND_PATH"] = PAGES;
		   $vars["PAGE_NOT_FOUND_NAME"] = $nome;			   
		   $vars["PAGE_NOT_FOUND"]      = false;
		   
		   $file = PAGES . $nome;
		   
		   //echo "<br>---------- page: ". $file;
		   
		   if ( ! file_exists( $file ) ) {
			  // Verificando se a Pagian de Erro existe :)
			  $file = VIEWS . "error-404.html";
			  if ( ! file_exists( $file ) ) {
				  die("\ncontroller.php: Houve um erro. page: $file, nao existe...");				  
			  }			  
		   } else {
			 // pagina solicitada encontrada
			 $vars["PAGE_NOT_FOUND"] = true;
		   }
		
           // Conertendo as chaves de HASH para variavel		
		   if (is_array($vars) && count($vars) > 0) {
			   extract($vars, EXTR_PREFIX_ALL, 'view');
		   }
		   
		   require_once( $file );
	   }

	   protected function dialog($nome, $vars = null) {
	   	 
	   	if (is_array($vars) && count($vars) > 0) {
	   		extract($vars, EXTR_PREFIX_ALL, 'view');
	   	}
	   	 
	   	$file = DIALOG . $nome .'.phtml';

	   	if ( !file_exists( $file ) )
	   		die("controller.php: Houve um erro. Dialog: $file, nao existe...");
	   
	   		require_once( $file );
	   }
	   
	   protected function loadJSON($nome, $vars = null) {
	   	 
	   	  if (is_array($vars) && count($vars) > 0) {
	   		extract($vars, EXTR_PREFIX_ALL, 'load');
	   	  }
	   	 
		  // Normalmente o NOME eh composto por: tarefa_JSON.php
	   	  $file = JSON . $nome .'_JSON.php';
	   	    	 
	   	  if ( !file_exists( $file ) )
	   		die("controller.php: Houve um erro. JSON: $file, nao xiste...");
	   
	      //echo "<br>----------> Controller->loadJSON: ".$file;
		  
	      require_once( $file );	   
	   }
	   
	   public function init($_start) {
	   	
	   	//-- Recuperando a Sessao
		$this->_session = $_start->_session;
	  
	    //-- Determinando Redirecionamento
	    $this->_redirector = $_start->_redirector;
		
		//-- Pegando dados de Auth 
        $this->_auth = $_start->_auth;
		
		
		// Acho que cheguei onde te criava kkk
		//echo "Aqui...".$_start->_controller;

	   }
}
?>