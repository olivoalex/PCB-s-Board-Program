<?php
  session_start();

  $HOME = $_SERVER['DOCUMENT_ROOT'].'/App4Web';

  define ('CONTROLLERS',$HOME . '/Controllers/');
  define ('VIEWS',$HOME . '/Views/');
  define ('PAGES',$HOME . '/Pages/');
  define ('MODELS',$HOME . '/Models/');
  define ('HELPERS',$HOME . '/System/Helpers/');
  define ('LIBS',$HOME . '/System/Lib/');  
  define ('AJAX',$HOME . '/Ajax/');
  define ('JSON',$HOME . '/JSON/');
  define ('DIALOG',$HOME . '/Windows/');
  define ('PORTAL',$HOME . '/');

//echo "Carregando Config...";
  // As credenciais do portal precisam ser carregadas :)
  // Carregando o arquivo de config do PORTAL
  require_once("config.php");
  
  
//echo "Carregando System...";
  // Carregando classes basicas do MVC  
  require_once($HOME . '/System/system.php');
//echo "Carregando Controller...";
  require_once($HOME . '/System/controller.php');
//echo "Carregando Model...";
  require_once($HOME . '/System/model.php');
  
  
  function __autoload( $file ) {

        ////echo "loading ... {$file}<br>";

	if ( file_exists(CONTROLLERS . $file . '.php') )
		require_once(CONTROLLERS . $file . '.php');
	else if ( file_exists( MODELS . $file . '.php'))
		require_once(MODELS . $file . '.php');	
	else if ( file_exists( HELPERS . $file . '.php'))
		require_once(HELPERS . $file . '.php');
	else if ( file_exists( LIBS . $file . '.php'))
	    require_once(LIBS . $file . '.php');
	else if ( file_exists( AJAX . $file . '.php'))
	   	require_once(AJAX . $file . '.php');
	else if ( file_exists( JSON . $file . '.php'))
		require_once(JSON . $file . '.php');
	else if ( file_exists( PORTAL . $file . '.php'))
	    require_once(PORTAL . $file . '.php');	    	 
	else
	   	die("Arquivo in inicializar.php: [ $file ], Portal, Model, Helper, AJAX, JSON ou Lib nao encontrado.");
  }
  
//echo "Carregando Start...";
  // Iniciando o Sistema :)
  $start = new System;
//echo "Carregando WebFiles...";
  
  // Ferramentas de Terceiros usadas via MIT license
  require_once("web_files.php");
//echo "Carregando Logged...";
  
  // Determina texto para LOGGED: online ou offline
  require_once( LIBS . "Logged.php");   
  
  // Carregando o Arquivo de Config do Dominio ou Visitante
  if ( file_exists( $start->_session->selectSession("DOM_CONFIG") ) ) {     
     require_once( $start->_session->selectSession("DOM_CONFIG") );
  }
  
  //echo "<br>----------> Inicializar";
  
?>
