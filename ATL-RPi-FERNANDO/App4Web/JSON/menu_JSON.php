<?php
//-- Author : @fviana
global $start;
// Iniciando a sessao para esse objeto e pegando os dados de la :)
if (!isset($_SESSION) ) { session_start(); }    
include($_SESSION["PORTAL_INCLUDE_PATH"] . "conectarInclude.php");  

function retornaMenuOpcao($_pdo, $_mnu_id) {
	
   // Montando MENU com opcoes em JSON   
   $_sql = "SELECT "
         . " mno.mno_id, "
         . " IFNULL(mnu.opc_id,0) as mnu_id, IFNULL(mnu.opc_cod,'RAIZ') as mnu_cod, IFNULL(mnu.opc_descr,'Principal') as mnu_descr, IFNULL(mnu.opc_alias,'RAIZ') as mnu_alias, "
		 . " IFNULL(mnu.nva_id,0) as mnu_nva_id, IFNULL(mnu.prg_id,0) as mnu_prg_id, "
         . " tpm.tpo_cod, tpm.tpo_descr, tpm.tpo_controle, "
         . " tpo.tpo_cod, tpo.tpo_descr, tpo.tpo_controle, "
         . " opc.opc_id, opc.opc_cod, opc.opc_descr, opc.opc_alias, opc.prg_id, opc.opc_csstexto, "
		 . " IFNULL(prg.prg_id,0) prg_id, IFNULL(prg.prg_cod,'NONE') prg_cod, IFNULL(prg.prg_descr,'None') prg_descr, IFNULL(prg.prg_link,'NONE') prg_link "
		 . " FROM menu mno LEFT JOIN (opcao mnu, tipo_opcao tpm) ON mno.mnu_id = mnu.opc_id AND mnu.tpo_id = tpm.tpo_id ," 
         .      " opcao opc LEFT JOIN programa prg ON prg.prg_id = opc.prg_id ,"
         .		" tipo_opcao tpo "
         . " WHERE mno.opc_id = opc.opc_id "
         .   " AND opc.tpo_id = tpo.tpo_id "
		 // Pegando a estrutura do RAIZ qdo ZERO, e demais niveis != 0;
		 .   " AND mno.mnu_id = {$_mnu_id} " 
         . " ORDER BY mno.mnu_ordem asc, mnu_descr asc, opc_descr asc";
                    
   //echo "<br>--------->JSON: sql -> ".$_sql;
		 
   $_stm = $_pdo->prepare($_sql);
   $_stm->execute();
   $_db_ret = $_stm->fetchAll(PDO::FETCH_ASSOC);	
   
   return $_db_ret;
   
}   

function retornaMenuOpcaoArray($_pdo, $_db_ret) {
   
   // Percorrendo o resultado 
   // Associativo para receber dados 	
   $_dados = false;
   $_idx = 0;
   
   for ($_idx=0; $_idx < count($_db_ret); $_idx++) {
         	  
	  $_linha["MNO"]["ID"]           = $_db_ret[$_idx]['mno_id'];
		 
	  $_linha["MNU"]["ID"]    = $_db_ret[$_idx]['mnu_id'];
	  $_linha["MNU"]["COD"]   = $_db_ret[$_idx]['mnu_cod'];
	  $_linha["MNU"]["DESCR"] = $_db_ret[$_idx]['mnu_descr'];
	  $_linha["MNU"]["HASH"]  = "#";

	  $_linha["OPC"]["ID"]    = $_db_ret[$_idx]['opc_id'];
	  $_linha["OPC"]["TYPE"]  = $_db_ret[$_idx]['tpo_controle'];
	  $_linha["OPC"]["COD"]   = $_db_ret[$_idx]['opc_cod'];
	  $_linha["OPC"]["DESCR"] = $_db_ret[$_idx]['opc_descr'];
	  $_linha["OPC"]["CSS"]   = $_db_ret[$_idx]['opc_csstexto'];
	  $_linha["OPC"]["HASH"]  = "#";
		 
	  $_linha["PRG"]["ID"]    = $_db_ret[$_idx]['prg_id'];
	  $_linha["PRG"]["COD"]   = $_db_ret[$_idx]['prg_cod'];
	  $_linha["PRG"]["DESCR"] = $_db_ret[$_idx]['prg_descr'];
	  $_linha["PRG"]["HASH"]  = $_db_ret[$_idx]['prg_link'];
		 
	  $_dados[$_idx] = $_linha;
   }
   
   return $_dados;
   
}
   // Tratando acentuacao :(
   header("Expires: Mon, 12 Sep 1971 03:00:00 GMT");
   header("Cache-Control: no-cache");
   header("Pragma: no-cache");
 
  // trabalhando com parametros recebidos   
   $_emp_id  = (isset($_SESSION['emp_id']) ? $_SESSION['emp_id'] : 0);  
   $_usr_id  = (isset($_SESSION['usr_id']) ? $_SESSION['usr_id'] : 0);
   
   // Guardando dados na sessao
   $_SESSION["emp_id"] = $_emp_id;
   $_SESSION["usr_id"] = $_usr_id;
     
   // Variaveis padrao para RETORNO JSON
   $_errors = array();
   $_return = array();
   $_acao   = "NONE";
   $_id     = 0;
   //$_random = new RandomString(20);
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

   // Procurando os controles para poder serem executados
   // Determinando a ACAO a ser executada
   if (isset($_PARAMETERS['acao'])) {
      $_acao = $_PARAMETERS['acao'];
   } else {
	  $_acao = "NONE";
   }
   if (isset($_PARAMETERS['id'])) {
      $_id = $_PARAMETERS['id'];
   } else {
	  $_id = 0;
   }
   
   //Recebe os parâmetros enviados via POST
   $_acao      = (isset($_PARAMETERS['acao'])) ? $_PARAMETERS['acao'] : 'NONE';
   $_parametro = (isset($_PARAMETERS['parametro'])) ? $_PARAMETERS['parametro'] : 'NONE';

   //Recebendo dados para serem processados via GET     
   $_acao            = (isset($_PARAMETERS['acao'])) ? $_PARAMETERS['acao'] : 'VALIDAR';
   $_usr_login       = (isset($_PARAMETERS['usr_login'])) ? $_PARAMETERS['usr_login'] : 'NONE';
   $_usr_email       = (isset($_PARAMETERS['usr_email'])) ? $_PARAMETERS['usr_email'] : 'NONE';
   $_usr_passwd      = (isset($_PARAMETERS['usr_passwd'])) ? $_PARAMETERS['usr_passwd'] : 'NONE';
   $_usr_passwd_conf = (isset($_PARAMETERS['usr_passwd_conf'])) ? $_PARAMETERS['usr_passwd_conf'] : 'NONE';
   $_usr_nome        = (isset($_PARAMETERS['usr_nome'])) ? $_PARAMETERS['usr_nome'] : 'NONE';
   $_usr_sobrenome   = (isset($_PARAMETERS['usr_sobrenome'])) ? $_PARAMETERS['usr_sobrenome'] : 'NONE';
   $_usr_login_email = (isset($_PARAMETERS['usr_login_email'])) ? $_PARAMETERS['usr_login_email'] : 'NONE'; 
   
   // Associativo para receber dados 	
   $_dados = array();
	
   // Conectando com o Banco
   $_pdo = Conectar();
	
   // Referencia com base de dados
   $_linha = null;
   $_referencia = null;
   $_idx = 0;	
   
   // Solicita carregar a RAIZ do MENU
   $_db_ret = retornaMenuOpcao($_pdo, 0);
   $_dados["RAIZ"] = retornaMenuOpcaoArray($_pdo,$_db_ret);
	  
   // Percorrendo o resultado RAIZ para montar o 1o. Nivel - NV01	  
   if (  $_dados["RAIZ"] ) {	 
	  // Montando Nivel 1  
	  for ( $_idx=0; $_idx < count($_dados["RAIZ"]); $_idx++ ) {
		 $_mnu_id = $_dados["RAIZ"][$_idx]["OPC"]["ID"];
		 
         $_db_ret = retornaMenuOpcao($_pdo, $_mnu_id);
		 $_nv01 = retornaMenuOpcaoArray($_pdo, $_db_ret);
		 $_dados["RAIZ"][$_idx]["NV01"] = $_nv01;

		 		 
		// Percorrendo o resultado NV01 para montar o 2o. Nivel - NV02 
		if ( $_nv01 ) {
		    //echo "Tem mnuid: {$_mnu_id} Nivel 1 a ser explodido<br>";
		   // Montando Nivel 2
		   for ( $_idx_1=0; $_idx_1 < count($_nv01); $_idx_1++ ) {
			  $_mnu_id_nv1 = $_nv01[$_idx_1]["OPC"]["ID"];		      
              $_db_ret_1 = retornaMenuOpcao($_pdo, $_mnu_id_nv1);
		      $_nv02 = retornaMenuOpcaoArray($_pdo, $_db_ret_1);
		      $_dados["RAIZ"][$_idx]["NV01"][$_idx_1]["NV02"] = $_nv02;
		   }
		}

        // Percorrendo o resultado NV02 para montar o 3o. Nivel - NV03
        // Percorrendo o resultado NV03 para montar o 4o. Nivel - NV04
        // Percorrendo o resultado NV04 para montar o 5o. Nivel - NV05
	  } // Raiz	  
   } else {
	  $_dados     = null;
	  $_status    = false;
	  $_mensagem .= "<br>Não Existe um Menu Montado Ainda :).";	  
   }
   
   //echo "Conteudo das Variaveis recebidas via POST: <br>";
   //echo "_acao               ".$_acao."<br>";
   
   // Retorno padrao para JSON   
   $_return['OK']                 = ( $_SESSION['LOGGED'] == "S" ? true : false );
   $_return['ACAO']               = $_acao;
   $_return['STATUS']             = $_status;
   $_return['MENSAGEM']           = $_mensagem;
   $_return['ERROR']              = $_error;
   $_return['DADOS']              = $_dados;
   $_return['MSG']                = $_mensagem;
   
   // Retornando JSON   
   echo json_encode($_return);
?>