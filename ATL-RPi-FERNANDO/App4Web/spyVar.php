<?php  

   // Date in the past
   header('Content-Type: text/html; charset=UTF-8',true);
   header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
   header("Cache-Control: no-cache");
   header("Pragma: no-cache"); 

   include("System/Helpers/debugHelper.php");
   
   $_QUEM = "SESSION";
   if ( isset($_GET["quem"] ) ) {
      $_QUEM = $_GET["quem"];
   } 
   $_spyValores =  new debugHelper();
   
   echo "<h3>Debugging Variable: {$_QUEM}</h3>";
   switch ( $_QUEM ) {
	  case "GLOBALS" :
	     if ( isset($GLOBALS) ) {
	        $_spyValores->debug($GLOBALS);
		 } else {
			echo "\$GLOBALS, Nao definida";		 
		 }
		 break;
	  case "SERVER" :
	     if ( isset($_SERVER) ) {
	        $_spyValores->debug($_SERVER);
		 } else {
			echo "\$_SERVER, Nao definida";		 
		 }
		 break;
	  case "REQUEST" :
	     if ( isset($_REQUEST) ) {
	        $_spyValores->debug($_REQUEST);
		 } else {
			echo "\$_REQUEST, Nao definida";		 
		 }
		 break;
	  case "POST" :
	     if ( isset($_POST) ) {
	        $_spyValores->debug($_POST);
		 } else {
			echo "\$_POST, Nao definida";		 
		 }
		 break;
	  case "GET" :
	     if ( isset($_GET) ) {
	        $_spyValores->debug($_GET);
		 } else {
			echo "\$_GET, Nao definida";		 
		 }
		 break;
	  case "FILES" :
	     if ( isset($_FILES) ) {
	        $_spyValores->debug($_FILES);
		 } else {
			echo "\$_FILES, Nao definida";		 
		 }
		 break;
	  case "ENV" :
	     if ( isset($_ENV) ) {
	        $_spyValores->debug($_ENV);
		 } else {
			echo "\$_ENV, Nao definida";		 
		 }
		 break;
	  case "COOKIE" :
	     if ( isset($_COOKIE) ) {
	        $_spyValores->debug($_COOKIE);
		 } else {
			echo "\$_SESSION, Nao definida";		 
		 }
		 break;
	  case "SESSION" :
	     if ( isset($_SESSION) ) {
	        $_spyValores->debug($_SESSION);
		 } else {
			echo "\$_SESSION, Nao definida";		 
		 }
		 break;		 
	  default :
	     echo "{$_QUEM}, Nao definida";		 
   }

?>