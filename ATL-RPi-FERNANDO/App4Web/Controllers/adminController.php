<?php
   class Admin extends Controller {
	   public $_system; 

       public function init($_start) {
		   
		  //echo "<br>----------> Admin->init";
		   
          // Um INIT nao herda o anterior da classe da qual foi derivado, cada um executa apenas o seu
          //Identificacao do Tela a ser processada
		  $this->_system = $_start;
		  
          $this->_dados['tel_id']             = "0";
          $this->_dados['tel_cod']            = "admin";
          $this->_dados['tel_nome']           = "Portal ERP4web - Login";
          $this->_dados['tel_titulo']         = "Login";
         	
          // Inicializa variaveis de ADMIN
          //include("Include/adminInclude.php");
		  //print_r($_start);
		  	   
	   }	   
	   
	   public function index_action() {
	   	//echo "<br>----------> Admin->index_action";  		
	   	
		// Se ire para k vai para a pagina inicial do APP logado :)
	   	$this->_redirector->goToControllerTaskAction("index", "index", "index");

	   }

	   public function login() {
		  
		  //echo "<br>----------> Admin->login->Action: ".$this->_system->_action ;
		  
	   	  $login   = ( isset($_POST['login'])   ? $_POST['login']   : 'visitante' );
	   	  $senha   = ( isset($_POST['senha'])   ? $_POST['senha']   : '' );
	   	  $nova_senha = ( isset($_POST['nova_senha']) ? $_POST['nova_senha'] : '' );
	   	  $resenha = ( isset($_POST['resenha']) ? $_POST['resenha'] : '' );
	   	  $email   = ( isset($_POST['email'])   ? $_POST['email']   : 'visitante@erp4web.com.br' );
	   	 
	   	  // Caso nao identificar a acao de login, acionar para LOGAR automaticamente
	   	  $this->_system->_dados['login']      = $login;
	   	  $this->_system->_dados['senha']      = $senha;
	   	  $this->_system->_dados['nova_senha'] = $nova_senha;
	   	  $this->_system->_dados['resenha']    = $resenha;
	   	  $this->_system->_dados['email']      = $email;
	   	  $this->_system->_dados['login_acao'] = $this->_system->_action;
	   	  
	   	  if ( $this->_system->_action == 'logar' ) {   	  	 
	   	  	 $this->_system->_auth->setTableName('entidade');
	   	  	 $this->_system->_auth->setUserColumn('ent_login');
			 $this->_system->_auth->setPassColumn('ent_passwd');
			 $this->_system->_auth->setUser( $login )	;							
			 $this->_system->_auth->setPass( $senha );
			 $this->_system->_auth->setLoginControllerTaskAction('admin','index','index');
			 $this->_system->_auth->login();
			 if ( $this->_system->_session->selectSession("userAuthError") ) {
			 	$this->_dados['error'] = $this->_system->_session->selectSession("userAuthError");
			 }

			 // Passando dados para o formulario caso alterados 
			 $this->_system->_dados['login']      = $login;
			 $this->_system->_dados['senha']      = $senha;
			 $this->_system->_dados['nova_senha'] = $nova_senha;
			 $this->_system->_dados['resenha']    = $resenha;			 
			 $this->_system->_dados['email']      = $email;
		  }
		  //echo "<br>Dados Logado: <br>";
		  //print_r($this->_dados);		  
		  //echo "<script>alert('Controller: Acao: {$this->_action},  Login: {$login}, Senha:{$senha}, Nova:{$nova_senha}, Redigitada:{$resenha} e Mail: {$email}');</script>";
		  if ( $this->_system->_action == "reset" ) {
		  	// O solicitar_reset apenas mostra o formulario para o real RESET q pega apenas o LOGIN e apos enviar o email abre um novo form com 
		  	// novas senhas porem precisa de uma senha nossa q sera a OLD_SENHA no formulario. Confuso neh kkk tb acher mas eh isso mesmo kkk
		  	// Enviar email com nova mensagem
		  	$_ret = $this->_system->_auth->logEntidadeNovaSenha($login);
		  	if ( $_ret['ok'] == false ){
		  		// Volta para informar o Login a ser reseted :)
		  		$this->_system->_dados['error'] = $_ret['msg'];
		  	    $this->_system->_action = 'solicitar_reset';
		  	    $this->_system->_dados['login_acao'] = $this->_action;
		  	} else {
		  		// Exibe mensagem de nova senha enviada com sucesso :) espero kkk
		  	    $this->_system->_dados['error'] = $_ret['msg'];
		  	}
		  }
		  
		  if ( $this->_system->_action == 'validar_reset' ) {
		  	$_ret = $this->_system->_auth->logEntidadeSolicitacaoReset($login, $senha, $nova_senha, $resenha);
		  	if ( $_ret['ok'] == false ) {
		  		$this->_system->_dados['error'] = $_ret['msg'];
		  		$this->_system->_action = 'reset';
		  	} else {
		  		$this->_system->_dados['error'] = $_ret['msg'];
		  		$this->_system->_action = 'logar';		  		
		  	}
		  	$this->_system->_dados['login_acao'] = $this->_system->_action;
		  }
		  
		  if ( $this->_system->_action == 'redefinir' ) {	
		  	$this->_system->_dados['error'] = "Senha Sera alterada, Obrigado!!!";
		  	$this->_system->_action = 'logar';
		  	$this->_system->_dados['login_acao'] = $this->_system->_action;
		  }
		  
		  // Entrando pelo botao vermelho kk da outra forma enviava email novamente
		  if ( $this->_system->_action == "forca_reset" ) {
		  	  $this->_system->_dados['error'] = "Não esquecer de usar sua senha nova enviada por email para essa operação.<br>Obrigado!!!";
		  	  $this->_system->_action = 'reset';
		  	  $this->_system->_dados['login_acao'] = $this->_action;
		  }
		  			
		  if ( $this->_system->_action == 'solicitar' ) {	
		  	
		  	// Solicitando registro para o usuario
		  	$_ret = $this->_system->_auth->logEntidadeSolicitacaoAcesso($login, $senha, $email);
		  	
		  	if ($_ret['ok'] == false ) {
		  		// Solicitacao nao registrada, com problemas
		  		$this->_system->_dados['error'] = $_ret['msg'];
		  	} else {
		  		// Solicitacao registrada com sucesso
		  		$this->_system->_dados['error'] = $_ret['msg'];
		  		$this->_system->_action = 'logar';
		  		$this->_system->_dados['login_acao'] = $this->_system->_action;
		  	}
		  	
		  }
		  
		  //echo "<script>alert('Antes Login-acao: ".$this->_system->_action."');</script>";
		  
		  $this->view('login',$this->_system->_dados);
	   }

	   public function logout() {
	   	  
		  //echo "<br>----------> Index->logout";
		  
	   	  $this->_system->_session->createSession("logado", false);
	   	
	   	  $this->_system->_auth->setLogoutControllerTaskAction('admin','logout','auth');
	   	  
	   	  $this->_system->_dados = null;	 
	   	  	   	  
	   	  $this->_system->_auth->logout();
	   }
   }
?>