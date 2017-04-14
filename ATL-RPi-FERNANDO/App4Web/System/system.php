<?php
class System{
	  private $_url;
	  
	  public $_home;
	  public $_path;
	  public $_server;
	  public $_link;
	  
	  private $_explode_url;
	  private $_expode_link;

      public $_ambiente;	  
	  public $_script;
	  public $_dominio;
	  public $_controller;	  
	  public $_action;
	  public $_task;
	  public $_params;	  	  
	  
	  public $_browser;
	  
	  public $_session;
	  
	  public $_redirector;

	  public $_auth;
	  
	  public $_debug;
	  
	  public $_dados;
	 
	  public function __construct() {
		  		 		 
         //echo "<br>----[".$this->_controller." ]------> system->__construct onde http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];						 
		 
		 $this->setUrl();
		 
         $this->setExplode();	
		 
		 $this->_browser = $this->getBrowser();
		 
		 $this->setAmbiente();
		 
		 $this->setScript();
		 
		 $this->setDominio();
		 
		 $this->setController();
		 
		 $this->setTask();
		 
		 $this->setAction();
		 
		 $this->setParams();
		 
		 $this->_session = new sessionHelper();

		 $this->_redirector = new redirectorHelper();
		
         $this->_dados = "";
			 
		 // Home directory
		 $this->_home = $_SERVER['DOCUMENT_ROOT'].'/' . $this->_ambiente . '/';			  
	  	 $this->_session->createSession("PORTAL_HOME",$this->_home);
		
		 // Server - URL completa ate o Ambiente
	     $this->_server = "http://".$_SERVER['SERVER_NAME'] . '/' . $this->_ambiente . '/';
         $this->_session->createSession("PORTAL_SERVER",$this->_server);
		 
		 // Path
	  	 $this->_path = $this->_home;
		 $this->_session->createSession("PORTAL_PATH",$this->_path);
		 $this->_session->createSession("PORTAL_DOMINIO_PATH",$this->_path . $this->_dominio."/");
	  	
	  	 // Link
	  	 $this->_link = $this->getLink();
		 $this->_session->createSession("PORTAL_LINK",$this->_link);
		 $this->_session->createSession("PORTAL_DOMINIO_LINK",$this->_link . $this->_dominio."/");
	 
		 // Determinando as variaveis do PORTAL ( TOKENS )
         $this->_session->createSession("SERVER"         , $_SERVER['HTTP_HOST']);
		 $this->_session->createSession("HOST"           , $_SERVER['HTTP_HOST']);
		 $this->_session->createSession("LINK"           , $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
		 $this->_session->createSession("SCRIPT"         , "NONE");
		 $this->_session->createSession("URL"            , 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
		 $this->_session->createSession("URL_PARAM"      , $this->_url);
		 $this->_session->createSession("URL_HASH"       , "#NONE");
		 $this->_session->createSession("URL_HASH_ACAO"  , "NONE");
		 $this->_session->createSession("URL_AMBIENTE"   , "NONE");
		 $this->_session->createSession("URL_DOMINIO"    , "NONE");
		 $this->_session->createSession("URL_CONTROLLER" , "NONE");
		 $this->_session->createSession("URL_TASK"       , "NONE");
		 $this->_session->createSession("URL_TAREFA"     , "NONE");
		 $this->_session->createSession("URL_ACTION"     , "NONE");
		 $this->_session->createSession("URL_ACAO"       , "NONE");
		 $this->_session->createSession("URL_PARAMS"     , "NONE");
        
		 // 1- Ambiente
		 $this->_session->changeSession("URL_AMBIENTE",  $this->_ambiente);
		 
         // 2- Script
         $this->_session->changeSession("URL_SCRIPT",    $this->_script);
  
         // 3 - Dominio
         $this->_session->changeSession("URL_DOMINIO",   $this->_dominio);
		 
         // 4 - Controller
         $this->_session->changeSession("URL_CONTROLLER",$this->_controller);
         
		 // 5 - Task
         $this->_session->changeSession("URL_TASK",      $this->_task);
         $this->_session->changeSession("URL_TAREFA",    $this->_session->selectSession("URL_TASK"));
         
		 // 6 - Action
         $this->_session->changeSession("URL_ACTION",    $this->_action);
         $this->_session->changeSession("URL_ACAO",      $this->_session->selectSession("URL_ACTION"));
		 
		 // parametros
		 $this->_session->createSession("URL_PARAMS", $this->_params);
	  	  	
	  	// Determinano o SKIN se nao achar forca o Padrao
	  	if ( $this->_session->selectSession("DOM_SKIN") == false ) {
	  	    $this->_session->createSession("DOM_SKIN", "Padrao");
	  	}
	  	$_skin = $this->_session->selectSession("DOM_SKIN");
	  	
	  	// Pasta do Portal, onde comeca
	  	$this->_session->createSession("PORTAL_FOLDER","/{$this->_ambiente}/");
	  	
	  	// Depois de tudo carregado, determinando alguns PATHH/LINK
	  	$this->_session->createSession("DOM_PATH", $this->getPathDominio());
	  	$this->_session->createSession("DOM_LINK", $this->getLinkDominio());
	  	$this->_session->createSession("DOM_AREA_PATH", $this->getPathDominioArea());
	  	$this->_session->createSession("DOM_AREA_LINK", $this->getLinkDominioArea());
	  	
	  	// Skin do Dominio
	  	$this->_session->createSession("DOM_SKIN_PATH", $this->_path . "Skin/{$_skin}/");
	  	$this->_session->createSession("DOM_SKIN_LINK", $this->_link . "Skin/{$_skin}/");
	  	
	  	// Onde estao os aquivos WEB
	  	$this->_session->createSession("PORTAL_WEBFILES_PATH", $this->_path . "Web-files/");
	  	$this->_session->createSession("PORTAL_WEBFILES_LINK", $this->_link . "Web-files/");
	  	
	  	// Java Scripts Nossos
	  	$this->_session->createSession("PORTAL_JS_SCRIPTS_PATH", $this->_path . "System/Js/");
	  	$this->_session->createSession("PORTAL_JS_SCRIPTS_LINK", $this->_link . "System/Js/");
		
		// Controller Link/Path
	  	$this->_session->createSession("PORTAL_CONTROLLERS_PATH", $this->_path . "Controllers/");
	  	$this->_session->createSession("PORTAL_CONTROLLERS_LINK", $this->_link . "Controllers/");
		
		// Views Link
		$this->_session->createSession("PORTAL_VIEWS_PATH", $this->_path . "Views/");
	  	$this->_session->createSession("PORTAL_VIEWS_LINK", $this->_link . "Views/");
		
        // Config File Portal - Carregando o config do portal e depois sobrepoe os dados especificos do Dominio selecionado
        $this->_session->createSession("PORTAL_CONFIG","config.php");
		
		// Config para o Dominio
		$config_file = $this->_session->selectSession("DOM_AREA_PATH"). "config.php";
        $this->_session->createSession("DOM_CONFIG", $config_file);		
		
		// AJAX Path and Link
		$this->_session->createSession("PORTAL_AJAX_PATH", $_SESSION["PORTAL_PATH"] ."Ajax/" );
		$this->_session->createSession("PORTAL_AJAX_LINK", $_SESSION["PORTAL_LINK"] ."Ajax/" );
		$this->_session->createSession("PORTAL_DOMINIO_AJAX_LINK", $_SESSION["PORTAL_DOMINIO_LINK"] ."Ajax/" );

		// JSON Path and Link
		$this->_session->createSession("PORTAL_JSON_PATH", $_SESSION["PORTAL_PATH"] ."JSON/" );
		$this->_session->createSession("PORTAL_JSON_LINK", $_SESSION["PORTAL_LINK"] ."JSON/" );
		
		// Include Path and Link
		$this->_session->createSession("PORTAL_INCLUDE_PATH", $_SESSION["PORTAL_PATH"] ."Include/" );
		$this->_session->createSession("PORTAL_INCLUDE_LINK", $_SESSION["PORTAL_LINK"] ."Include/" );
		
		// Dialogs Path and Link
		$this->_session->createSession("PORTAL_DIALOG_PATH", $_SESSION["PORTAL_PATH"] ."Include/Dialogs/" );
		$this->_session->createSession("PORTAL_DIALOG_LINK", $_SESSION["PORTAL_LINK"] ."Include/Dialogs/" );		

		// Dialogs Path and Link
		$this->_session->createSession("PORTAL_LIB_PATH", $_SESSION["PORTAL_PATH"] ."System/Lib/" );
		$this->_session->createSession("PORTAL_LIB_LINK", $_SESSION["PORTAL_LINK"] ."System/Lib/" );		
		
	    // Indicando o Ambiente que estamos para o Redirection saber para onde ir :)
		$this->_redirector->init($this);

		$this->_auth = new authHelper();
		 
		$this->_debug = new debugHelper();
	  }
	  
	  private function setUrl() {  	

	     // Se nao passou nada, encaminha como visitante
		 $_GET['url'] = ( isset($_GET['url']) ? $_GET['url'] : 'Portal/index/index/index' );
		 
		 $this->_url = $_GET['url'];

	  }
	  
	  private function setExplode() {
		  
	  	  $aux_url = $this->_url;	
		  
		  $aux_link = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];		  

		  //echo "<br>----------> system->setExplode url: {$aux_url}";
		  
		  //echo "<br>----------> system->setExplode link: {$aux_link}";
		  
		  $this->_explode_url = explode('/', $aux_url);
		  
		  $this->_explode_link = explode('/', $aux_link);
		  
	  }

	  private function setAmbiente() {
	  	$am = (!isset($this->_explode_link[1]) || $this->_explode_link[1] == null || ucfirst($this->_explode_link[1]) == "Index" ? "App4Web" : ucFirst($this->_explode_link[1]));
	  	   	 
	  	$this->_ambiente = $am;
		
		//echo "<br>----------> Ambiente: ".$am;
	  }

	  private function setScript() {
	  	$sc = (!isset($this->_explode_link[2]) || $this->_explode_link[2] == null || $this->_explode_link[2] == "index_action" ? "index.php" : $this->_explode_link[2]);
	  	   	 
	  	$this->_script = $sc;
		
		//echo "<br>----------> Script: ".$sc;
	  }
	  
	  private function setDominio() {
	  	$do = (!isset($this->_explode_url[0]) || $this->_explode_url[0] == null || ucfirst($this->_explode_url[0]) == "Visitante" ? "Portal" : ucFirst($this->_explode_url[0]));
	  	   	 
	  	$this->_dominio = $do;
		
		//echo "<br>----------> Dominio: ".$do;
	  }
	  
	  private function setController() {
		  
   		  $co = (!isset($this->_explode_url[1]) || $this->_explode_url[1] == null || $this->_explode_url[1] == "index" ? "index" : $this->_explode_url[1]);

		  $this->_controller = $co;
		  
		  //echo "<br>----------> Controller: ".$co;
	  }	  

	  private function setTask() {
	  	$tk = (!isset($this->_explode_url[2]) || $this->_explode_url[2] == null || $this->_explode_url[2] == "index" ? "index_action" : $this->_explode_url[2]);
	  	 
	  	$this->_task = $tk;
		
		//echo "<br>----------> Task: ".$tk;
	  }
	  
	  private function setAction() { 	
		  $ac = (!isset($this->_explode_url[3]) || $this->_explode_url[3] == null || $this->_explode_url[3] == "index" ? "index" : $this->_explode_url[3]);
		  
		  $this->_action = $ac;
		  
		  //echo "<br>----------> Action: ".$ac;
	  }	  
	  
	  function montaUrl($_controller, $_task, $_action, $_param) {
		  
		  $_ret = $this->_link . $this->_dominio . "/" . $_controller . "/" . $_task . "/" . $_action . "/". $_param;
		  
		  return $_ret;
		  
	  }
	  
	  private function setParams() {
		  // Liberando as primeiras posicoes com o Dominio, Controller, Task e Action :)
		  unset ($this->_explode_url[0], $this->_explode_url[1], $this->_explode_url[2], $this->_explode_url[3]);
		  
		  //echo "<br>---------> Url: ".$this->_url;
		  //echo "<br>---------> Url Completa: ". $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];	
          //echo "<br>Ecplodex Url:";
		  //print_r($this->_explode_url);
		  
		  if ( end( $this->_explode_url ) == null ) 
			  // tirando o ultimo elemento do array se vazio
			  array_pop( $this->_explode_url );		 
		  
		  $i=0;
		  $ind = array();
		  $value = array();
		  $arr = array();
		  	 
		  if (!empty($this->_explode_url) ) {
			
		  	$key = "";		
			foreach ( $this->_explode_url as $val ) {
				
				// Convertendo BARRAS quando precisam ser barras mesmo e nao delimitador
				$val = str_replace("@BARRA@","/",$val);
				$val = str_replace("@JVELHA@","#",$val);
										
				if ( $i % 2 == 0 ) {
					// Par
					$ind[] = $val;
					$key = $val;
				} else {
					//Impar
					if ( isset( $arr[$key]) ) {
					  // Ja esta na pilha
					  if ( is_array($arr[$key]) ) {
					     array_push($arr[$key], $val);
					  } else {
					  	$aux = array();
					  	array_push($aux, $arr[$key]);
					  	array_push($aux, $val);
					  	$arr[$key] = $aux;
					  }
					} else {
					  $arr[$key] = $val;						
					}
					$value[] = $val;					
				}	
                $i++;				
			}  
	      }

		  //echo "<br>----------> Parans:";
	      if ( count($arr) > 0 ) {
		  	
			  //$this->_params = array_combine($ind, $value);
		  	  $this->_params = $arr;
		    
			  // Transformando em GET
		      foreach ( $this->_params as $key => $val ) {
		      	//echo "<br> Key ->: {$key} = {$val}";
	      		$_GET[$key] = $val;		      			      	
		      }		      
		  } else {
			  $this->_params = array();
		  }
		  
		  // Forca a ACAO para todos
		  $_GET['ACAO'] = strtoupper($this->_action);
		  $_GET['acao'] = strtoupper($this->_action);
	  }
	  
	  public function getParam( $name = null ) {
		  		  
		  if ( $name != null ) {
		  	  if ( array_key_exists($name, $this->_params) ) {
		  	  	return $this->_params[$name];
		  	  } else { 
		  	  	return false;
		  	  }
		  } else {
			 return $this->_params;
		  }
	  }
	  
	  public function getLink() {
	  	// Prepara a base para o Link de navegacao dentro do Portal ou da area do Cliente
	  	// No fundo eh a mesma mas com identificacao do Dominio
  	   return $this->_server;
	  }

	  public function getPathDominio() {
	  	// Prepara path para carregar arquivos abaio da estrutura do Dominio
	  	return "{$this->_home}Dominio/{$this->_dominio}/";
	  }
	  
	  public function getPathDominioArea() {
	  	// Prepara path para carregar arquivos abaio da estrutura do Dominio
	  	return "{$this->_home}Dominio/{$this->_dominio}/";
	  }	  
	  
	  public function getLinkDominio() {
	  	// Prepara path para carregar arquivos abaio da estrutura do Dominio
	  	return "{$this->_server}{$this->_dominio}/";
	  }

	  public function getLinkDominioArea() {
	  	// Prepara path para carregar arquivos abaio da estrutura do Dominio
	  	return "{$this->_server}Dominio/{$this->_dominio}/";
	  }
	  
	  public function execute() {
	  	  
		  //echo "<br>--ANTES-EXECUTE---------------------------------> system->execute onde http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];						 
		  //echo "<br>------------------------------------------------[".$this->_controller." ]-------> ";		  
		  //echo "<br>------------------------------------------------[".$this->_task." ]-------> ";						 
		  //echo "<br>------------------------------------------------[".$this->_action." ]-------> ";						 
		  
	  	  $controller_path = "";
	  	  if ( ucfirst($this->_task) == "Ajax" ) {
			 if ( ucfirst($this->_controller) == "Ajax" ) {
				$controller_path = CONTROLLERS . $this->_task . "Controller.php";  // ajaxController.php
			 } else {
	  	  	    $controller_path = AJAX . $this->_controller.".php"; // comboBoxArmazemAJAX.php
			 }
	  	  } else {
			 //if ( ucfirst($this->_task) == "Index_action" ) {				 
				//$this->_task = $this->_controller;
				//$this->_controller = "index";
				//$this->action = "view";
				$controller_path = CONTROLLERS . $this->_controller . "Controller.php";
			 //} else {
			$controller_path = CONTROLLERS . $this->_controller . "Controller.php"; 
	  	  }
		  
		  //echo "<br>--DEPOIS--EXECUTE-------------------------------> system->execute onde http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];						 
		  //echo "<br>------------------------------------------------[".$this->_controller." ]-------> ";		  
		  //echo "<br>------------------------------------------------[".$this->_task." ]-------> ";						 
		  //echo "<br>------------------------------------------------[".$this->_action." ]-------> ";						 
		  
		  if ( ! file_exists( $controller_path )) 
			  die ("<br>Nao existe Controller: $controller_path");

		  //echo "<br>---------> controller: {$controller_path}";
		  
		  // cARREGA O ARQUIVO COM O cONSTRUCTOS SOLICITADO
		  require_once( $controller_path );
		  
		  // Cria o OBJETO conforme o contEudo da variael
		  $app = new $this->_controller();
		  
		  // Nesse momento temos que ver como esta a sessao e redirecionar para fazer o LOGIN
	   	  //-- Verificando se fez Login
	   	  $logado = "N";				 
		
		  //echo "<br>---------> Verificando sessao true: ".Logged();
		
	   	  if ( $this->_session->checkSession('LOGGED')) {
			
	   		 $logado = $this->_session->selectSession('LOGGED');
			 
			 //echo "<br>---------> Verificando sessao true";
			
	   		 if ($logado == "S") {
				
	   			//-- Preparando dados para a View
	   			//-- Login
	   			$this->_dados['login_id']     = $this->_auth->userData("ent_id");
	   			$this->_dados['login_nome']   = $this->_auth->userData("ent_nome");
	   			$this->_dados['login_apelido']= $this->_auth->userData("ent_apelido");
	   			$this->_dados['login']        = $this->_auth->userData("ent_login");
	   			$this->_dados['login_passwd'] = $this->_auth->userData("ent_passwd");
	   			$this->_dados['login_webkey'] = $this->_auth->userData("ent_webkey");
				
	   			// Dados do login para controle de busca
	   			$this->_dados['login_ent_id'] = $this->_auth->userData("ent_id");
	   			$this->_dados['login_prv_id'] = $this->_auth->userData("prv_id");
	   			$this->_dados['login_org_id'] = $this->_auth->userData("org_id");
	   			$this->_dados['login_ges_id'] = $this->_auth->userData("ges_id");
	   			$this->_dados['login_emp_id'] = $this->_auth->userData("emp_id");
	   			$this->_dados['login_fil_id'] = $this->_auth->userData("fil_id");
	   			$this->_dados['login_dep_id'] = $this->_auth->userData("dep_id");
				
				// Icone para login
				$this->_dados['login_icon']   = $this->_auth->userData("ent_csstexto");
				if ( empty($this->_dados['login_icon']) ) {
				   $this->_dados['login_icon'] = "fa-user";
				}				
	   		 }
	   	  }
		
		  // Forca o Logout
		  if ( ( ucfirst($this->_controller) == "Admin" && ucfirst($this->_task) == "Login" ) || 
		       ( ucfirst($this->_controller) == "Admin" && ucfirst($this->_task) == "Logout" ) ) {
		     $logado = "N";
			 $this->_session->changeSession('LOGGED', $logado);
		  }		
		
	   	  // Verifica se precisa Redirecionar pagina ?
		  // Aqui evitamos a chamada infinita do ADMIN paravalidar o LOGIN		  
		  //echo "<br>---------> Logado: ". $logado." Qual controller: ".ucfirst($this->_controller);
		  
		  // Entrar se estiver BLOQUEADO ou NAO ESTIVER LOGADO
	   	  if ( $logado == "N" && ucfirst($this->_controller) != "Admin" ) {
	   		 // Precisa redirecionar para autenticacao	
             //echo "<br>----------> System->redirecionando";
			 
			 // Se for Ajax e Tarefa Login, permite a execucao logo abaixo e nao faz esse redirecionamento
			 if ( ucfirst($this->_controller) != "Ajax" || ucfirst($this->_task) != "Login" ) {
				//echo "<br>----------> System->Redirecionar: Controller: ". ucfirst($this->_controller) ." Task: ". ucfirst($this->_task);	   	        
			    // Nao esta logado
				$this->_redirector->goToControllerTaskAction("admin", "login", "validar");
			    return $this;
			 }
			 //echo "<br>----------> System->Liberado: Controller: ". ucfirst($this->_controller) ." Task: ". ucfirst($this->_task);
	   	  } 
		  
		  // Verificando se nao esta bloqueado
		  //echo "<br>---------> Logado esta : " . Logged();
		  //echo "<br>---------> Local logado esta: ". $logado;
		  if ( $logado == "B" ) {
			 // Esta bloqueado
			 if ( ucfirst($this->_controller) != "Index" || ucfirst($this->_task) != "Lock" ) {	            
                if ( ucfirst($this->_controller) != "Ajax" || ucfirst($this->_task) != "Login" ) {
				   //echo "<br>----------> System->Redirecionar: Controller: ". ucfirst($this->_controller) ." Task: ". ucfirst($this->_task);	   	        
			       // Nao esta logado
				   $this->_redirector->goToControllerTaskAction("index", "lock", "validar");
			       return $this;
			    }				
		    }
		  }
		  		  
		  //echo "<br>----------> System->Controller: ". ucfirst($this->_controller) ." Task: ". ucfirst($this->_task);
		  //echo "<br>---------> Url: ".$this->_url;
		  //echo "<br>---------> Url Completa: ". $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];	
		  
		  // Ao instanciar um novo controller, precisa pasar as credenciais do antigo para esse		  
		  if ( !method_exists($app,$this->_task) )
			 die ("<br>houve um erro URL:[ $this->_url ] e TASK, [ $this->_task ] nao existe");
		        
		  $task = $this->_task;
		 
		  $app->init($this);
		  
		  $app->$task();
	  }
	  
	  function paramToDados() {
		  
		 // Descarrega o conteudo de PARAMS em DADOS.
		 // Indiretamente sera convertido em $_view[key] para usar no .phtml
		 //echo"<br>------------------------------------------ parametros";
		 foreach ( $this->_params as $key => $val ) {
			//echo "<br>------------------------------------------------------> Key: {$key}, Valor: {$val}";
			$this->_dados[$key] = $val;
		 }
		 
		 // Para iniciar uma pagina de dados, deixar no modo NAV caso nao esteja identificada como parametro
		 if ( ! array_key_exists("nav_modo", $this->_dados) ) {
		    $this->_dados["nav_modo"] = "NAV";
		 }
		 
		 if ( ! array_key_exists("guia", $this->_dados) ) {
		    $this->_dados["guia"] = "BAS";
		 }

		 if ( ! array_key_exists("guia_corrente", $this->_dados) ) {
		    $this->_dados["guia_corrente"] = "BAS";
		 }
		 
		 if ( ! array_key_exists("guia_lock", $this->_dados) ) {
		    $this->_dados["guia_lock"] = "BAS";
		 }		 
		 
	  }
	  
	  function loadToDados($_dados) {
		  
		 // Descarrega o conteudo de PARAMS em DADOS.
		 // Indiretamente sera convertido em $_view[key] para usar no .phtml
		 foreach ( $_dados as $key => $val ) {
			$this->_dados[$key] = $val;
		 }		 		 
	  }	  
	   
	  function getBrowser() {
	  	$useragent = $_SERVER['HTTP_USER_AGENT'];
	  
	  	//echo "System->getBorwser: UserAgent: ".$useragent;
	  
	  	if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
	  		$browser_version=$matched[1];
	  		$browser = 'IE';
	  	} elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
	  		$browser_version=$matched[1];
	  		$browser = 'Opera';
	  	} elseif (preg_match( '|OPR/|',$useragent,$matched)) {
	  		//print_r($matched);
	  	    $browser_version="N/D"; //$matched[1];
	  		$browser = 'Opera';
	  	} elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
	  		$browser_version=$matched[1];
	  		$browser = 'Firefox';
	    } elseif (preg_match( '|Edge/|',$useragent,$matched)) {
	  		$browser_version= "N/D"; //$matched[0];
	  		$browser = 'Edge';	  		
	  	} elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
	  		$browser_version=$matched[1];
	  		$browser = 'Chrome';
	  	} elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
	  		$browser_version=$matched[1];
	  		$browser = 'Safari';
	  	} elseif (preg_match( '|Windows|',$useragent,$matched)) {
	  		$browser_version= "N/D"; //$matched[1];
	  		$browser = 'IE';
	  	} else {
	  		// browser not recognized!
	  		$browser_version = 0;
	  		$browser= 'Other';
	  	}
	  
	  	return  $browser;
	  
	  }	  
}
?>