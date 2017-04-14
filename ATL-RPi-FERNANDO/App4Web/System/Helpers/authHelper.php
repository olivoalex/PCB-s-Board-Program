<?php
class authHelper{
   	   protected $sessionHelper,
   	             $redirectorHelper,
   	             $tableName,
   	             $userColumn,
   	             $passColumn,
   	             $user,
   	             $pass,
   	             $loginController  = 'index',
   	             $loginTask        = 'index',
   	             $loginAction      = 'index',
   	             $logoutController = 'index',
   	             $logoutTask       = 'index',
   	             $logoutAction     = 'index';
   	   public $db;
   	   public $_eMailHelper;
   	   public $_RandomString;

   	   public function __construct(){
   	   	 $this->sessionHelper = new sessionHelper();
   	   	 $this->redirectorHelper = new redirectorHelper();
   	   	 $this->db = new Model();
   	   	 $this->_eMailHelper = new eMailHelper();
   	   	 $this->_RandomString = new RandomString(10);
   	   	 return $this;
   	   }
   	   
   	   public function setTableName( $valor) {
   	   	 $this->tableName = $valor;
   	   }

   	   public function setUserColumn( $valor) {
   	   	 $this->userColumn = $valor;
   	   }

   	   public function setPassColumn( $valor) {
   	   	 $this->passColumn = $valor;
   	   }

   	   public function setUser( $valor) {
   	   	 $this->user = $valor;
   	   }

   	   public function setPass( $valor) {
   	   	 $this->pass = $valor;
   	   }
   	   
   	   public function setLoginControllerTaskAction( $controller, $task, $action) {
   	   	 $this->loginController = $controller;
   	   	 $this->loginTask = $task;
   	   	 $this->loginAction = $action;
   	   	 return $this;
   	   }
   	   
   	   public function setLogoutControllerTaskAction( $controller, $task, $action) {
   	   	 $this->logoutController = $controller;
   	   	 $this->logoutTask = $task;
   	   	 $this->logoutAction = $action;
   	   	 return $this;
   	   }

   	   public function getUser() {
   	   	return $this->user;
   	   }
   	   
   	   // Function to get the client ip address
   	   public function getClientIp() {
   	   	  if ( array_key_exists('REMOTE_ADDR',$_SERVER) ) {;
   	   	    return $_SERVER["REMOTE_ADDR"];
   	   	  } else {
   	   	  	return "0.0.0.0";
   	   	  }   	   	     	   	     	      
   	   }
   	   
   	   public function getClientHost() { 
   	      if ( array_key_exists('REMOTE_HOST',$_SERVER) ) {;
   	        return $_SERVER["REMOTE_HOST"];
   	      } else {
   	   	    return "Desconhecido";
   	      }
   	   }
   	   
   	   public function getClientPort() {
   	      if ( array_key_exists('REMOTE_PORT',$_SERVER) ) {;
   	         return $_SERVER["REMOTE_PORT"];
   	      } else {
   	   	     return "0000";
   	      }
   	   }
   	   
   	   public function logEntidadeAcesso($_log_inout, $_mnu_id=0, $_opc_id=0, $_prg_id=0) {
	   	   		
	   	  //Logal ultimo acesso
	   	  $_sql = "INSERT INTO entidade_acesso (eac_id,ent_id,mnu_id,opc_id,prg_id,eac_login_logout,eac_remote_addr,eac_remote_host,eac_remote_port,eac_dtmreg,usr_id_reg)".
	   	  	  	  " VALUES (0,".$this->userData('ent_id').", {$_mnu_id}, {$_opc_id}, {$_prg_id},'{$_log_inout}','".
	   	  		  $this->getClientIp()."','".
	   	  		  $this->getClientHost()."','".
	   	  		  $this->getClientPort()."',".
	   	  		  "CURRENT_TIMESTAMP,".
	   	  		  $this->userData("ent_id").")";
	   	  // Inserindo o Log de saida
	   	  $_insert = $this->db->freeHandInsert($_sql);   	   
   	   }
   	   
   	   public function logEntidadeSolicitacaoAcesso($_login, $_passwd, $_email) {

   	   	// Mensagem de retorno   	   	
   	   	$_msg= "NONE";
   	   	$_ok=false;
   	   	
   	   	// Verificar se já existe uma entidade paraq o Login
   	   	$this->db->_tabela_nome = "entidade";
   	   	$_where = "ent_login = '{$_login}'";
   	   	$_ver = $this->db->select($this->db->_tabela_nome, $_where, '1');
   	   	if ( count($_ver) > 0 ) {
   	   		$_msg = "Atenção:<br>".
   	   		        "<br>Login informado, {$_login}, ja esta registrado em nosso Portal.<br>".
     	   		    "<br>Favor usar suas credenciais ou solicitar ajuda a nossa Central de Atendimento Virtual.".
     	   		    "<br><br>Equipe de Suporte.";
	   	   	// Retornando um HASH com mensagem e true/false
   		   	$_ret['msg'] = $_msg;
   	   		$_ret['ok']  = false;   	   		
   	   		return $_ret;     	   		    		
   	   	}
   	   	
   	   	//Verifica se o LOGIN ou email ja esta com solicitacao aberta
   	   	// Tipo_Entidade
   	   	$this->db->_tabela_nome = "entidade_solicitacao_acesso";
   	   	$_where = "esa_login = '{$_login}'";
   	   	$_ver = $this->db->select($this->db->_tabela_nome,$_where,'1');
   	   	if ( count($_ver) > 0 ) {
   	   		// Login ja registrado
   	   		$_msg = "Login informado, {$_login}, ja têm solicitação pendente de contato.<br>";
   	   	}
   	   	$_where = "esa_email = '{$_email}'";
   	   	$_ver =$this->db->select($this->db->_tabela_nome,$_where,'1');
   	   	if ( count($_ver) > 0 ) {
   	   		if ( $_msg == "NONE" ) {
   	   		  $_msg = "O Email informado, {$_email}, já possui solicitação pendente de contato.<br>";
   	   		} else {
   	   		  $_msg .= "<br>O Email informado, {$_email}, também já possui solicitação pendente.<br>";
   	   		}
   	   	}
   	   	
   	   	if ( $_msg != "NONE" ) {
   	   		$_msg .= "<br>Aguarde nosso contato através do email {$_email}.<br>".
   	   		         "<br>Agradecamos sua compreensão.<br>".
   	   		         "<br>Equipe de Relacionamento com cliente.";
   	   		$_msg = ucfirst($_login)."<br><br>" . $_msg;
   	   	} else {
   	   		$_sql = "INSERT INTO entidade_solicitacao_acesso (esa_id,esa_login,esa_passwd,esa_email,esa_dtmreg) ".   	   	
   	   				" VALUES (0, '{$_login}', '{$_passwd}', '{$_email}', CURRENT_TIMESTAMP)";   	   	
   	   		//Inserindo o Log de saida
   	   		$_insert = $this->db->freeHandInsert($_sql);
   	   		
   	   		$_msg = ucfirst($_login).
     	   		    ",<br><br>Sua solicitação foi registrada com sucesso.<br>";
   	   		
   	   		$_ok = true;
   	   		
   	   		// Preparando email para ser enviado
   	   		$_msg_subject = "Solicitacao de Acesso para o Login {$_login}.";
   	   		$_msg_body    = "Oi, {$_login}<br>".
     	   		            "<br>Conforme sua solicitação, estamos entrando em contato para registrar seu interesse em nossa ferramenta.".
     	   		            "<br>Nossa equipe de Contato com o Cliente, esta configurando um ambiente para você e assim que possivel,".
     	   		            "<br>enviaremos um mail com maiores detalhes de seu acesso ao nosso Portal <a href='www.clog.com.br/erp4web/Visitante/index/index/index'>ERP4web</a>.".
     	   		            "<br><br>Dados digitados por você no nosso Poral:".
     	   		            "<br>Login: {$_login}".
     	   		            "<br>eMail: {$_email}".
     	   		            "<br>Senha: {$_passwd}".
     	   		            "<br><br>Obrigado pela atenção.".
     	   		            "<br>Equipe Relaciomanento com o Cliente.";
   	   		// Preparando Objeto
   	   		$this->_eMailHelper->setDe("portal@clog.com.br");
   	   		$this->_eMailHelper->setDeNome("Portal ERP4web");
   	   		$this->_eMailHelper->setSubject($_msg_subject);
   	   		$this->_eMailHelper->setBody($_msg_body);
   	   		$this->_eMailHelper->setPara($_email);
   	   		//Enviando o email
   	   		$_ret = $this->_eMailHelper->sendMail();
   	   		
   	   		//Verificando como foi o envio do email
   	   		if ( $_ret['ok'] == true ) {
   	   		   $_msg .= "Aguarde nosso contato através do email {$this->_eMailHelper->getDe()}.<br>";
   	   		            //"<hr>".$_ret['msg'];
   	   		} else {
   	   		   $_msg .= "Entraremos em contato assim que possivel.<br>";
     	   		        //"<br>".$_ret['msg'].
     	   		        //"<br> Status: ".$_ret['ok'];
   	   	       //
   	   	       // Temos que atualizar a solicitacao indicando que teve falha no envio do email
   	   	       //  Tambem analissar se vai criar a tabela eMail para obter um ID do que foi enviado e tb guardar o email :|
   	   		}
   	   		$_msg .= "<br>Agradecamos sua preferência.<br>".
   	   			     "<br>Equipe de Relacionamento com cliente.";   	   			
   	   	}
   	   	
   	   	// Retornando um HASH com mensagem e true/false
   	   	$_ret['msg'] = $_msg;
   	   	$_ret['ok']  = $_ok;
   	   	
   	   	return $_ret;
   	   	
   	   }   	   
   	   	
   	   public function logEntidadeSolicitacaoReset($_login, $_old_senha, $_nova_senha, $_resenha) {
   	   
   	   	// Mensagem de retorno
   	   	$_msg= "NONE";
   	   	$_ok=false;
   	   	
   	   	if ( $_login == null || $_login == " " || empty($_login) ) {
   	   		if ( $_msg == "NONE" ) { $_msg = ""; }
   	   		$_msg .= "Favor informar um Login.<br>";
   	   	}
   	   	if ( $_old_senha == null || $_old_senha == " " || empty($_old_senha) ) {
   	   		if ( $_msg == "NONE" ) { $_msg = ""; }
   	   		$_msg = "Favor digitar sua senha de acesso enviada por email.<br>";
   	   	}
   	   	if ( $_nova_senha == null || $_nova_senha == " " || empty($_nova_senha) ) {
   	   		if ( $_msg == "NONE" ) { $_msg = ""; }
   	   		$_msg = "Favor digitar uma nova senha.<br>";
   	   	}
   	   	if ( $_nova_senha == null || $_nova_senha == " " || empty($_nova_senha) ) {
   	   		if ( $_msg == "NONE" ) { $_msg = ""; }
   	   		$_msg = "Favor re-digitar a nova senha.<br>";
   	   	}   	   	
   	  
   	   	//Verifica se o LOGIN ou email ja esta com solicitacao aberta
   	   	// Tipo_Entidade
   	   	if ( $_msg = "NONE" ) {
   	   	   $this->db->_tabela_nome = "entidade";
   	   	   $_where = "ent_login = '{$_login}'";
   	   	   $_ent = $this->db->select($this->db->_tabela_nome,$_where,'1');
   	   	   if ( count($_ent) > 0 ) {
   	   	      // Senha digitada confere com a enviada   	   	      
   	   	   	  //echo "<script>alert('Entidade:{$_ent[0]['ent_nome']} e Senha:{$_ent[0]['ent_passwd']} para Login:{$_ent[0]['ent_login']}');</script>";
   	   	      
   	          if ( $_old_senha != $_ent[0]["ent_passwd"] ) {
   	   		      if ( $_msg == "NONE" ) {
   	   			      $_msg = "Senha de acesso Invalida.<br>";     	   			       
   	   		      } else {
   	   			     $_msg .= "<br>Senha de acesso invalida.<br>";   	   			            
   	   		      }
   	   		      $_msg .= "<br>Use a senha enviada para o seu email.<br>";
   	   	      }
   	   	      
   	   	   } else {
     	      // Login nao registrado   	   	   	
   	   	   	  $_msg = "Login informado, {$_login}, nao existe.<br>";
   	   	   }
   	   	   
   	   	   // Senhas iguais nao
   	   	   if ( $_nova_senha != $_resenha ) {
   	   	   	  if ( $_msg == "NONE" ) {
   	   	   		 $_msg = "Nova senha Informada nao é igual a re-digitada.<br>";
   	   	   	  } else {
   	   	   		 $_msg .= "<br>Novas senhas invalidas, sao diferentes.<br>";
   	   	   	  }
   	   	   }
   	   	}

   	   	//Vamos ver como chegou ate aqui
   	   	if ( $_msg != "NONE" ) {
   	   		$_msg .= "<br>Verifique valores digitados e tente novamente.";   	   				
   	   	} else {
   	   		$_sql = "UPDATE entidade SET ent_passwd = '{$_nova_senha}' , ". 
   	   				                   " ent_dtmupd =CURRENT_TIMESTAMP , ".
   	   				                   " usr_id_upd = {$_ent[0]['ent_id']} ".
   	   		         " WHERE ent_id = ".$_ent[0]['ent_id'];

   	   		//Inserindo o Log de saida
   	   		$_update = $this->db->freeHandUpdate($_sql);
   	   	    
   	   		//echo "<script>alert('Update Status: {$_update}');</script>";   	   		
   	   		//print_r($_update);
   	   	    
   	   		if ( $_update ) {
   	   			$_msg = ucfirst($_ent[0]['ent_nome']).   	   		
   	   		        ",<br><br>Senha atualizada com sucesso.<br>".
   	   		        "Você já pode fazer login e usar nosso Portal.<br>".
   	   		        "<br>Agradecamos sua preferência.<br>".
   	   		        "<br>Equipe de Relacionamento com cliente.";
   	   		    $_ok = true;
   	   		} else {
   	   			$_msg = "<br><br>Nao foi possivel alterar sua senha, tente mais tarde.<br>".
     	   			    "Se essa mensagem persistir, favor entre em contato com o Suporte.<br>".
     	   			    "Obrigado pela sua compreensão.<br>".
     	   			    "<br>Equipe de Relacionamento com cliente.";
   	   			$_ok = false;
   	   		}
   	   	}
   	   
   	   	// Retornando um HASH com mensagem e true/false
   	   	$_ret['msg'] = $_msg;
   	   	$_ret['ok']  = $_ok;
   	   
   	   	return $_ret;
   	   
   	   }
   	      	   
   	   public function logEntidadeNovaSenha($_login) {
   	   		
   	   	// Mensagem de retorno
   	   	$_msg= "NONE";
   	   	$_ok=false;
   	   	$_email = "";
   	   
   	   	if ( $_login == null || $_login == " " || empty($_login) ) {
   	   		if ( $_msg == "NONE" ) { $_msg = ""; }
   	   		$_msg .= "Favor informar um Login.<br>";
   	   	}
   	   
   	   	//Verifica se o LOGIN ou email ja esta com solicitacao aberta
   	   	// Tipo_Entidade
   	   	if ( $_msg = "NONE" ) {
   	   		$this->db->_tabela_nome = "entidade";
   	   		$_where = "ent_login = '{$_login}'";
   	   		$_ent = $this->db->select($this->db->_tabela_nome,$_where,'1');
   	   		if ( count($_ent) > 0 ) {   	   
   	   		} else {
   	   			// Login nao registrado
   	   			$_msg = "Login informado, {$_login}, nao existe.<br>";
   	   		}
   	   	}
   	   	
   	   	// Verificando se entidade tem email
   	   	$this->db->_tabela_nome = "email";
   	   	$_where = "ent_id = {$_ent[0]['ent_id']}";
   	   	$_eml = $this->db->select($this->db->_tabela_nome,$_where,'1');
   	   	if ( count($_ent) > 0 ) {
   	   		$_email = $_eml[0]['eml_cod'];
   	   	} else {
   	   		$_msg = "Login solicitado sem email, favor solicitar inclusao de um email via Central de Atendimento Virtual.";
   	    }
   	   
   	   	//Vamos ver como chegou ate aqui
   	   	if ( $_msg != "NONE" ) {
   	   		$_msg .= "<br>Verifique valores digitados e tente novamente.";
   	   	} else {
   	   		// Gerar um numero sequencial com 10 digitos e substiruir com a senha atual
   	   		$this->_RandomString->setRandomString();
   	   		$_nova_senha = $this->_RandomString->getRandomString();
   	   	
   	   		$_sql = "UPDATE entidade SET ent_passwd = '{$_nova_senha}' , ".
   	   				" ent_dtmupd =CURRENT_TIMESTAMP , ".
   	   				" usr_id_upd = {$_ent[0]['ent_id']} ".
   	   				" WHERE ent_id = ".$_ent[0]['ent_id'];
   	   
   	   		//Inserindo o Log de saida
   	   		$_update = $this->db->freeHandUpdate($_sql);
   	   
   	   		if ( $_update ) {
   	   			$_msg = ucfirst($_ent[0]['ent_nome']).
   	   			        ",<br><br>Nova Senha atualizada com sucesso.<br>".
   	   			        "Enviamos um email para você com essa informação.<br>".
   	   			        "Favor aguardar esse email e assim que possível venha ao nosso portal e altere sua nova senha para uma de sua escolha.<br>".
   	   			        "<br>Agradecemos sua preferência.<br>".
   	   			        "<br>Equipe de Relacionamento com cliente.";
   	   			
   	   			// Enviar um email
   	   			// Preparando email para ser enviado
   	   			$_msg_subject = "Nova Senha para o Login {$_login}.";
   	   			$_msg_body    = "Oi, {$_ent[0]['ent_nome']}<br>".
   	   					"<br>Geramos uma nova senha para você pder fazer o reset da antiga.".
   	   					"<br>Entre em nosso Portal <a href='www.clog.com.br/erp4web/Visitante/index/index/index'>ERP4web</a> e use essa nova senha: {$_nova_senha}".   	   				
   	   					"<br><br>Qualquer necessidade, entre em contato pela Central de Atendimento Virtual.".
   	   					"<br>Obrigado pela atenção.".
   	   					"<br><br>Equipe Relaciomanento com o Cliente.";
   	   			// Preparando Objeto
   	   			$this->_eMailHelper->setDe("portal@clog.com.br");
   	   			$this->_eMailHelper->setDeNome("Portal ERP4web");
   	   			$this->_eMailHelper->setSubject($_msg_subject);
   	   			$this->_eMailHelper->setBody($_msg_body);
   	   			$this->_eMailHelper->setPara($_email);
   	   			//Enviando o email
   	   			$_ret = $this->_eMailHelper->sendMail();
   	   			if ( $_ret['ok'] == false ) {
   	   				$_msg = ucfirst($_login).
   	   			            ",<br><br>Tivemos alguns problemas internos e não conseguimos te enviar um email com a Nova Senha.<br>".
   	   			            "Por favor, entre em nosso Portal e solicite o reset de sua senha novamente.<br>".
   	   			            "<br>Agradecemos sua compreenção.<br>".
   	   			            "<br>Equipe de Suporte.";
   	   			    $_ok = false;
   	   			} else {
   	   				$_ok = true;
   	   			}   	   			   	   			
   	   		} else {
   	   			$_msg = "<br><br>Nao foi possivel alterar sua senha, tente mais tarde.<br>".
   	   					"Se essa mensagem persistir, favor entre em contato com o Suporte.<br>".
   	   					"Obrigado pela sua compreensão.<br>".
   	   					"<br>Equipe de Relacionamento com cliente.";
   	   			$_ok = false;
   	   		}
   	   	}
   	   		
   	   	// Retornando um HASH com mensagem e true/false
   	   	$_ret['msg'] = $_msg;
   	   	$_ret['ok']  = $_ok;
   	   		
   	   	return $_ret;
   	   		
   	   }
   	   
   	   public function checkLogin($action) {

   	   	 switch ($action) {
   	   	 	case "boolean" :
   	   	 		if (!$this->sessionHelper->checkSession("userAuth"))
   	   	 			return false;
   	   	 		else 
   	   	 			return true;
   	   	 		break;
   	   	 	case "redirect" :
   	   	 		if (!$this->sessionHelper->checkSession("userAuth")) {
   	   	 			    //echo "<br> chckLogin current Controller {$this->redirectorHelper->getCurrentController()}";
   	   	 			    //echo "<br> chckLogin current Task {$this->redirectorHelper->getCurrentTask()}";
   	   	 			    //echo "<br> chckLogin current Action {$this->redirectorHelper->getCurrentAction()}";
   	   	 			    //echo "<br> checkLogin Login Controller {$this->loginController}";
   	   	 			    //echo "<br> checkLogin Login Task {$this->loginTask}";
   	   	 			    //echo "<br> checkLogin Login Action {$this->loginAction}";
   	   	 			if ($this->redirectorHelper->getCurrentController() != $this->loginController ||
   	   	 				$this->redirectorHelper->getCurrentTask() != $this->loginTask ) {
   	   	 			    //echo "<br>Aqui...";
   	   	 				$this->redirectorHelper->goToControllerTaskAction($this->loginController, $this->loginTask, $this->loginAction);
   	   	 			}
   	   	 		}
   	   	 		break;

   	   	 	case "stop" :
   	   	 		if (!$this->sessionHelper->checkSession("userAuth"))
   	   	 			exit;
   	   	 		break;
   	   	 }
   	   	 
   	  }
   	  
   	  public function userData( $key ) {
   	  	$ret = $this->sessionHelper->selectSession("userData");
   	  	if ( $ret ) {
   	  		return $ret[$key];
   	  	} else {
   	  		return "NONE";
   	  	}   	  	
   	  }
   	  
   	  public function userTPE( $key ) {
   	  	$ret = $this->sessionHelper->selectSession("TPE");
   	  	if ( $ret ) {
   	  	   return $ret[$key];
   	  	} else {
   	  	   return "NONE";
   	  	}
   	  }
   	  
   	  public function userTTE( $key ) {
   	  	$ret = $this->sessionHelper->selectSession("TTE");
   	  	if ( $ret ) {
   	  		return $ret[$key];
   	  	} else {
   	  		return "NONE";
   	  	}
   	  }
   	  
   	  public function userGTE( $key ) {
   	  	$ret = $this->sessionHelper->selectSession("GTE");
   	  	return $ret[$key];
   	  	if ( $ret ) {
   	  		return $ret[$key];
   	  	} else {
   	  		return "NONE";
   	  	}   	  	
   	  }

   	  public function setCurrentDominio($aDominio) {
   	  	global $start;
   	  	$start->_dominio = $aDominio;
   	  }
   	  
   	  public function login() {
   	  	global $start;
   	  	$logged = false;
   	  	$db = new Model();

   	  	echo "<br>Usuario: ".$this->user;
   	  	echo "<br>Senha: ".$this->pass;
   	  	echo "<br>Usuario ucfirst: ".ucfirst($this->user);
   	  	
   	  	// Cria uma variavel de sessao 
   	  	$this->sessionHelper->createSession("userAuthError", "");
   	  	
 	  	echo "<br>autHelper->Login: Table: {$this->tableName}";
 	  	
     	$db->_tabela_nome = $this->tableName;

   	  	$where = $this->userColumn . " = '" . $this->user . "' "; //and ". 
     	  	        //$this->passColumn . " = '" . $this->pass . "'";

   	  	$sql = $db->select($this->tableName, $where,'1');
   	  	
   	  	$this->sessionHelper->createSession("logado", false);
   	  	
   	    if (count($sql) > 0 ) {
   	  	   	  if ( $sql[0]['ent_passwd'] != $this->pass ) {
   	  	         $this->sessionHelper->createSession("userAuth", false);
   	  	         $erro = "Senha do Usuario {$this->user}, Invalida ..."; 
   	  	         $this->sessionHelper->createSession("userAuthError", $erro);   	  	   	  	
   	  	   	  } else { 
   	  	         $this->sessionHelper->createSession("userAuth", true);
   	  	         $this->sessionHelper->createSession("userData", $sql[0]);
   	  	         if ( !empty ($sql[0]['ent_dominio'] ) ) {
   	  	            $this->setCurrentDominio(ucfirst($sql[0]['ent_dominio']));
   	  	         } else {
   	  	         	$this->setCurrentDominio('Portal');
   	  	         }
   	  	         $logged = true;
   	  	         $this->sessionHelper->createSession("logado", $logged);
   	  	         
   	  	         //Logando ultimo acesso - entidade_acesso   	  	         
   	  	         $this->logEntidadeAcesso("IN");
   	  	         
   	  	         // Devo carregar dados adicionais da Entidade
   	  	         // Tipo_Entidade
   	  	         $db->_tabela_nome = "tipo_entidade";
   	  	         $_where = "tpe_id = {$sql[0]['tpe_id']}";
   	  	         $_tpe = $db->select($db->_tabela_nome,$_where,'1');
   	  	         if ( count($_tpe) > 0 ) {
   	  	         	$this->sessionHelper->createSession("TPE", $_tpe[0]);
   	  	   	     }
   	  	   	     
   	  	         // Tipo_tipo_entidade
   	  	   	     $db->_tabela_nome = "tipo_tipo_entidade";
   	  	   	     $_where = "tte_id = {$sql[0]['tte_id']}";
   	  	   	     $_tte = $db->select($db->_tabela_nome,$_where,'1');
   	  	   	     if ( count($_tte) > 0 ) {
   	  	   	     	$this->sessionHelper->createSession("TTE", $_tte[0]);
   	  	   	     }
   	  	   	     
   	  	         // Grupo_Entidade
   	  	   	     $db->_tabela_nome = "grupo_tipo_entidade";
   	  	   	     $_where = "gte_id = {$sql[0]['gte_id']}";
   	  	   	     $_gte = $db->select($db->_tabela_nome,$_where,'1');
   	  	   	     if ( count($_gte) > 0 ) {
   	  	   	     	$this->sessionHelper->createSession("GTE", $_gte[0]);
   	  	   	     }
   	  	   	     
   	  	   	     // Empresa
   	  	   	     $db->_tabela_nome = "empresa";
   	  	   	     $_where = "emp_id = {$sql[0]['emp_id']}";
   	  	   	     $_emp = $db->select($db->_tabela_nome,$_where,'1');
   	  	   	     if ( count($_emp) > 0 ) {
   	  	   	     	$this->sessionHelper->createSession("EMP", $_emp[0]);
   	  	   	     }
   	  	   	     
   	  	   	     // Filial
   	  	   	     $db->_tabela_nome = "filial";
   	  	   	     $_where = "fil_id = {$sql[0]['fil_id']}";
   	  	   	     $_fil = $db->select($db->_tabela_nome,$_where,'1');
   	  	   	     if ( count($_fil) > 0 ) {
   	  	   	     	$this->sessionHelper->createSession("FIL", $_fil[0]);
   	  	   	     }
   	  	   	     
   	  	   	     // Departamento
   	  	   	     $db->_tabela_nome = "departamento";
   	  	   	     $_where = "dep_id = {$sql[0]['dep_id']}";
   	  	   	     $_dep = $db->select($db->_tabela_nome,$_where,'1');
   	  	   	     if ( count($_dep) > 0 ) {
   	  	   	     	$this->sessionHelper->createSession("DEP", $_dep[0]);
   	  	   	     }   	  	   	     
   	  	   	  }
   	    } else { 
   	  	      $this->sessionHelper->createSession("userAuth", false);
   	  	      $erro = "Usuario {$this->user}, nao existe..."; 
   	  	      $this->sessionHelper->createSession("userAuthError", $erro);   	  		
   	    }
   	  	
   	  	echo "auth->Login: Dados-> ".$this->loginController. " Task: ".$this->loginTask. " Act: ".$this->loginAction;

	    if ($logged == true ) {
	    	$this->redirectorHelper->goToControllerTaskAction($this->loginController, $this->loginTask, $this->loginAction);
	    }
   	  	
   	  	return $this;

   	  }
   	  
   	  public function logout() {

   	  	// Logando saida do ultimo acesso - entidade_acesso   	  	         
   	  	$this->logEntidadeAcesso("OUT");
   	  	
   	  	$this->sessionHelper->deleteSession("userAuth");
   	  	$this->sessionHelper->deleteSession("userData"); 

   	  	$this->redirectorHelper->goToControllerTaskAction($this->logoutController, $this->loginTask, $this->logoutAction);

   	  	return $this;

   	  }
}
?>