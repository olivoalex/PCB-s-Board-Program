<?php
   //-- Author : @fviana
   // Tratando acentuacao :(
   header('Content-Type: text/html; charset=UTF-8',true);   
   header("Expires: Mon, 12 Sep 1971 03:00:00 GMT");
   header("Cache-Control: no-cache");
   header("Pragma: no-cache");
       
   // Variaveis padrao para RETORNO JSON
   $_errors = array();
   $_return = array();
   $_acao   = "NONE";
   $_id     = 0;
   $_random = 0;
   $_status = true;
   $_mensagem = "";
   $_error  = "";
   $_CURRENT_DATE     = date('Y-m-d');
   $_CURRENT_TIME     = date('H:i:s');
   $_CURRENT_DATETIME = date('Y-m-d H:i:s');    
		
   // Junta POST com GET   
   $_PARAMETERS = null;
   if ( isset($_POST) ) { $_PARAMETERS = $_POST; }
   if ( isset($_GET)  ) { $_PARAMETERS = array_merge($_PARAMETERS, $_GET) ; }
   
   //POST ou GET em PARAMETERS
   //echo "<br>---------- Parametros<br>";
   //print_r($_PARAMETERS);
  
   //Recebe os parâmetros enviados via POST
   $_acao      = (isset($_PARAMETERS['acao'])) ? $_PARAMETERS['acao'] : 'NONE';
  
   //Exibe algumas variaveis :)
   //echo "<br>---------Acao: {$_acao}";
     
   // Associativo para receber dados 	
   $_dados = array();
	
   // Conectando com o Banco
   //$_pdo = Conectar();
	
   // Referencia com base de dados
   $_linha = null;
   $_referencia = null;
   $_idx = 0;	
   
   // Carregando dados
   $_dados["ID"] = $_acao;
   $_dados["TEMP-FX-01-DE"] = 0;       $_dados["TEMP-FX-01-ATE"] = 5;
   $_dados["TEMP-FX-02-DE"] = 5;       $_dados["TEMP-FX-02-ATE"] = 8;
   $_dados["TEMP-FX-03-DE"] = 8;       $_dados["TEMP-FX-03-ATE"] = 21;
   $_dados["TEMP-FX-04-DE"] = 21;      $_dados["TEMP-FX-04-ATE"] = 28;
   $_dados["TEMP-FX-05-DE"] = 28;      $_dados["TEMP-FX-05-ATE"] = 100;
   
   
   // Retorno padrao para JSON   
   $_return['OK']                 = true;
   $_return['ACAO']               = $_acao;
   $_return['STATUS']             = $_status;
   $_return['MENSAGEM']           = $_mensagem;
   $_return['ERROR']              = $_error;
   $_return['DADOS']              = $_dados;
   $_return['MSG']                = $_mensagem;
   
  
   //print_r($_return);
   
   // Retornando JSON   
   echo json_encode($_return);
?>