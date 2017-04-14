<?php
   //-- Author : @fviana
   global $start;
   
   // Tratando acentuacao :(
   header('Content-Type: text/html; charset=UTF-8',true);   
   header("Expires: Mon, 12 Sep 1971 03:00:00 GMT");
   header("Cache-Control: no-cache");
   header("Pragma: no-cache");
    
   // Iniciando a sessao para esse objeto e pegando os dados de la :)
   // $_session = new sessionHelper();
     
   include($_SESSION["PORTAL_INCLUDE_PATH"] . "conectarInclude.php");       

   // Coloca como NAO logado
   $start->_session->createSession('LOGGED',"N");
   
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
   $_random = new RandomString(20);
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

   // tratamentos de formulario especifico para REGISTRAR
   if ( $_acao == "REGISTRAR" ) {
	  $_usr_login       = (isset($_PARAMETERS['usr_login_2'])) ? $_PARAMETERS['usr_login_2'] : 'NONE';
      $_usr_passwd      = (isset($_PARAMETERS['usr_passwd_2'])) ? $_PARAMETERS['usr_passwd_2'] : 'NONE';
   }

   // Colocando na sessao
   $_SESSION['acao']            = $_acao;
   $_SESSION['usr_login']       = $_usr_login;
   $_SESSION['usr_email']       = $_usr_email;
   $_SESSION['usr_passwd']      = $_usr_passwd;
   $_SESSION['usr_passwd_conf'] = $_usr_passwd_conf;
   $_SESSION['usr_nome']        = $_usr_nome;
   $_SESSION['usr_sobrenome']   = $_usr_sobrenome;
   $_SESSION['usr_login_email'] = $_usr_login_email;
   
   
   //Exibe algumas variaveis :)
   //echo "<br>---------Acao: {$_acao}";
   
   // Ideitificando usuario visitante
   $_SESSION['VISITANTE_DOMINIO'] = "Visitante";
   $_SESSION['VISITANTE_USER']    = "visitante";
   $_SESSION['VISITANTE_PASSWD']  = "tEsTe#123-2017";
   $_SESSION['VISITANTE_WEBKEY']  = "tEsTe#456-2017";
   $_SESSION['VISITANTE_NAME']    = "Visitante";
   $_SESSION['VISITANTE_APELIDO'] = "Visitante";
   $_SESSION['VISITANTE_EMAIL']   = "visitante@erp4web.com.br";
   $_SESSION['VISITANTE_PHOTO']   = "visitante.png";
   $_SESSION['VISITANTE_EMP_ID']  = 0;
   $_SESSION['VISITANTE_FIL_ID']  = 0;
   $_SESSION['VISITANTE_ENT_ID']  = 0;
   $_SESSION['VISITANTE_USR_ID']  = 0;
   $_SESSION['VISITANTE_ID']      = 0;

   
   // Determinando quem esta Logado, dependendo da ACAO sai como VISITANTE e LOGADO
   $_SESSION['LOGGED_DOMINIO'] = $_SESSION['VISITANTE_DOMINIO'];
   $_SESSION['LOGGED_USER']    = $_SESSION["VISITANTE_USER"];
   $_SESSION['LOGGED_PASSWD']  = $_SESSION["VISITANTE_PASSWD"];
   $_SESSION['LOGGED_WEBKEY']  = $_SESSION["VISITANTE_WEBKEY"];
   $_SESSION['LOGGED_NAME']    = $_SESSION["VISITANTE_NAME"];
   $_SESSION['LOGGED_APELIDO'] = $_SESSION["VISITANTE_APELIDO"];
   $_SESSION['LOGGED_EMAIL']   = $_SESSION["VISITANTE_EMAIL"];
   $_SESSION['LOGGED_PHOTO']   = $_SESSION["VISITANTE_PHOTO"];
   $_SESSION['LOGGED_EMP_ID']  = $_SESSION["VISITANTE_EMP_ID"];
   $_SESSION['LOGGED_FIL_ID']  = $_SESSION["VISITANTE_FIL_ID"];
   $_SESSION['LOGGED_ENT_ID']  = $_SESSION["VISITANTE_ENT_ID"];
   $_SESSION['LOGGED_USR_ID']  = $_SESSION["VISITANTE_USR_ID"];
   $_SESSION['LOGGED_ID']      = $_SESSION["VISITANTE_ID"];
   $_SESSION['LOGGED']         = "N";

   
   // Associativo para receber dados 	
   $_dados = array();
	
   // Conectando com o Banco
   $_pdo = Conectar();
	
   // Referencia com base de dados
   $_linha = null;
   $_referencia = null;
   $_idx = 0;	
   
   // Caso nao tenha recebido nenhum parametro, sai logado como VISITANTE
   if ( $_usr_login == "NONE" ) {
      // Determinando quem esta Logado, dependendo da ACAO sai como VISITANTE e LOGADO
      $_SESSION['LOGGED_DOMINIO'] = $_SESSION['VISITANTE_DOMINIO'];
      $_SESSION['LOGGED_USER']    = $_SESSION["VISITANTE_USER"];
      $_SESSION['LOGGED_PASSWD']  = $_SESSION["VISITANTE_PASSWD"];
	  $_SESSION['LOGGED_WEBKEY']  = $_SESSION["VISITANTE_WEBKEY"];
      $_SESSION['LOGGED_NAME']    = $_SESSION["VISITANTE_NAME"];
	  $_SESSION['LOGGED_APELIDO'] = $_SESSION["VISITANTE_APELIDO"];
      $_SESSION['LOGGED_EMAIL']   = $_SESSION["VISITANTE_EMAIL"];
      $_SESSION['LOGGED_PHOTO']   = $_SESSION["VISITANTE_PHOTO"];
      $_SESSION['LOGGED_EMP_ID']  = $_SESSION["VISITANTE_EMP_ID"];
      $_SESSION['LOGGED_FIL_ID']  = $_SESSION["VISITANTE_FIL_ID"];
      $_SESSION['LOGGED_ENT_ID']  = $_SESSION["VISITANTE_ENT_ID"];
      $_SESSION['LOGGED_USR_ID']  = $_SESSION["VISITANTE_USR_ID"];
      $_SESSION['LOGGED_ID']      = $_SESSION["VISITANTE_ID"];	  
      $_SESSION['LOGGED']         = "S";	
      $_mensagem = "Seja Bem Vindo, Visitante!!!";
   } else {
	  // Recebemos um usuario para validar
      // Acessando Base de Dados para Validar o LOGIN
      $_sql = "SELECT ent.ent_id, ent.ent_cod, ent.ent_scd, ent.ent_nome, ent.ent_nome_curto, ent.ent_apelido, ent.ent_login, "
	     .       " ent.ent_passwd, ent.ent_webkey, ent.ent_documento, ent.ent_foto_140x185 ent_foto, ent.ent_ativo, "
         .       " ent.tpe_id, ent.tte_id, ent.gte_id, "
         .       " ent.prv_id, "
         .       " ent.org_id, ent.ges_id, ent.emp_id, ent.fil_id, ent.dep_id, "
         .       " ent.ban_id, ent.agb_id, "
         .       " ent.end_id, "
		 .       " ent.eml_id, eml.eml_cod, "
		 .       " ent.tel_id, "
         .       " ent.pes_id, "
         .       " ent.ste_id, ent.sse_id, "
         .       " ent.prf_id, "
         .       " ent.nva_id "
         .  " FROM entidade ent "
		 .  " LEFT JOIN email eml "
		 .  "   ON ent.eml_id = eml.eml_id "
         . " WHERE ent_login = '" . $_usr_login."'";
		 
      //echo "<br>--------->JSON: sql -> ".$_sql;
		 
      $_stm = $_pdo->prepare($_sql);
      $_stm->execute();
      $_db_ret = $_stm->fetchAll(PDO::FETCH_ASSOC);
   
      if ( $_db_ret) {
         // Percorrendo o resultado     
         for ($_idx=0; $_idx < count($_db_ret); $_idx++) {
         
		    // Caso tenha mais de uma, fica com a ultima :(
		    $_linha = null;
		    $_referencia = null;
		 
	   	    // Dados do Login
	   	    $_referencia["ENTIDADE"]["ent_id"]         = $_db_ret[$_idx]["ent_id"];
		    $_referencia["ENTIDADE"]["ent_cod"]        = $_db_ret[$_idx]["ent_cod"];
		    $_referencia["ENTIDADE"]["ent_scd"]        = $_db_ret[$_idx]["ent_scd"];
		    $_referencia["ENTIDADE"]["ent_nome"]       = $_db_ret[$_idx]["ent_nome"];
		    $_referencia["ENTIDADE"]["ent_nome_curto"] = $_db_ret[$_idx]["ent_nome_curto"];
		    $_referencia["ENTIDADE"]["ent_apelido"]    = $_db_ret[$_idx]["ent_apelido"];
		    $_referencia["ENTIDADE"]["ent_login"]      = $_db_ret[$_idx]["ent_login"];
		    $_referencia["ENTIDADE"]["ent_passwd"]     = $_db_ret[$_idx]["ent_passwd"];
		    $_referencia["ENTIDADE"]["ent_webkey"]     = $_db_ret[$_idx]["ent_webkey"];
		    $_referencia["ENTIDADE"]["ent_documento"]  = $_db_ret[$_idx]["ent_documento"];
		    $_referencia["ENTIDADE"]["ent_foto"]       = $_db_ret[$_idx]["ent_foto"];
		    $_referencia["ENTIDADE"]["ent_ativo"]      = $_db_ret[$_idx]["ent_ativo"];
		    $_referencia["ENTIDADE"]["tpe_id"]         = $_db_ret[$_idx]["tpe_id"];
		    $_referencia["ENTIDADE"]["tte_id"]         = $_db_ret[$_idx]["tte_id"];
		    $_referencia["ENTIDADE"]["gte_id"]         = $_db_ret[$_idx]["gte_id"];
		    $_referencia["ENTIDADE"]["prv_id"]         = $_db_ret[$_idx]["prv_id"];
		    $_referencia["ENTIDADE"]["org_id"]         = $_db_ret[$_idx]["org_id"];
		    $_referencia["ENTIDADE"]["ges_id"]         = $_db_ret[$_idx]["ges_id"];
		    $_referencia["ENTIDADE"]["emp_id"]         = $_db_ret[$_idx]["emp_id"];
		    $_referencia["ENTIDADE"]["fil_id"]         = $_db_ret[$_idx]["fil_id"];
		    $_referencia["ENTIDADE"]["dep_id"]         = $_db_ret[$_idx]["dep_id"];
		    $_referencia["ENTIDADE"]["ban_id"]         = $_db_ret[$_idx]["ban_id"];
		    $_referencia["ENTIDADE"]["agb_id"]         = $_db_ret[$_idx]["agb_id"];
		    $_referencia["ENTIDADE"]["end_id"]         = $_db_ret[$_idx]["end_id"];
		    $_referencia["ENTIDADE"]["eml_id"]         = $_db_ret[$_idx]["eml_id"];
			$_referencia["ENTIDADE"]["eml_cod"]        = $_db_ret[$_idx]["eml_cod"];
		    $_referencia["ENTIDADE"]["tel_id"]         = $_db_ret[$_idx]["tel_id"];
		    $_referencia["ENTIDADE"]["pes_id"]         = $_db_ret[$_idx]["pes_id"];
		    $_referencia["ENTIDADE"]["ste_id"]         = $_db_ret[$_idx]["ste_id"];
		    $_referencia["ENTIDADE"]["sse_id"]         = $_db_ret[$_idx]["sse_id"];
		    $_referencia["ENTIDADE"]["prf_id"]         = $_db_ret[$_idx]["prf_id"];
		    $_referencia["ENTIDADE"]["nva_id"]         = $_db_ret[$_idx]["nva_id"];
		 
		    $_linha["REFERENCIA"] = $_referencia;
		  
		    $_dados["LOGIN"][$_idx] = $_linha;
	     }
      } else {
		   $_dados     = null;
		   $_status    = false;
		   $_mensagem .= "<br>Login Informado Não Existe.";	  
      }

      // Tratando a acao e fazendo o que precisa
      // Para registrar e Redefinir sai como LOGIN de Visitante
      if ( $_acao == "VALIDAR" ) {
	   
	   	 // Iniciando como se LOGIN fosse invalido
	     $_SESSION['LOGGED_PHOTO']    = "NONE";
	     $_SESSION['LOGGED_NAME']     = "NONE";
		 $_SESSION['LOGGED_APELIDO']  = "NONE";
		 $_SESSION['LOGGED_ID']       = 0;
         $_SESSION['LOGGED_EMAIL']    = "NONE";
		 
		 //print_r($_dados);
		 
	     if ( $_idx != 1 ) {
		    // Temos mais de uma ENTIDADE para o login informado
			if ( $_idx == 0 ) {
			   $_mensagem = "Login Informado Nao Existe, Tente Outro.";	
			} else {
			   $_mensagem = "Login Informado Inconsistente, Tente Outro.";
			}			
			$_SESSION['LOGGED'] = "N";
	     } else {
		    // Temos apenas uma ENTIDADE para esse LOGIN 
			if ( $_dados["LOGIN"][0]["REFERENCIA"]["ENTIDADE"]["ent_passwd"] != $_usr_passwd ) {
			   $_mensagem = "Senha Invalida.";
			   $_SESSION['LOGGED'] = "N";
			} else {
		       $_mensagem = "Seja Bem Vindo, ".$_dados["LOGIN"][0]["REFERENCIA"]["ENTIDADE"]["ent_apelido"];
			   $_SESSION['LOGGED']         = "S";
			   $_SESSION['LOGGED_PHOTO']   = $_dados["LOGIN"][0]["REFERENCIA"]["ENTIDADE"]["ent_foto"];
	           $_SESSION['LOGGED_NAME']    = $_dados["LOGIN"][0]["REFERENCIA"]["ENTIDADE"]["ent_nome_curto"];
			   $_SESSION['LOGGED_APELIDO'] = $_dados["LOGIN"][0]["REFERENCIA"]["ENTIDADE"]["ent_apelido"];
			   $_SESSION['LOGGED_WEBKEY']  = $_dados["LOGIN"][0]["REFERENCIA"]["ENTIDADE"]["ent_webkey"];
               $_SESSION['LOGGED_EMAIL']   = $_dados["LOGIN"][0]["REFERENCIA"]["ENTIDADE"]["eml_cod"];
			   // Da entidade
			   $_SESSION['LOGGED_EMP_ID']  = $_dados["LOGIN"][0]["REFERENCIA"]["ENTIDADE"]["emp_id"];
			   $_SESSION['LOGGED_FIL_ID']  = $_dados["LOGIN"][0]["REFERENCIA"]["ENTIDADE"]["fil_id"];
			   $_SESSION['LOGGED_ENT_ID']  = $_dados["LOGIN"][0]["REFERENCIA"]["ENTIDADE"]["ent_id"];
			   $_SESSION['LOGGED_USR_ID']  = $_dados["LOGIN"][0]["REFERENCIA"]["ENTIDADE"]["ent_id"];
			   $_SESSION['LOGGED_ID']      = $_dados["LOGIN"][0]["REFERENCIA"]["ENTIDADE"]["ent_id"];
			}
	     }	   
         $_SESSION['LOGGED_USER']= $_usr_login;
         $_SESSION['LOGGED_PASSWD']= $_usr_passwd;
	     
      }else{
         if ( $_acao == "REGISTRAR" ) {
		  
	     } else {
	        if ( $_acao == "REDEFINIR" || $_acao == "RECUPERAR" ) {
			 
		    } else {
			   if ( $_acao == "DESBLOQUEAR" || $_acao == "UNLOCK" ) {
			      $_SESSION['LOGGED_USER']= $_usr_login;
			      $_SESSION['LOGGED'] = ($_usr_login == "Fernando" ? "S" : "N");
                  if ( $_SESSION['LOGGED_PASSWD'] != $_usr_passwd ) {
			         $_SESSION['LOGGED'] = "N";
				     $_msg = "Senha Invalida.";
			      }	           
			   }
		    }
	     }		  
      } // acao = validar ?

   } //user_login = NONE
      
   //echo "Conteudo das Variaveis recebidas via POST: <br>";
   //echo "_acao               ".$_acao."<br>";
   //echo "_usr_login          ".$_usr_login."<br>";
   //echo "_usr_email          ".$_usr_email."<br>";
   //echo "_usr_passwd         ".$_usr_passwd."<br>";
   //echo "_usr_passwd_conf    ".$_usr_passwd_conf."<br>";
   //echo "_usr_nome           ".$_usr_nome."<br>";
   //echo "_usr_sobrenome      ".$_usr_sobrenome."<br>";
   //echo "_usr_login_email    ".$_usr_login_email."<br>";
   
   // Retorno padrao para JSON   
   $_return['OK']                 = ( $_SESSION['LOGGED'] == "S" ? true : false );
   $_return['ACAO']               = $_acao;
   $_return['STATUS']             = $_status;
   $_return['MENSAGEM']           = $_mensagem;
   $_return['ERROR']              = $_error;
   $_return['DADOS']              = $_dados;
   $_return['MSG']                = $_mensagem;
   
   // Retorno especifico para esse JSON
   $_return['FORM']['acao']       = $_acao;
   $_return['FORM']['usr_login']  = $_usr_login;
   $_return['FORM']['usr_email']  = $_usr_email;
   $_return['FORM']['usr_passwd'] = $_usr_passwd;
   $_return['CONTROLE']['emp_id'] = $_emp_id;
   $_return['CONTROLE']['usr_id'] = $_usr_id;
   
   // Passar dados do Login para o javascript
   $_return['LOGIN']['LOGGED_DOMINIO'] = $_SESSION['LOGGED_DOMINIO'];
   $_return['LOGIN']['LOGGED_USER']    = $_SESSION['LOGGED_USER'];
   $_return['LOGIN']['LOGGED_ID']      = $_SESSION['LOGGED_ID'];
   $_return['LOGIN']['LOGGED_EMP_ID']  = $_SESSION['LOGGED_EMP_ID'];
   $_return['LOGIN']['LOGGED_FIL_ID']  = $_SESSION['LOGGED_FIL_ID'];
   $_return['LOGIN']['LOGGED_PASSWD']  = $_SESSION['LOGGED_PASSWD'];
   $_return['LOGIN']['LOGGED_WEBKEY']  = $_SESSION['LOGGED_WEBKEY'];
   $_return['LOGIN']['LOGGED_NAME']    = $_SESSION['LOGGED_NAME'];
   $_return['LOGIN']['LOGGED_APELIDO'] = $_SESSION['LOGGED_APELIDO'];
   $_return['LOGIN']['LOGGED_EMAIL']   = $_SESSION['LOGGED_EMAIL'];
   $_return['LOGIN']['LOGGED_PHOTO']   = $_SESSION['LOGGED_PHOTO'];
   $_return['LOGIN']['LOGGED']         = $_SESSION['LOGGED'];
   
   //print_r($_return);
   
   // Retornando JSON   
   echo json_encode($_return);
?>