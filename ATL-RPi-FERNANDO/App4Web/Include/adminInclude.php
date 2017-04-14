<?php
	//-- Navegacao
	$this->_dados['home']              = $this->_home;
	$this->_dados['path']              = $this->_path;
	$this->_dados['link']              = $this->_link;
	$this->_dados['link_modulo']       = $this->_link_modulo;
	$this->_dados['link_dominio']      = $this->_link_dominio;     // Path do Dominio
	$this->_dados['link_dominio_area'] = $this->_link_dominio_area;  // Dominio Area
	$this->_dados['path_dominio_area'] = $this->_path_dominio_area;  // Dominio Area
	$this->_dados['link_skin']         = $this->_link_skin;        // Skin do Dominio
	$this->_dados['link_webfiles']     = $this->_link_webfiles;    // Onde estao os aquivos WEB
	$this->_dados['link_system_js']    = $this->_link_system_js;   // Java Scripts nossos
	$this->_dados['dominio']           = $this->_dominio;
	$this->_dados['browser']           = $this->getBrowser();
	 
	$this->_auth = $start->_auth;
	 
	//echo "<br>Admin->Init Dados:<br>";
	//print_r($this->_dados);
	
	// Dados do Login da sessao
	if ( $this->_session->checkSession("SKIN") ) {
		$this->_dados['skin']            = $this->_session->selectSession("SKIN");
	} else {
		$this->_dados['skin']            = "Verde";
	}
	if ( $this->_session->checkSession("SKIN-ADMINLTE") ) {
		$this->_dados['skin_adminlte']   = $this->_session->selectSession("SKIN-ADMINLTE");
	} else {
		$this->_dados['skin_adminlte']   = "skin-green";
	}
	
	//echo "<script>alert('Skin: {$this->_dados['skin_adminlte']}');</script>";
	
	$this->_dados['right_sidebar']  = $this->_right_sidebar->getRightSideBar();
	$this->_dados['page_title']               = "ERP4web Portal for Businness v1.0";
	$this->_dados['page_logo']                = "ERP4<b>web</b>";
	$this->_dados['page_logo_mini']           = "Ewb";
	$this->_dados['page_header']              = "Seja bem vindo, Visitante.";
	$this->_dados['page_header_description']  = "Faça um tour em nosso ERP4web, caso desejar, escolha um pacote de seu interesse e entre em contato conosco.";
	$this->_dados['page_breadcrumb_menu']     = "Visitante";
	$this->_dados['page_breadcrumb_opcao']    = "Login";
	$this->_dados['page_breadcrumb_csstexto'] = "fa fa-lock text-red";
	$this->_dados['page_content']             = "";
	 
	$this->_dados['tpe_descr']                = "Visitante";
	$this->_dados['tte_descr']                = "Visitante";
	$this->_dados['gte_descr']                = "Visitante";
	 
	if ( $this->_session->selectSession("logado") == true ) {
		$this->_dados['login_online_csstxto']     = "text-success";
		$this->_dados['login_online']             = "On Line";
		$this->_dados['login_nome']               = "???????????";
	} else {
		$this->_dados['login_online_csstxto']     = "text-danger";
		$this->_dados['login_online']             = "Off Line";
		$this->_dados['login_nome']               = "Visitante";
	}
	$_ent_avatar = $this->_link_dominio_area."/Imagens/Profile/avatar.png";
	$this->_dados['ent_avatar'] = $_ent_avatar;
	//echo "<script>alert('Avatar {$_ent_avatar}');</script>";
	
	$_membro_desde = date('M, d');
	$this->_dados['membro_desde'] = $_membro_desde;
	 
	// Dados da empresa para o portal
	$this->_dados['copyright_esquerda'] = "<strong>Copyright &copy; 2016 <a href='#'>ERP4web</a>.</strong> Todos os direitos reservados";
	$this->_dados['copyright_direita']  = "KND Inform&#225;tica LTDA";
	// Carregando menu para a Entidade
	$this->_dados['sidebar'] = "";
	// Carregando as notificacoes para entidade
	$this->_dados['notificacao'] = "";
	// Carregando tarefas c/ progress bar :)
	$this->_dados['notificacao_task'] = "";
	// Carregando Mensagens entre Entidades Internads e Externas :)
	$this->_dados['notificacao_message'] = "";
	
	// Montando o User Account Menu
	$this->_user_account_menu->setAvatar($_ent_avatar);
	$this->_user_account_menu->setMembroDesde($_membro_desde);
	$this->_user_account_menu->setNome("Visitante");
	$this->_user_account_menu->setNomeComplemento("Seja bem vindo!!!");
	$this->_user_account_menu->setLinkDominio($this->_link_dominio);
	$this->_dados['user_account_menu'] = $this->_user_account_menu->getUserAccountMenu();
	 
	//parametros de MENU e acesso
	if ( ! isset($this->_params) ) { $this->_params['mnu'] = 0;	}
	if ( ! array_key_exists('mnu', $this->_params ) ) { $this->_params['mnu'] = 0; }
	if ( ! array_key_exists('opc', $this->_params ) ) { $this->_params['opc'] = 0; }
	if ( ! array_key_exists('mnv', $this->_params ) ) { $this->_params['mnv'] = 0; }
	if ( ! array_key_exists('prg', $this->_params ) ) { $this->_params['prg'] = 0; }
	if ( ! array_key_exists('nva', $this->_params ) ) { $this->_params['nva'] = 0; } 
    $this->_dados['mnu'] = $this->_params["mnu"];
	$this->_dados['opc'] = $this->_params["opc"];
	$this->_dados['mnv'] = $this->_params["mnv"];
	$this->_dados['prg'] = $this->_params["prg"];
	$this->_dados['nva'] = $this->_params["nva"];
	
	$this->_auth->setLoginControllerTaskAction('admin', 'login', 'auth');
	 
	$this->_auth->checkLogin('redirect');
	 
	//echo "<br>Admin Controller3";
	 
	$this->_db = new adminModel();
?>