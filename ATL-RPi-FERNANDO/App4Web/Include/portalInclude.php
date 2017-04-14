<?php
    $_db = new Model();
    
	//-- Passado para a View
	$this->_system->_dados['modulo']                   = $MODULO;
	$this->_system->_dados['link_modulo']              = $LINK_MODULO;
	
    //AFV $this->_system->_dados['right_sidebar']            = $this->_right_sidebar->getRightSideBar();
	
	$this->_system->_dados['page_header']              = $this->_system->_dados['tel_titulo'];
	$this->_system->_dados['page_header_description']  = "descricao resumida do que a pagina faz :)";
	 
	$this->_system->_dados['page_title']               = ucfirst($this->_system->_auth->userData("ent_dominio"))." - web Portal for Businness v1.0";
	$this->_system->_dados['page_logo']                = $this->_system->_auth->userData("ent_dominio");
	$this->_system->_dados['page_logo_mini']           = $this->_system->_auth->userData("ent_dominio");
	 
	$this->_system->_dados['page_breadcrumb_menu']     = $MODULO;
	$this->_system->_dados['page_breadcrumb_opcao']    = $this->_system->_task;
	$this->_system->_dados['page_breadcrumb_csstexto'] = "fa fa-dashboard text-green";
	$this->_system->_dados['page_content']             = "";
	if ( $this->_system->_session->selectSession("logado") == true ) {
		$this->_system->_dados['login_online_csstxto']     = "text-success";
		$this->_system->_dados['login_online']             = "On Line";
	} else {
		$this->_system->_dados['login_online_csstxto']     = "text-danger";
		$this->_system->_dados['login_online']             = "Off Line";
	}
	
	// Esta logado ?
	//echo "<script>alert('Xi1: ".$this->_system->_session->checkSession("LOGGED")." Xi2: ".$this->_system->_session->selectSession("LOGGED")."');</script>";

	// Dados do Login da sessao
	if ( $this->_system->_session->checkSession("SKIN") ) {
		$this->_system->_dados['skin']            = $this->_system->_session->selectSession("SKIN");
	} else {
		$this->_system->_dados['skin']            = "Verde";
	}
	if ( $this->_system->_session->checkSession("SKIN-ADMINLTE") ) {
		$this->_system->_dados['skin_adminlte']   = $this->_system->_session->selectSession("SKIN-ADMINLTE");
	} else {
		$this->_system->_dados['skin_adminlte']   = "skin-green";
	}
	
	//echo "<script>alert('Skin: {$this->_system->_dados['skin_adminlte']}');</script>";
	
	//Sessao foi iniciada
	if ($this->_system->_session->checkSession("LOGGED") == true ) {

		// Esta logado
		if ($this->_system->_session->selectSession("LOGGED") == "S" ) {
			// Carregando dados da Entidade
			$this->_system->_dados['ent_id']           = $this->_system->_auth->userData("ent_id");
			$this->_system->_dados['ent_nome']         = $this->_system->_auth->userData("ent_nome");
			$this->_system->_dados['ent_nome_curto']   = $this->_system->_auth->userData("ent_nome_curto");
			$this->_system->_dados['ent_apelido']      = $this->_system->_auth->userData("ent_apelido");
			$this->_system->_dados['ent_dominio']      = $this->_system->_auth->userData("ent_dominio");
			
			// Dados da Entidade: Empresa/Filial e Departamento
			$this->_system->_dados['emp_id']           = $this->_system->_auth->userData("emp_id");
			$_EMP = $this->_system->_session->selectSession("EMP");
			if ( $_EMP == false ) {
				$this->_system->_dados['emp_nome']       = "";
				$this->_system->_dados['emp_nome_curto'] = "";
				$this->_system->_dados['emp_apelido']    = "";
			} else {
				$this->_system->_dados['emp_nome']       = $_EMP['emp_nome'];
				$this->_system->_dados['emp_nome_curto'] = $_EMP['emp_nome_curto'];
				$this->_system->_dados['emp_apelido']    = $_EMP['emp_apelido'];
			}
			// Filial
			$this->_system->_dados['fil_id']           = $this->_system->_auth->userData("fil_id");
			$_FIL = $this->_system->_session->selectSession("FIL");
			if ( $_FIL == false ) {
				$this->_system->_dados['fil_nome']       = "";
				$this->_system->_dados['fil_nome_curto'] = "";
				$this->_system->_dados['fil_apelido']    = "";
			} else {
				$this->_system->_dados['fil_nome']       = $_FIL['fil_nome'];
				$this->_system->_dados['fil_nome_curto'] = $_FIL['fil_nome_curto'];
				$this->_system->_dados['fil_apelido']    = $_FIL['fil_apelido'];
			}
			// Departamento
			$this->_system->_dados['dep_id']             = $this->_system->_auth->userData("dep_id");
			$_DEP = $this->_system->_session->selectSession("DEP");
			if ( $_DEP == false ) {
				$this->_system->_dados['dep_nome']       = "";
				$this->_system->_dados['dep_nome_curto'] = "";
				$this->_system->_dados['dep_apelido']    = "";
			} else {
				$this->_system->_dados['dep_nome']       = $_DEP['dep_nome'];
				$this->_system->_dados['dep_nome_curto'] = $_DEP['dep_nome_curto'];
				$this->_system->_dados['dep_apelido']    = $_DEP['dep_apelido'];
			}
			
			// Identificando pelo CONTROLLER o programa - carregado na authHelper
			// Programa referente ao Controller
			$_db->_tabela_nome = "programa";
			$_where = "prg_cod = '".strtoupper($this->_system->_controller)."'";
			//echo "------------------------------------------------------> {$_where}";
			//$_prg = $_db->read($_where);
			$_prg = $_db->select( "programa", $_where, 1 );
			if ( count($_prg) <= 0 ) {
				$_prg[0]['prg_cod']        = strtoupper($this->_system->_controller);
				$_descricao = strtoupper($this->_system->_controller);
				if ( $_prg[0]['prg_cod'] == "CADASTRO" ) {
					$_descricao = $this->_system->_dados['tel_breadcrumb'];
				} elseif ($_prg[0]['prg_cod'] == "INDEX" ) {
					$_descricao = "Menu";
				}
				// Atribuindo a descricao para aparecer na tela
				$_prg[0]['prg_descr']      = $_descricao;
				$_prg[0]['prg_nome']       = $_descricao;
				$_prg[0]['prg_nome_curto'] = $_descricao;
				$_prg[0]['prg_apelido']    = $_descricao;

			}
			// ver como por na sessao essas informacoes
			$this->_system->_session->createSession("PRG", $_prg[0]);
			$this->_system->_dados['prg_cod']        = $_prg[0]['prg_cod'];
			$this->_system->_dados['prg_descr']      = $_prg[0]['prg_descr'];
			$this->_system->_dados['prg_nome']       = $_prg[0]['prg_descr'];
			$this->_system->_dados['prg_nome_curto'] = $_prg[0]['prg_descr'];
			$this->_system->_dados['prg_apelido']    = $_prg[0]['prg_descr'];
			
			// re-Montando o BreadCrumb			
			$_BREADCRUMB = "<a href='#' data-toggle='tooltip' data-placement='bottom' title='Empresa'>" . $this->_system->_dados['emp_nome'] . "</a>" .
			               " > ".
			               "<a href='#' data-toggle='tooltip' data-placement='bottom' title='Filial'>"  . $this->_system->_dados['fil_nome_curto'] . "</a>" .  
			               " > " . 
			               "<a href='#' data-toggle='tooltip' data-placement='bottom' title='Departamento'>" . $this->_system->_dados['dep_nome_curto'] . "</a>" . 
			               " > " . 
			               "<a href='#' data-toggle='tooltip' data-placement='bottom' title='Menu/Opção'>" . $this->_system->_dados['prg_nome_curto'] . "</a>";
			$this->_system->_dados['page_breadcrumb_menu']  = $_BREADCRUMB;
			 
			$this->_system->_dados['tpe_descr']        = $this->_system->_auth->userTPE("tpe_descr");
			$this->_system->_dados['tte_descr']        = $this->_system->_auth->userTTE("tte_descr");
			$this->_system->_dados['gte_descr']        = $this->_system->_auth->userGTE("gte_descr");			
				
			// Determinando o AVATAR do usuario logado :)
			$_ent_avatar = $this->_system->_auth->userData('ent_foto_140x185');
			if ( empty($_ent_avatar) ) {
				$_ent_avatar = "NONE";
			}
			if ( $_ent_avatar == "NONE" ) {
				// Obrigatorio ter um avatar para o tipo de entidade
				// Kiko veja ai como fazer kkk :)
				$_ent_avatar = $this->_system->_auth->userTPE('tpe_foto');
			}
			$_ent_dominio = $this->_system->_auth->userData('ent_dominio');
			$_ent_avatar = $_ent_avatar; //$this->_link_dominio_area."Imagens/Profile/".$_ent_avatar;
			$this->_system->_dados['ent_avatar'] = $_ent_avatar;
				
			$_ent_dtmreg = strtotime($this->_system->_auth->userData("ent_dtmreg"));
			$_membro_desde = date('M, d', $_ent_dtmreg);
			$this->_system->_dados['membro_desde'] = $_membro_desde;
			 
			// Dados da empresa para o portal
			$this->_system->_dados['copyright_esquerda'] = "<strong>Copyright &copy; 2016 <a href='#'>ERP4web</a>.</strong> Todos os direitos reservados";
			$this->_system->_dados['copyright_direita']  = "KND Inform&#225;tica LTDA";
			 
			// Identificando parametros de execucao
		    // Menu
			$_mnu = 0;
			if (array_key_exists('mnu',$this->_system->_params) ) {
				$_mnu = $this->_system->_params["mnu"];
			}

			// Nivel do Menu
			$_menu_nivel = 0;
			if (array_key_exists('mnv',$this->_system->_params) ) {
				$_menu_nivel = $this->_system->_params["mnv"];
			}
			// Opcao selecioada
			$_opcao_selecionada = 0;
			$_opc = 0;
			if (array_key_exists('opc',$this->_system->_params) ) {
				$_opcao_selecionada = $this->_system->_params["opc"];
				$_opc = $this->_system->_params["opc"];
			}
		    // Programa
			$_prg = 0;
			if (array_key_exists('prg',$this->_system->_params) ) {
				$_prg = $this->_system->_params["prg"];
			}
			
			// Carregando menu para a Entidade
			//echo "<script>alert('Menu Nivel:{$_menu_nivel} e Opcao: {$_opcao_selecionada}');</script>";

			
			//echo "<script>alert('Action: {$this->_action}, Menu:{$_mnu}, Opcao:{$_opc} e Programa:{$_prg}');</script>";
			
			//echo "Parametros:<br>";
			//print_r($this->_system->_params);
			//echo "<br>------------------- Dominio ---> {$this->_dominio}";
			//echo "<br>------------------- Controller ---> {$this->_controller}";
			//echo "<br>------------------- Task ---> {$this->_task}";
			//echo "<br>------------------- Action ---> {$this->_action}";
			
			
			//$this->_menu->setOpcaoLink($this->_link_dominio);
			//$this->_menu->loadMenuEntidade($this->_action, "ERP4web Menu", $_opcao_selecionada, $_menu_nivel, $this->_system->_auth->userData("ent_id"), $_mnu, $_opc, $_prg);
			//$this->_system->_dados['sidebar'] = $this->_menu->getMenu();
			 
			// Carregando as notificacoes, indicando PATHS
			//$this->_notificacao->setNotificacaoLink($this->_link_dominio);
			//$this->_notificacao->setNotificacaoLinkPortal($this->_server);
			 
			// Carregando as notificacoes para entidade
			//$this->_notificacao->loadNotificacao("ERP4web", "none", $this->_system->_auth->userData("ent_id"));
			//$this->_system->_dados['notificacao'] = $this->_notificacao->getNotificacao();
			 
			// Carregando tarefas c/ progress bar :)
			//$this->_notificacao->loadNotificacaoTask("ERP4web", "none", $this->_system->_auth->userData("ent_id"));
			//$this->_system->_dados['notificacao_task'] = $this->_notificacao->getNotificacao();
			 
			// Carregando Mensagens entre Entidades Internads e Externas :)
			//$this->_notificacao->loadNotificacaoMessage("ERP4web", "none", $this->_system->_auth->userData("ent_id"));
			//$this->_system->_dados['notificacao_message'] = $this->_notificacao->getNotificacao();
	
			// Montando o User Account Menu
			//$this->_user_account_menu->setAvatar($_ent_avatar);
			//$this->_user_account_menu->setMembroDesde($_membro_desde);
			//$this->_user_account_menu->setNome($this->_system->_auth->userData("ent_nome"));
			//$this->_user_account_menu->setNomeComplemento($this->_system->_auth->userTPE("tpe_descr"));
			//$this->_user_account_menu->setLinkDominio($this->_link_dominio);
			//$this->_system->_dados['user_account_menu'] = $this->_user_account_menu->getUserAccountMenu();
			
			//parametros de MENU e acesso
	        if ( ! isset($this->_system->_params) ) { $this->_system->_params['mnu'] = 0;	}
	        if ( ! array_key_exists('mnu', $this->_system->_params ) ) { $this->_system->_params['mnu'] = 0; }
	        if ( ! array_key_exists('opc', $this->_system->_params ) ) { $this->_system->_params['opc'] = 0; }
	        if ( ! array_key_exists('mnv', $this->_system->_params ) ) { $this->_system->_params['mnv'] = 0; }
	        if ( ! array_key_exists('prg', $this->_system->_params ) ) { $this->_system->_params['prg'] = 0; }
	        if ( ! array_key_exists('nva', $this->_system->_params ) ) { $this->_system->_params['nva'] = 0; } 
            $this->_system->_dados['mnu'] = $this->_system->_params["mnu"];
	        $this->_system->_dados['opc'] = $this->_system->_params["opc"];
	        $this->_system->_dados['mnv'] = $this->_system->_params["mnv"];
	        $this->_system->_dados['prg'] = $this->_system->_params["prg"];
	        $this->_system->_dados['nva'] = $this->_system->_params["nva"];
		
	        //echo "<script>alert('mnu: ".$this->_system->_dados['mnu']." Opc: ".$this->_system->_dados['opc']." Prg: ".$this->_system->_dados['prg']."');</script>";
	
			// Logando acesso a esse programa
			if ( array_key_exists("prg", $this->_system->_params) == true ) {
			    // PRG eh um programa valido 
				if ( $this->_system->_params['prg'] != 0 ) {
			   	  $this->_system->_auth->logEntidadeAcesso("PRG",  $this->_system->_params["mnu"], $this->_system->_params["opc"], $this->_system->_params["prg"]);
			   }
			}
		}
	}
?>